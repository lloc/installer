<?php namespace Wordpress\Composer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class WordpressInstaller extends LibraryInstaller {

    protected $_types = array(
        'wordpress-plugin',
        'wordpress-theme',
    );

    public function getInstallPath(PackageInterface $package)
    {
        $wpContent = 'wordpress/wp-content/';
        $path      = $package->getPrettyName();
        $pos       = strpos($path, '/');

        if ($pos !== FALSE)
        {
            $path = substr($path, $pos);
        }

        switch($package->getType())
        {
            case 'wordpress-plugin':
                return $wpContent . 'plugins/'.$path;
                break;
            case 'wordpress-theme':
                return $wpContent . 'themes/'.$path;
                break;
        }
    }

    public function supports($type)
    {
        return in_array($type, $this->_types);
    }

}
