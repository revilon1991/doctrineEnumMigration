<?php

namespace DoctrineEnumMigration\Exception;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand;
use DoctrineEnumMigration\Interfaces\EnumTypeInterface;
use RuntimeException;

use function get_class;

class DoctrineEnumMigrationException extends RuntimeException
{
    public static function typeNotImplementInterface(Type $type): self
    {
        $typeClassName = get_class($type);
        $interfaceClassName = EnumTypeInterface::class;

        return new static("Type '$typeClassName' must implement interface '$interfaceClassName'");
    }

    public static function traitForInvalidClass(): self
    {
        $commandClassName = static::class;
        $updateCommandClassName = UpdateCommand::class;

        return new static(
            "You're use trait with class '$commandClassName' which not extend class '$updateCommandClassName'"
        );
    }
}
