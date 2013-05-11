<?php namespace Wordpress\Composer;

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
        $wpCorePath    = 'wordpress/core';
        $wpContentPath = 'wordpress/wp-content';
        $path          = $package->getPrettyName();
        $pos           = strpos($path, '/');

        if ($pos !== FALSE)
        {
            $path = substr($path, $pos);
        }

        if ($this->composer->getPackage())
        {
            $extra = $this->composer->getPackage()->getExtra();

            if ( ! empty($extra['wordpress-coredir']))
            {
                $wpCorePath = rtrim($extra['wordpress-coredir'], '/');
            }

            if ( ! empty($extra['wordpress-wp-contentdir']))
            {
                $wpContentPath = rtrim($extra['wordpress-wp-contentdir'], '/');
            }
        }

        switch($package->getType())
        {
            case 'wordpress-core':
                $installPath = $wpCorePath;

                break;
            case 'wordpress-plugin':
                $installPath = $wpContentPath . '/plugins/' . $path;

                break;
            case 'wordpress-theme':
                $installPath = $wpContentPath . '/themes/' . $path;
                
                break;
        }

        return $installPath;
    }

    public function supports($type)
    {
        return in_array($type, $this->_types);
    }

}
