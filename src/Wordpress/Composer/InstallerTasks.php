<?php namespace Wordpress\Composer;

use Composer\Script\Event;

class InstallerTasks {

    public static $params = array(
        'wordpress-wp-contentdir' => 'wordpress/wp-content',
        'wordpress-coredir'       => 'wordpress/core',
        'wordpress-wp-config'     => array(
            'site_url'  => 'http://localhost',
            'db_host'   => 'localhost',
            'db_name'   => 'wordpress',
            'db_user'   => 'root',
            'db_pass'   => '',
            'db_charset' => 'utf8',
            'db_collate' => '',
            'db_prefix'  => 'wp_',
            'generate_auth_keys' => true,
            'wp_lang'    => '',
            'wp_debug'   => false,
            'disallow_file_edit' => false,
            'wp_contenturl' => null,
        ),
    );

    public static function wpConfig(Event $event)
    {
        $params = self::$params;
        $extra = $event->getComposer()->getPackage()->getExtra();

        if (is_array($extra))
        {
            $params['wordpress-coredir'] = (isset($extra['wordpress-coredir']))
                ? $extra['wordpress-coredir']
                : $params['wordpress-coredir'];

            $params['wordpress-wp-contentdir'] = (isset($extra['wordpress-wp-contentdir']))
                ? $extra['wordpress-wp-contentdir']
                : $params['wordpress-wp-contentdir'];

            if (isset($extra['wordpress-wp-config']))
            {
                $params['wordpress-wp-config'] = array_merge(
                    self::$params['wordpress-wp-config'],
                    $extra['wordpress-wp-config']
                );
            }
        }

        $wpContentUrl = (is_null($params['wordpress-wp-config']['wp_contenturl']))
            ? (rtrim($params['wordpress-wp-config']['site_url'], '/') . '/wp-content')
            : $params['wordpress-wp-config']['wp_contenturl'];

        if (true === $params['wordpress-wp-config']['generate_auth_keys'])
        {
            $authKeys = file_get_contents('https://api.wordpress.org/secret-key/1.1/salt/');
        }
        else
        {
            $authKeys = "define('AUTH_KEY',         'put your unique phrase here');\n"
                . "define('SECURE_AUTH_KEY',  'put your unique phrase here');\n"
                . "define('LOGGED_IN_KEY',    'put your unique phrase here');\n"
                . "define('NONCE_KEY',        'put your unique phrase here');\n"
                . "define('AUTH_SALT',        'put your unique phrase here');\n"
                . "define('SECURE_AUTH_SALT', 'put your unique phrase here');\n"
                . "define('LOGGED_IN_SALT',   'put your unique phrase here');\n"
                . "define('NONCE_SALT',       'put your unique phrase here');\n";
        }

        if (isset($params['wordpress-wp-config']['content-dir']))
        {
            $wpConfigContentDir = $params['wordpress-wp-config']['content-dir'];
        }
        else
        {
            $wpConfigContentDir = '__DIR__ . \'/wp-content\'';
        }

        $wpConfigParams = array(
            ':wp_content_dir'          => $wpConfigContentDir,
            ':site_url'                => $params['wordpress-wp-config']['site_url'],
            ':db_host'                 => $params['wordpress-wp-config']['db_host'],
            ':db_name'                 => $params['wordpress-wp-config']['db_name'],
            ':db_user'                 => $params['wordpress-wp-config']['db_user'],
            ':db_pass'                 => $params['wordpress-wp-config']['db_pass'],
            ':db_charset'              => $params['wordpress-wp-config']['db_charset'],
            ':db_collate'              => $params['wordpress-wp-config']['db_collate'],
            ':db_prefix'               => $params['wordpress-wp-config']['db_prefix'],
            ':wp_lang'                 => $params['wordpress-wp-config']['wp_lang'],
            ':wp_debug'                => (false !== $params['wordpress-wp-config']['wp_debug']) ? 'true' : 'false',
            ':disallow_file_edit'      => (false !== $params['wordpress-wp-config']['disallow_file_edit']) ? 'true' : 'false',
            ':wp_content_url'          => $wpContentUrl,
            ':auth_keys'               => $authKeys,
        );

        $wpConfig = file_get_contents(__DIR__ . '/../../../templates/wp-config.php-dist');

        $wpConfig = str_replace(
            array_keys($wpConfigParams),
            $wpConfigParams,
            $wpConfig
        );

        file_put_contents($wpConfigParams[':wordpress-coredir'] . '/wp-config.php', $wpConfig);
    }

}