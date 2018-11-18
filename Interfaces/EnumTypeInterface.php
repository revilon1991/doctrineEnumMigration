<?php

namespace DoctrineEnumMigration\Interfaces;

interface EnumTypeInterface
{
    /**
     * Get all allow values from enum type
     *
     * @return array
     */
    public static function getValues(): array;
}
