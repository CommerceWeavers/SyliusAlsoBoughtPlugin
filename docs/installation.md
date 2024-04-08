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

commerce_weavers_sylius_also_bought:
    number_of_products_to_associate: 10 # default value
    batch_size_limit: 1000 # default value
```

### 4. Add trait to enhance Sylius Product model

```php
// src/Entity/Product/Product.php

<?php

declare(strict_types=1);

namespace App\Entity\Product;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BoughtTogetherProductsAwareInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BoughtTogetherProductsAwareTrait;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Product as BaseProduct;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_product')]
class Product extends BaseProduct implements BoughtTogetherProductsAwareInterface
{
    use BoughtTogetherProductsAwareTrait;
}
```

### 5. Execute migrations

```
bin/console doctrine:migrations:migrate -n
```

### 6. Run setup command

```
bin/console sylius:also-bought:setup
```

### 7. Synchronize bought together products

```
bin/console sylius:also-bought:synchronize
```

Run this command periodically to keep the bought together products up to date.
Use cron or any other scheduler to automate this process.

### 8. Rebuild the cache

```bash
bin/console cache:clear
bin/console cache:warmup
```
