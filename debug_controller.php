<?php

defined('EMONCMS_EXEC') or die('Restricted access');

function debug_controller()
{
    global $mysqli, $redis, $user, $path, $session, $route, $settings;
    $result = false;

    // Load html,css,js pages to the client
    if ($route->action === '')
    {
        $links = array(
            'settings' => 'EmonCMS Settings',
            'env' => 'Environment Variables',
            'phpinfo' => 'PHPInfo()'
        );
        return view("Modules/debug/Views/links.php", array(
            'path' => $path.'debug/',
            'links' => $links
        ));
    }
    elseif ($route->action === 'settings')
    {
        $route->format = 'json';
        $CONFIG_INI = parse_ini_file("default-settings.ini", true);
        $CUSTOM_INI = parse_ini_file("settings.ini", true);
        $ENV_INI = parse_ini_file("settings.env.ini", true);
        return ini_check_envvars(array_replace_recursive($CONFIG_INI, $CUSTOM_INI, $ENV_INI));
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
