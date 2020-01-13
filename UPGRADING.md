# Upgrading from Nova Blog to 6.0

## Drafts

We've added [nova-drafts](https://github.com/optimistdigital/nova-drafts) field to replace previous internal drafts logic.  
**All draft functionality will remain** but in order to enable this feature you have to install the [nova-drafts](https://github.com/optimistdigital/nova-drafts) field package.

```bash
composer require optimistdigital/nova-drafts
```

We've also removed `'drafts_enabled' => true` from the config file, feel free to delete that line.
