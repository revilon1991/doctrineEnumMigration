<?php

declare(strict_types=1);

namespace DoctrineEnumMigration\Tools;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use DoctrineEnumMigration\Service\EnumMigrationService;

class EnumSchemaTool extends SchemaTool
{
    /**
     * @var EnumMigrationService
     */
    private $enumMigrationService;

    public function __construct(EntityManagerInterface $em, EnumMigrationService $enumMigrationService)
    {
        $this->enumMigrationService = $enumMigrationService;

        parent::__construct($em);
    }

    /**
     * @inheritdoc
     */
    public function getUpdateSchemaSql(array $classes, $saveMode = false): array
    {
        $updateEnumSchemaSqlList = $this->enumMigrationService->getUpdateEnumSchemaSql();

        return array_merge(parent::getUpdateSchemaSql($classes, $saveMode), $updateEnumSchemaSqlList);
    }
}
