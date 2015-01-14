<?php

namespace Pagekit\System\Event;

use Pagekit\Auth\Event\LoginEvent;
use Pagekit\Application as App;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MigrationListener implements EventSubscriberInterface
{
    /**
     * Redirects to migration page on login.
     *
     * @param LoginEvent $event
     */
    public function onLogin(LoginEvent $event)
    {
        if ($event->getUser()->hasAccess('system: software updates') && App::migrator()->create('extensions/system/migrations', App::option()->get('system:version'))->get()) {
            $event->setResponse(App::response()->redirect('@system/migration'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'auth.login' => ['onLogin', 8]
        ];
    }
}
