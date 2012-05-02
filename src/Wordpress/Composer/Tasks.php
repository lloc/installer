<?php

namespace Wordpress\Composer;

use Composer\Script\Event;

class Tasks {

    public static function init(Event $event)
    {
        $composer = $event->getComposer();

        var_dump($composer->getConfig());
    }

}
