<?php

declare(strict_types=1);

namespace DoctrineEnumMigration\Service;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping\ClassMetadata;
use DoctrineEnumMigration\Dto\EnumColumnDto;
use DoctrineEnumMigration\Tools\EnumComparator;
use DoctrineEnumMigration\Interfaces\EnumTypeInterface;
use DoctrineEnumMigration\Manager\EnumMigrationManager;
use DoctrineEnumMigration\Exception\DoctrineEnumMigrationException;

use function get_class;

class EnumMigrationService
{
    /**
     * @var EnumComparator
     */
    private $enumComparator;

    /**
     * @var EnumMigrationManager
     */
    private $manager;

    public function __construct(
        EnumComparator $enumComparator,
        EnumMigrationManager $manager
    ) {
        $this->enumComparator = $enumComparator;
        $this->manager = $manager;
    }

    public function getUpdateEnumSchemaSql(): array
    {
        $currentEnumColumnDtoList = $this->makeCurrentEnumColumnDtoList();

        $currentEnumTypeList = array_column($currentEnumColumnDtoList, 'typeName');
        $freshEnumColumnDtoList = $this->makeFreshEnumColumnDtoList($currentEnumTypeList);

        $sqlModifyList = [];

        foreach ($currentEnumColumnDtoList as $currentEnum) {
            foreach ($freshEnumColumnDtoList as $freshEnum) {
                if ($currentEnum->typeName === $freshEnum->typeName) {
                    $diffExist = $this->enumComparator->diffExist($currentEnum, $freshEnum);

                    if ($diffExist) {
                        $key = "{$freshEnum->tableName}.{$freshEnum->columnName}";

                        $sqlModifyList[$key] = $this->getModifySql($freshEnum);
                    }
                }
            }
        }

        return array_values($sqlModifyList);
    }

    private function getModifySql(EnumColumnDto $enumColumnDto): string
    {
        $sqlPattern = "ALTER TABLE %s MODIFY %s ENUM('%s') %s COMMENT '(DC2Type:%s)';";

        return sprintf(
            $sqlPattern,
            $enumColumnDto->tableName,
            $enumColumnDto->columnName,
            implode("','", $enumColumnDto->valueList),
            $enumColumnDto->nullable ? 'NULL' : 'NOT NULL',
            $enumColumnDto->typeName
        );
    }

    /**
     * @param array $currentEnumTypeList
     *
     * @return EnumColumnDto[]
     */
    private function makeFreshEnumColumnDtoList(array $currentEnumTypeList): array
    {
        $databasePlatform = $this->manager->getConnection()->getDatabasePlatform();

        /** @var ClassMetadata[] $metadataList */
        $metadataList = $this->manager->getMetadataFactory()->getAllMetadata();

        $freshEnumColumnDtoList = [];

        foreach ($metadataList as $metadata) {
            foreach ($metadata->getFieldNames() as $columnName) {
                $typeName = $metadata->getTypeOfField($columnName);
                $nullable = $metadata->isNullable($columnName);

                $currentEnumTypeFlipList = array_flip($currentEnumTypeList);

                if (isset($currentEnumTypeFlipList[$typeName])) {
                    $type = Type::getType($typeName);

                    if (!$type instanceof EnumTypeInterface) {
                        throw DoctrineEnumMigrationException::typeNotImplementInterface($type);
                    }

                    $tableName = $metadata->getTableName();
                    $valueList = get_class($type)::getValues();

                    $declaration = Type::getType($typeName)->getSQLDeclaration([], $databasePlatform);

                    $freshEnumColumnDtoList[] = new EnumColumnDto(
                        $tableName,
                        $columnName,
                        $valueList,
                        $declaration,
                        $typeName,
                        $nullable
                    );
                }
            }
        }

        return $freshEnumColumnDtoList;
    }

    /**
     * @return EnumColumnDto[]
     */
    private function makeCurrentEnumColumnDtoList(): array
    {
        $schemaManager = $this->manager->getConnection()->getSchemaManager();
        $currentEnumList = $this->manager->getCurrentEnumList();

        $freshEnumColumnDtoList = [];

        foreach ($currentEnumList as $value) {
            $tableName = $value['TABLE_NAME'];
            $columnName = $value['COLUMN_NAME'];
            $declaration = $value['COLUMN_TYPE'];
            $comment = $value['COLUMN_COMMENT'];
            $nullable = $value['IS_NULLABLE'] !== 'NO';
            $valueList = $this->getEnumValueListFromDeclaration($declaration);

            $typeName = $schemaManager->extractDoctrineTypeFromComment($comment, '');

            $freshEnumColumnDtoList[] = new EnumColumnDto(
                $tableName,
                $columnName,
                $valueList,
                $declaration,
                $typeName,
                $nullable
            );
        }

        return $freshEnumColumnDtoList;
    }

    private function getEnumValueListFromDeclaration(string $declaration): array
    {
        $result = str_replace(["enum('", "')"], '', $declaration);

        return explode("','", $result);
    }
}
