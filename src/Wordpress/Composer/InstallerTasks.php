<?php namespace Wordpress\Composer;

use Composer\Script\Event;

class InstallerTasks {

    public function static wpConfig(Event $event)
    {
        $extra = $event->getComposer()->getPackage()->getExtra();

        var_dump($extra);
    }

}