<?php

namespace OptimistDigital\NovaBlog\Nova;

use Laravel\Nova\Panel;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Heading;
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
        $showCategoryColumnInIndex = config('nova-blog.hide_category_selector') === true ? null : BelongsTo::make(__('Category'), 'category', 'OptimistDigital\NovaBlog\Nova\Category')->nullable();
        $hideCategoryColumnInIndex = config('nova-blog.hide_category_selector') === true ? null : BelongsTo::make(__('Category'), 'category', 'OptimistDigital\NovaBlog\Nova\Category')->nullable()->hideFromIndex();

        $postContent = Flexible::make(__('Post content'), 'post_content')->hideFromIndex()
            ->addLayout(__('Text section'), 'text', [
                config('nova-blog.use_trix') === true ? Trix::make(__('Text content'), 'text_content') : Markdown::make(__('Text content'), 'text_content'),
            ])
            ->addLayout(__('Image section'), 'image', [
                Image::make(__('Image'), 'image')->deletable(false)->creationRules('required'),
                Text::make(__('Image caption'), 'caption'),
                Text::make(__('Alt (image alternate text)'), 'alt')
            ])
            ->addLayout('Other embed media section', 'other_media', [
                Textarea::make(__('Embed media code (twitter, iframe, etc.)'), 'media_code'),
                Text::make(__('Media caption'), 'caption')
            ]);

        if (config('nova-blog.include_froala_texteditor_option')) {
            $postContent->addLayout('Text section in Froala', 'text_froala', [
                Froala::make(__('Text section in Froala'), 'text_content_froala')
            ]);
        }

        $fields = [
            ID::make()->sortable(),
            config('nova-blog.use_trix') === true ? Trix::make(__('Title'), 'title')->rules('required')->alwaysShow() : Title::make(__('Title'), 'title')->rules('required')->alwaysShow(),
            config('nova-blog.hide_pinned_post_option') === true ? null : Boolean::make(__('Is pinned'), 'is_pinned'),
            config('nova-blog.include_include_in_bloglist') === true ? Boolean::make(__('Include in bloglist'), 'include_in_bloglist') : null,
            Slug::make(__('Slug'), 'slug')->rules('required', 'alpha_dash_or_slash')->hideWhenUpdating()->hideFromIndex()->hideFromDetail(),
            Select::make(__('Slug'), 'slug_generation')->options(['original' => 'Use existing', 'new_from_title' => 'Generate new from title', 'custom' => 'Create custom'])->hideWhenCreating()->hideFromDetail()->hideFromIndex()->rules('required')->resolveUsing(function () {
                return $this->slug_generation ?? 'original';
            }),
            ConditionalContainer::make([
                Slug::make(__('Custom slug'), 'slug')->rules('required'),
            ])->if('slug_generation = custom'),
            Text::make(__('Slug'), function () {
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
            DateTime::make(__('Published at'), 'published_at')->rules('required'),
            Textarea::make(__('Post introduction'), 'post_introduction'),
            config('nova-blog.include_featured_image') === true ? Image::make(__('Featured image'), 'featured_image') : null,
            (config('nova-blog.hide_category_column_from_index') === true) ? $hideCategoryColumnInIndex : $showCategoryColumnInIndex,

            $postContent,
            config('nova-blog.include_related_posts_feature') === true && config('nova-blog.hide_related_posts_column_from_index') === false ?
                Multiselect
                ::make(__('Related posts'), 'related_posts')
                ->options($relatedPostOptions)
                ->withMeta(['value' => $relatedPosts])
                : null,

            config('nova-blog.include_related_posts_feature') === true && config('nova-blog.hide_related_posts_column_from_index') === true ?
                Multiselect
                ::make(__('Related posts'), 'related_posts')
                ->options($relatedPostOptions)
                ->withMeta(['value' => $relatedPosts])
                ->hideFromIndex()
                : null,
        ];

        if (NovaBlog::hasNovaLang()) {
            config('nova-blog.hide_locale_column_from_index') === true ?  $fields[] = \OptimistDigital\NovaLang\NovaLangField::make(__('Locale'), 'locale', 'locale_parent_id')->onlyOnForms()->hideFromIndex() :
                $fields[] = \OptimistDigital\NovaLang\NovaLangField::make('Locale', 'locale', 'locale_parent_id')->onlyOnForms();
        } else {
            config('nova-blog.hide_locale_column_from_index') === true ?    $fields[] = LocaleField::make(__('Locale'), 'locale', 'locale_parent_id')
                ->locales($locales)
                ->onlyOnForms()->hideFromIndex() :
                $fields[] = LocaleField::make(__('Locale'), 'locale', 'locale_parent_id')
                ->locales($locales)
                ->onlyOnForms();
        }

        if (count($locales) > 1) {
            config('nova-blog.hide_locale_column_from_index') === true ? $fields[] = LocaleField::make(__('Locale'), 'locale', 'locale_parent_id')
                ->locales($locales)
                ->exceptOnForms()
                ->hideFromIndex() :
                $fields[] = LocaleField::make(__('Locale'), 'locale', 'locale_parent_id')
                ->locales($locales)
                ->exceptOnForms()
                ->maxLocalesOnIndex(3);
        } else if ($hasManyDifferentLocales) {
            config('nova-blog.hide_locale_column_from_index') === true ? $fields[] = Text::make(__('Locale'), 'locale')->exceptOnForms()->hideFromIndex() :
                $fields[] = Text::make(__('Locale'), 'locale')->exceptOnForms();
        }

        if (NovaBlog::hasNovaDrafts()) {
            $fields[] = \OptimistDigital\NovaDrafts\DraftButton::make(__('Draft'));
            $fields[] = \OptimistDigital\NovaDrafts\PublishedField::make(__('State'), 'published');
            $fields[] = \OptimistDigital\NovaDrafts\UnpublishButton::make(__('Unpublish'));
        }

        $fields[] = new Panel('SEO', $this->getSeoFields());

        if (count($templateFieldsAndPanels['fields']) > 0) {
            $fields[] = new Panel(
                'Page data',
                array_merge(
                    [Heading::make(__('Page data'))->hideFromDetail()],
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
            Heading::make(__('SEO')),
            Text::make(__('SEO Title'), 'seo_title')->hideFromIndex(),
            Text::make(__('SEO Description'), 'seo_description')->hideFromIndex(),
            Image::make(__('SEO Image'), 'seo_image')->hideFromIndex(),
        ];
    }

    public function title()
    {
        return $this->name . ' (' . $this->slug . ')';
    }

    /**
     * Label for resources
     *
     * @return string
     */
    public static function label()
    {
        return __('Posts');
    }

    public static function singularLabel()
    {
        return __('Post');
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
}
