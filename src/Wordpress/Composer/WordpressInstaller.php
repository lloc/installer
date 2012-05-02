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
        $package->getPrettyName();

        var_dump($package);

        return 'vendor/'.$package->getPrettyName();
    }

    public function supports($type)
    {
        return in_array($type, $this->_types);
    }

}
