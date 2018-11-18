Doctrine Enum Migration Bundle
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

Лицензия
--------

![license](https://img.shields.io/badge/License-proprietary-red.svg?style=flat-square)