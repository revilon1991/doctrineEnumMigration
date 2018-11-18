Itunes Receipt Bundle
=====================

Введение
--------

Бандл предоставляет возможность обновление Enum типов посредством комманды:
```bash
    php bin/console doctrine:schema:update --force
```

Установка
---------

### Шаг 1: Загрузка бандла

Добавьте в composer.json

```
    "repositories" : [
        // ...
        {
            "type" : "vcs",
            "url" : "git@github.com:revilon1991/doctrineEnumMigration.git"
        }
        // ...
    ],
```

Откройте консоль и, перейдя в директорию проекта, выполните следующую команду:

```bash
    composer require revilon1991/doctrineEnumMigration
```

*Эта команда подразумевает что [Composer](https://getcomposer.org) установлен и доступен глобально.*

### Шаг 2: Подключение бандла (symfony 2.*)

После включите бандл добавив его в список зарегистрированных бандлов в `app/AppKernel.php` файл вашего проекта:

```php
<?php
// app/AppKernel.php

class AppKernel extends Kernel
{
    // ...

    public function registerBundles()
    {
        $bundles = [
            // ...
            new DoctrineEnumMigration\DoctrineEnumMigrationBundle(),
        ];

        return $bundles;
    }

    // ...
}
```

Конфигурация
------------

Чтобы начать использовать бандл не требуется предварительной конфигурации.
Для переключения валидации Receip'ов на "боевой" сервер пропишите:

```yaml
# app/config/config.yml
wakeapp_itunes_receipt:
    receipt_type: 'production'
```

Доступные значения receipt_type: 'production', 'sandbox'

Можно использовать свой Guzzle-клиент. Для этого необходимо переопределить сервис:

```yaml
# app/config/service.yml
wakeapp_itunes_receipt.http_client:
    class: Your\Custom\Guzzle\Client
```

Использование
-------------

```php
<?php
// Получить сервис
/** @var \Wakeapp\Bundle\ItunesReceiptBundle\Service\WakeappItunesReceiptService $versioningService */
$itunesReceiptService = $this->getContainer()->get(\Wakeapp\Bundle\ItunesReceiptBundle\Service\WakeappItunesReceiptService::class);

/** 
 * @var string $receipt The base64 encoded receipt data.
 */
$receipt = 'ABCD==';

/** 
 * @var bool|null $excludeOldTransactions   Only used for receipts that contain auto-renewable subscriptions. 
 *                                          Your app’s shared secret (a hexadecimal string).
 */
$password = 'YOUR_PASSWORD';

/** 
 * @var bool|null $excludeOldTransactions   Only used for iOS7 style app receipts that contain auto-renewable or 
 *                                          non-renewing subscriptions. If value is true, response includes only the 
 *                                          latest renewal transaction for any subscriptions.
 */
$excludeOldTransactions = true;

try {
    $itunesReceiptInfo = $service->validate($receipt, $password, $excludeOldTransactions);
} catch (Wakeapp\Bundle\ItunesReceiptBundle\Exception\ReceiptErrorStatus $e) {
    die("ReceiptErrorStatus {$e->getCode()}: {$e->getMessage()}\n");
} catch (Wakeapp\Bundle\ItunesReceiptBundle\Exception\WakeappItunesReceiptException $e) {
    die("Undefined receipt error {$e->getCode()}: {$e->getMessage()}\n");
}

if ($itunesReceiptInfo->isActive()) {
    // customer have active subscription or trial period
}

// others examples

$itunesReceiptInfo->getRawReceipt();
$itunesReceiptInfo->getRawLatestReceipt();
$itunesReceiptInfo->getEnvironment();
$itunesReceiptInfo->getBundleId();
$isActive = $itunesReceiptInfo->isActive();

if ($isActive) {
    $itunesReceiptInfo->isTrial();
    $itunesReceiptInfo->getActiveExpirationDate();
    $itunesReceiptInfo->getActiveTransactionId();
    $itunesReceiptInfo->getActiveProductId();
}

$expirationIntent = $itunesReceiptInfo->getExpirationIntent('product.id');

if ($expirationIntent) {
    $itunesReceiptInfo->getExpirationIntentText('product.id');
}
```

Лицензия
--------

![license](https://img.shields.io/badge/License-proprietary-red.svg?style=flat-square)