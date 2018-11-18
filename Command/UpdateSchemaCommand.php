<?php

declare(strict_types=1);

namespace DoctrineEnumMigration\Command;

use DoctrineEnumMigration\Traits\EnumMigrationCommandTrait;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\UpdateSchemaDoctrineCommand;

class UpdateSchemaCommand extends UpdateSchemaDoctrineCommand
{
    use EnumMigrationCommandTrait;
}
