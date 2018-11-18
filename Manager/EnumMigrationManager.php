<?php

declare(strict_types=1);

namespace DoctrineEnumMigration\Manager;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory;

class EnumMigrationManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getCurrentEnumList(): array
    {
        $sqlTypes = "
            SELECT
              TABLE_NAME,
              COLUMN_NAME,
              COLUMN_TYPE,
              IS_NULLABLE,
              COLUMN_COMMENT
            FROM information_schema.columns
            WHERE 1
              AND table_schema = :database_name
              AND DATA_TYPE = 'enum'
        ";

        $databaseName = $this->em->getConnection()->getDatabase();

        $stmt = $this->em->getConnection()->executeQuery($sqlTypes, [
            'database_name' => $databaseName,
        ]);


        return $stmt->fetchAll();
    }

    public function getConnection(): Connection
    {
        return $this->em->getConnection();
    }

    public function getMetadataFactory(): ClassMetadataFactory
    {
        return $this->em->getMetadataFactory();
    }
}
