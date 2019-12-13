<?php

defined('EMONCMS_EXEC') or die('Restricted access');

function debug_controller()
{
    global $mysqli, $redis, $user, $path, $session, $route, $settings;
    $result = false;
    $dir = "Modules/debug";

    // Load html,css,js pages to the client
    if ($route->action === '')
    {
        $links = array(
            'settings' => 'EmonCMS Settings',
            'env' => 'Environment Variables',
            'phpinfo' => 'PHPInfo()'
        );
        return view("$dir/Views/links.php", array(
            'path' => $path.'debug/',
            'links' => $links
        ));
    }
    elseif ($route->action === 'settings')
    {
        // $route->format = 'json';
        $DEFAULT_INI = parse_ini_file("default-settings.ini", true);
        $SETTINGS_INI = parse_ini_file("settings.ini", true);
        $ENV_INI = parse_ini_file("settings.env.ini", true);

        return view("$dir/Views/settings-table.php", array(
            'arrays' => array(
                '_default' => $DEFAULT_INI,
                '_settings' => $SETTINGS_INI,
                '_env' => array_replace_recursive($ENV_INI, ini_check_envvars($ENV_INI)),
                '_merged' => $settings
            )
        ));
    }
    elseif ($route->action === 'phpinfo')
    {
        phpinfo();
        exit();
    }
    elseif ($route->action === 'env')
    {
        $route->format = 'json';
        ksort($_ENV);
        return $_ENV;
    }
}
