<?php

namespace Hakam\MultiTenancyBundle\EventListener;

use Hakam\MultiTenancyBundle\Event\SwitchDbEvent;
use Hakam\MultiTenancyBundle\Services\DbConfigService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Ramy Hakam <pencilsoft1@gmail.com>
 */
class DbSwitchEventListener implements EventSubscriberInterface
{

    public function __construct(
        private ContainerInterface $container,
        private DbConfigService    $dbConfigService,
        private string             $databaseURL
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return
            [
                SwitchDbEvent::class => 'onHakamMultiTenancyBundleEventSwitchDbEvent',
            ];
    }

    public function onHakamMultiTenancyBundleEventSwitchDbEvent(SwitchDbEvent $switchDbEvent): void
    {
        $dbConfig = $this->dbConfigService->findDbConfig($switchDbEvent->getDbIndex());
        $tenantConnection = $this->container->get('doctrine')->getConnection('tenant');
        $params = [
            'driver' => $dbConfig->getDbDriver(),
            'host' => $dbConfig->getDbHost(),
            'port' => $dbConfig->getDbPort(),
            'dbname' => $dbConfig->getDbName(),
            'user' => $dbConfig->getDbUsername() ?? $this->parseDatabaseUrl($this->databaseURL)['user'],
            'password' => $dbConfig->getDbPassword() ?? $this->parseDatabaseUrl($this->databaseURL)['password'],
        ];
        $tenantConnection->switchConnection($params);
    }

    private function parseDatabaseUrl(string $databaseURL): array
    {
        $url = parse_url($databaseURL);
        return [
            'dbname' => substr($url['path'], 1),
            'user' => $url['user'],
            'password' => $url['pass'],
        ];
    }
}
