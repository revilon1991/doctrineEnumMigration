<?php

declare(strict_types=1);

namespace DoctrineEnumMigration\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use DoctrineEnumMigration\Service\EnumMigrationService;
use DoctrineEnumMigration\Traits\EnumMigrationCommandTrait;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\UpdateSchemaDoctrineCommand;

class UpdateSchemaCommand extends UpdateSchemaDoctrineCommand
{
    use EnumMigrationCommandTrait;

    /**
     * @var EnumMigrationService
     */
    private $enumMigrationService;

    public function __construct(string $name = null, EnumMigrationService $enumMigrationService)
    {
        $this->enumMigrationService = $enumMigrationService;

        parent::__construct($name);
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        return parent::execute($input, $output);
    }
}
