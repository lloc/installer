<?php

namespace Wordpress\Composer;

use Composer\Script\Event;

class Tasks {

    public static function init(Event $event)
    {
        $root = __DIR__ . '/../../../../../../';

        var_dump(realpath($root));
    }

}
