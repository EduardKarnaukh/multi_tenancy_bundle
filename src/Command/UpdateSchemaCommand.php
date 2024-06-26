<?php

namespace Hakam\MultiTenancyBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Command\Proxy\UpdateSchemaDoctrineCommand;
use Doctrine\ORM\EntityManagerInterface;
use Hakam\MultiTenancyBundle\Event\SwitchDbEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

#[AsCommand(
    name: 'tenant:schema:update',
    description: 'Proxy to launch doctrine:schema:update with custom databases.',
)]
final class UpdateSchemaCommand extends Command
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('database_id', 't', InputArgument::OPTIONAL, 'Database ID to update.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $newInput = new ArrayInput([
            'command' => 'doctrine:schema:update',
            '--force' => null,
            '--complete' => null,
//            '--dump-sql' => null,
            '--em' => 'tenant'
        ]);

        $databaseId = $input->getOption('database_id');
        $repo = $this->entityManager->getRepository($this->container->getParameter('hakam.tenant_db_list_entity'));
        $tenant = $repo->findOneBy(['id' => $databaseId]);
        if (!$tenant) {
            $io->error(sprintf('No tenant databases found. Check Tenants or DB Name if passed as an option.'));
            return Command::FAILURE;
        }

        try {
            $newInput->setInteractive($input->isInteractive());

            $switchEvent = new SwitchDbEvent($tenant->getId());
            $this->eventDispatcher->dispatch($switchEvent);

            $otherCommand = new UpdateSchemaDoctrineCommand();

            $otherCommand->setApplication(new Application($this->container->get('kernel')));
            $otherCommand->run($newInput, $output);
            $io->success(sprintf('Tenant database %s updated', $tenant->getDbName()));
        } catch (\Exception $e) {
            $io->error(sprintf('Tenant database %s not updated: %s',  $tenant->getDbName(), $e->getMessage()));
        }

        return Command::SUCCESS;
    }
}
