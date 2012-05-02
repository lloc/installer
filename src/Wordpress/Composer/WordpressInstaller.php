<?php

namespace Wordpress\Composer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class WordpressInstaller extends LibraryInstaller {

    protected $_types = array(
        'wordpress-plugin',
        'wordpress-theme',
        'wordpress-core',
    );

    public function getInstallPath(PackageInterface $package)
    {
        $pos = strpos($package->getPrettyName(), '/');

        if ($pos)
        {
            $path = substr($package->getPrettyName(), $pos);
        }
        else
        {
            $path = $package->getPrettyName();
        }

        switch($package->getType())
        {
            case 'wordpress-core':
                return 'public/'.$path;
                break;
            case 'wordpress-plugin':
                return 'plugins/'.$path;
                break;
            case 'wordpress-theme':
                return 'themes/'.$path;
                break;
        }
    }

    public function supports($type)
    {
        return in_array($type, $this->_types);
    }

}
