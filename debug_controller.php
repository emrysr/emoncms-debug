<?php

defined('EMONCMS_EXEC') or die('Restricted access');

function debug_controller()
{
    global $mysqli, $redis, $user, $path, $session, $route, $settings;
    $result = false;
    $dir = "Modules/debug";

    $DEFAULT_INI = parse_ini_file("default-settings.ini", true);
    $SETTINGS_INI = parse_ini_file("settings.ini", true);
    $ALL_ENV_INI = parse_ini_file("settings.env.ini", true);
    $ENV_FOUND = ini_check_envvars($ALL_ENV_INI);

    $arrays = array(
        '_default' => $DEFAULT_INI,
        '_settings' => $SETTINGS_INI,
        '_available_env' => $ALL_ENV_INI,
        '_env_found' => $ENV_FOUND,
        '_env' => array_replace_recursive($ALL_ENV_INI, $ENV_FOUND),
        '_merged' => $settings
    );
    // Load html,css,js pages to the client
    if ($route->action === '') {

        $links = array('debug/settings' => 'EmonCMS Settings');
        if(!empty($arrays['_env_found']))
            $links['debug/env'] = 'ENV Variables (json)';
            
        $links['admin/system.json'] = 'system (json)';
        $links['debug/phpinfo'] = 'PHPInfo()';
        
        return view("$dir/Views/links.php", array(
            'arrays'=> $arrays,
            'path' => $path.'debug/',
            'links' => $links
        ));
    }
    elseif ($route->action === 'settings')
    {
        return view("$dir/Views/settings-table.php", array('arrays'=>$arrays));
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
