<?php

namespace Wordpress\Composer;

use Composer\Script\Event;

class Tasks {

    public static function init(Event $event)
    {
        $root = realpath(__DIR__ . '/../../../../../../');

        if ($root !== FALSE)
        {
            $root .= DIRECTORY_SEPARATOR;
        }

        $paths = array(
            'public/wordpress/wp-content/themes',
            'public/wordpress/wp-content/plugins',
        );

        foreach ($paths as $path)
        {
            $target = substr($path, strrpos($path, '/'));

            if (is_dir($root . $path))
            {
                self::rrmdir($root . $path);
            }

            if ( ! is_dir($root . $target))
            {
                mkdir($root . $target, 0755);
                chmod($root . $target, 0755);
            }

            symlink($root . $path, $root . $target);
        }
    }

    public static function rrmdir($dir)
    {
        $fp = opendir($dir);

        if ($fp)
        {
            while ($f = readdir($fp))
            {
                $file = $dir . DIRECTORY_SEPARATOR . $f;

                if ($f == '.' OR $f == '..')
                {
                    continue;
                }
                elseif (is_dir($file) AND ! is_link($file))
                {
                    self::rrmdir($file);
                }
                else
                {
                    unlink($file);
                }
            }

            closedir($fp);
            rmdir($fp);
        }
    }

}
