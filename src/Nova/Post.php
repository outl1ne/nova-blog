<?php

namespace OptimistDigital\NovaBlog\Nova;

use Laravel\Nova\Panel;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Froala\NovaFroalaField\Froala;
use OptimistDigital\NovaBlog\NovaBlog;
use Whitecube\NovaFlexibleContent\Flexible;
use Laravel\Nova\Http\Requests\NovaRequest;
use OptimistDigital\NovaBlog\Nova\Fields\Slug;
use OptimistDigital\NovaBlog\Nova\Fields\Title;
use OptimistDigital\NovaLocaleField\LocaleField;
use OptimistDigital\NovaBlog\Models\RelatedPost;
use OptimistDigital\MultiselectField\Multiselect;
use DigitalCreative\ConditionalContainer\ConditionalContainer;
use DigitalCreative\ConditionalContainer\HasConditionalContainer;

class Post extends TemplateResource
{
    use HasConditionalContainer;
    public static $title = 'name';
    public static $model = 'OptimistDigital\NovaBlog\Models\Post';
    public static $displayInNavigation = false;

    protected $type = 'post';

    public function fields(Request $request)
    {
        // Get base data
        $tableName = config('nova-blog.blog_posts_table', 'nova_blog_posts');
        $templateClass = $this->getTemplateClass();
        $templateFieldsAndPanels = $this->getTemplateFieldsAndPanels();
        $locales = NovaBlog::getLocales();
        $hasManyDifferentLocales = Post::select('locale')->distinct()->get()->count() > 1;

        $relatedPostOptions = [];
        \OptimistDigital\NovaBlog\Models\Post::all()->filter(function ($post) {
            return $post->id !== $this->id;
        })->each(function ($post) use (&$relatedPostOptions) {
            $relatedPostOptions[$post->id] = $post->title;
        });

        $relatedPosts = RelatedPost::where('post_id', $this->id)->pluck('related_post_id');
        $showCategoryColumnInIndex = config('nova-blog.hide_category_selector') === true ? null : BelongsTo::make(__('novaBlog.category'), 'category', 'OptimistDigital\NovaBlog\Nova\Category')->nullable();
        $hideCategoryColumnInIndex = config('nova-blog.hide_category_selector') === true ? null : BelongsTo::make(__('novaBlog.category'), 'category', 'OptimistDigital\NovaBlog\Nova\Category')->nullable()->hideFromIndex();

        $postContent = Flexible::make(__('novaBlog.postContent'), 'post_content')->hideFromIndex()
            ->addLayout(__('novaBlog.textSection'), 'text', [
                config('nova-blog.use_trix') === true ? Trix::make(__('novaBlog.textContent'), 'text_content') : Markdown::make(__('novaBlog.textContent'), 'text_content'),
            ])
            ->addLayout(__('novaBlog.imageSection'), 'image', [
                Image::make(__('novaBlog.image'), 'image')->deletable(false)->creationRules('required'),
                Text::make(__('novaBlog.imageCaption'), 'caption'),
                Text::make(__('novaBlog.imageAlt'), 'alt')
            ])
            ->addLayout(__('novaBlog.embedMediaSection'), 'other_media', [
                Textarea::make(__('novaBlog.embedMediaCode'), 'media_code'),
                Text::make(__('novaBlog.embedMediaCaption'), 'caption')
            ])->button(__('novaBlog.addLayoutButton'));

        if (config('nova-blog.include_froala_texteditor_option')) {
            $postContent->addLayout(__('novaBlog.textSectionFroala'), 'text_froala', [
                Froala::make(__('novaBlog.textSectionFroala'), 'text_content_froala'),
            ]);
        }

        $fields = [
            ID::make()->sortable(),
            config('nova-blog.use_trix') === true ? Trix::make(__('novaBlog.title'), 'title')->rules('required')->alwaysShow() : Title::make(__('novaBlog.title'), 'title')->rules('required')->alwaysShow(),
            config('nova-blog.hide_pinned_post_option') === true ? null : Boolean::make(__('novaBlog.isPinned'), 'is_pinned'),
            config('nova-blog.include_include_in_bloglist') === true ? Boolean::make(__('novaBlog.includeInBloglist'), 'include_in_bloglist') : null,
            Slug::make(__('novaBlog.slug'), 'slug')->rules('required', 'alpha_dash_or_slash')->hideWhenUpdating()->hideFromIndex()->hideFromDetail(),
            Select::make(__('novaBlog.slug'), 'slug_generation')
                ->options(
                    [
                        'original' => __('novaBlog.slugOriginal'),
                        'new_from_title' => __('novaBlog.slugNewFromTitle'),
                        'custom' => __('novaBlog.slugCreateCustom')
                    ]
                )->hideWhenCreating()->hideFromDetail()->hideFromIndex()->rules('required')->resolveUsing(function () {
                return $this->slug_generation ?? 'original';
            }),
            ConditionalContainer::make([
                Slug::make(__('novaBlog.customSlug'), 'slug')->rules('required'),
            ])->if('slug_generation = custom'),
            Text::make(__('novaBlog.slug'), function () {
                $previewToken = $this->childDraft ? $this->childDraft->preview_token : $this->preview_token;
                $previewPart = $previewToken ? '?preview=' . $previewToken : '';
                $pagePath = $this->resource->slug;
                $pageBaseUrl = NovaBlog::getPageUrl($this->resource);
                $pageUrl = !empty($pageBaseUrl) ? $pageBaseUrl . $previewPart : null;
                $buttonText = $this->resource->isDraft() ? 'View draft' : 'View';

                if (empty($pageBaseUrl)) return "<span class='bg-40 text-sm py-1 px-2 rounded-lg whitespace-no-wrap'>$pagePath</span>";

                return "<div class='whitespace-no-wrap'>
                            <span class='bg-40 text-sm py-1 px-2 rounded-lg'>$pagePath</span>
                            <a target='_blank' href='$pageUrl' class='text-sm py-1 px-2 text-primary no-underline dim font-bold'>$buttonText</a>
                        </div>";
            })->asHtml()->exceptOnForms(),
            DateTime::make(__('novaBlog.publishedAt'), 'published_at')->rules('required'),
            Textarea::make(__('novaBlog.postIntroduction'), 'post_introduction'),
            config('nova-blog.include_featured_image') === true ? Image::make(__('novaBlog.featuredImage'), 'featured_image') : null,
            (config('nova-blog.hide_category_column_from_index') === true) ? $hideCategoryColumnInIndex : $showCategoryColumnInIndex,

            $postContent,
            config('nova-blog.include_related_posts_feature') === true && config('nova-blog.hide_related_posts_column_from_index') === false ?
                Multiselect
                ::make(__('novaBlog.relatedPosts'), 'related_posts')
                ->options($relatedPostOptions)
                ->withMeta(['value' => $relatedPosts])
                : null,

            config('nova-blog.include_related_posts_feature') === true && config('nova-blog.hide_related_posts_column_from_index') === true ?
                Multiselect
                ::make(__('novaBlog.relatedPosts'), 'related_posts')
                ->options($relatedPostOptions)
                ->withMeta(['value' => $relatedPosts])
                ->hideFromIndex()
                : null,
        ];

        if (NovaBlog::hasNovaLang()) {
            config('nova-blog.hide_locale_column_from_index') === true ?  $fields[] = \OptimistDigital\NovaLang\NovaLangField::make(__('novaBlog.locale'), 'locale', 'locale_parent_id')->onlyOnForms()->hideFromIndex() :
                $fields[] = \OptimistDigital\NovaLang\NovaLangField::make(__('novaBlog.locale'), 'locale', 'locale_parent_id')->onlyOnForms();
        } else {
            config('nova-blog.hide_locale_column_from_index') === true ?    $fields[] = LocaleField::make(__('novaBlog.locale'), 'locale', 'locale_parent_id')
                ->locales($locales)
                ->onlyOnForms()->hideFromIndex() :
                $fields[] = LocaleField::make(__('novaBlog.locale'), 'locale', 'locale_parent_id')
                ->locales($locales)
                ->onlyOnForms();
        }

        if (count($locales) > 1) {
            config('nova-blog.hide_locale_column_from_index') === true ? $fields[] = LocaleField::make(__('novaBlog.locale'), 'locale', 'locale_parent_id')
                ->locales($locales)
                ->exceptOnForms()
                ->hideFromIndex() :
                $fields[] = LocaleField::make(__('novaBlog.locale'), 'locale', 'locale_parent_id')
                ->locales($locales)
                ->exceptOnForms()
                ->maxLocalesOnIndex(3);
        } else if ($hasManyDifferentLocales) {
            config('nova-blog.hide_locale_column_from_index') === true ? $fields[] = Text::make(__('novaBlog.locale'), 'locale')->exceptOnForms()->hideFromIndex() :
                $fields[] = Text::make(__('novaBlog.locale'), 'locale')->exceptOnForms();
        }

        if (NovaBlog::hasNovaDrafts()) {
            $fields[] = \OptimistDigital\NovaDrafts\DraftButton::make(__('novaBlog.draft'),'draft');
            $fields[] = \OptimistDigital\NovaDrafts\PublishedField::make(__('novaBlog.status'), 'published');
            $fields[] = \OptimistDigital\NovaDrafts\UnpublishButton::make(__('novaBlog.unpublish'),'Unpublish');
        }

        $fields[] = new Panel(__('novaBlog.seo'), $this->getSeoFields());

        if (count($templateFieldsAndPanels['fields']) > 0) {
            $fields[] = new Panel(
                __('novaBlog.pageData'),
                array_merge(
                    [Heading::make(__('novaBlog.pageData'))->hideFromDetail()],
                    $templateFieldsAndPanels['fields']
                )
            );
        }
        if (count($templateFieldsAndPanels['panels']) > 0) {
            $fields = array_merge($fields, $templateFieldsAndPanels['panels']);
        }

        return collect($fields)->filter(function ($field) {
            return $field !== null;
        })->toArray();
    }

    protected function getSeoFields()
    {
        return [
            Text::make(__('novaBlog.seoTitle'), 'seo_title')->hideFromIndex(),
            Text::make(__('novaBlog.seoDescription'), 'seo_description')->hideFromIndex(),
            Image::make(__('novaBlog.seoImage'), 'seo_image')->hideFromIndex(),
        ];
    }

    public function title()
    {
        return $this->name . ' (' . $this->slug . ')';
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $column = config('nova-blog.blog_posts_table', 'nova_blog_posts') . '.locale';
        $query->doesntHave('childDraft');
        if (NovaBlog::hasNovaLang())
            $query->where(function ($subQuery) use ($column) {
                $subQuery->where($column, nova_lang_get_active_locale())
                    ->orWhereNotIn($column, array_keys(nova_lang_get_all_locales()));
            });
        return $query;
    }

    public static function label()
    {
        return __('novaBlog.posts');
    }

    public static function singularLabel()
    {
        return __('novaBlog.post');
    }
}
