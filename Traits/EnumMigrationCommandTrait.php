<?php

namespace DoctrineEnumMigration\Traits;

use Doctrine\ORM\Tools\SchemaTool;
use DoctrineEnumMigration\Tools\EnumSchemaTool;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use DoctrineEnumMigration\Service\EnumMigrationService;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand;
use DoctrineEnumMigration\Exception\DoctrineEnumMigrationException;

trait EnumMigrationCommandTrait
{
    /**
     * @var EnumMigrationService
     */
    private $enumMigrationService;

    protected function executeSchemaCommand(
        InputInterface $input,
        OutputInterface $output,
        SchemaTool $schemaTool,
        array $metadataList,
        SymfonyStyle $symfonyStyle = null
    ): ?int {
        $classParents = class_parents(static::class);
        $classParents = array_flip($classParents);

        if (!isset($classParents[UpdateCommand::class])) {
            throw DoctrineEnumMigrationException::traitForInvalidClass();
        }

        if (!$symfonyStyle) {
            $symfonyStyle = new SymfonyStyle($input, $output);
        }

        /** @var EntityManagerHelper $emHelper */
        $emHelper = $this->getHelper('em');

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $emHelper->getEntityManager();

        if (!$metadataList) {
            $metadataList = $em->getMetadataFactory()->getAllMetadata();
        }

        if (empty($metadataList)) {
            $symfonyStyle->success('No Metadata Classes to process.');

            return 0;
        }

        return parent::executeSchemaCommand(
            $input,
            $output,
            new EnumSchemaTool($em, $this->enumMigrationService),
            $metadataList,
            $symfonyStyle
        );
    }

    public function setEnumMigrationService(EnumMigrationService $enumMigrationService): void
    {
        $this->enumMigrationService = $enumMigrationService;
    }
}
