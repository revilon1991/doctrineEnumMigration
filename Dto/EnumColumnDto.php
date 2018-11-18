<?php

declare(strict_types=1);

namespace DoctrineEnumMigration\Dto;

use Doctrine\DBAL\Types\Type;

class EnumColumnDto
{
    /**
     * @var string
     */
    public $tableName;

    /**
     * @var string
     */
    public $columnName;

    /**
     * @var Type
     */
    public $valueList;

    /**
     * @var string
     */
    public $declaration;

    /**
     * @var string
     */
    public $typeName;

    /**
     * @var bool
     */
    public $nullable;

    public function __construct(
        string $tableName,
        string $columnName,
        array $valueList,
        string $declaration,
        string $typeName,
        bool $nullable
    ) {
        $this->tableName = $tableName;
        $this->columnName = $columnName;
        $this->valueList = $valueList;
        $this->declaration = $declaration;
        $this->typeName = $typeName;
        $this->nullable = $nullable;
    }
}
