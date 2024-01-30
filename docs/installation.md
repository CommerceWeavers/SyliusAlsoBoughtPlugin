## Installation

### 1. Require plugin with composer

```bash
composer require commerce-weavers/sylius-also-bought-plugin
```

### 2. Make sure the plugin is added to list of plugins

```php
// config/bundles.php

CommerceWeavers\SyliusAlsoBoughtPlugin\CommerceWeaversSyliusAlsoBoughtPlugin::class => ['all' => true],
```

### 3. Import configuration

```yaml
# config/packages/cw_sylius_also_bought_plugin.yaml

imports:
    - { resource: "@CommerceWeaversSyliusAlsoBoughtPlugin/config/app/config.yaml" }
```

### 4. Execute migrations

```
bin/console doctrine:migrations:migrate -n
```

### 5. Run setup command

```
bin/console sylius:also-bought:setup
```

### 6. Synchronize bought together products

```
bin/console sylius:also-bought:synchronize
```

Run this command periodically to keep the bought together products up to date.
Use cron or any other scheduler to automate this process.

### 7. Rebuild the cache

```bash
bin/console cache:clear
bin/console cache:warmup
```
