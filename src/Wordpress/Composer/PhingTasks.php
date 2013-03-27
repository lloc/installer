<?php

namespace Wordpress\Composer;

use Composer\Script\Event;

class PhingTasks {

    public static function postInstall(Event $e)
    {
        $io = $e->getIO();

        $phingProcess = new Process('vendor/bin/phing install');

        $phingProcess->run();

        if ( ! $phingProcess->isSuccessful())
        {
            $io->write($phingProcess->getErrorOutput());
            return;
        }

        $io->write($phingProcess->getOutput());
    }

}