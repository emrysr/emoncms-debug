<?php

defined('EMONCMS_EXEC') or die('Restricted access');

function debug_controller()
{
    global $mysqli, $redis, $user, $path, $session, $route, $settings, $log;
    $result = false;
    
    if (!$session['admin']) {
        // not $session['admin']

        if ($settings['updatelogin']===true) {
            $route->format = 'html';
            if ($route->action == 'db')
            {
                $applychanges = false;
                if (isset($_GET['apply']) && $_GET['apply']==true) $applychanges = true;

                require_once "Lib/dbschemasetup.php";
                $updates = array(array(
                    'title'=>"Database schema", 'description'=>"",
                    'operations'=>db_schema_setup($mysqli,load_db_schema(),$applychanges)
                ));

                return array('content'=>view("Modules/admin/update_view.php", array('applychanges'=>$applychanges, 'updates'=>$updates)));
            }
        } else {
            // user not admin level display login
            $log->error(sprintf('%s|%s',_('Not Admin'), implode('/',array_filter(array($route->controller,$route->action,$route->subaction)))));
            $message = urlencode(_('Admin Authentication Required'));
            
            $referrer = urlencode(base64_encode(filter_var($_SERVER['REQUEST_URI'] , FILTER_SANITIZE_URL)));
            return sprintf(
                '<div class="alert alert-warn mt-3"><h4 class="mb-1">%s</h4>%s. <a href="%s" class="alert-link">%s</a></div>', 
                _('Admin Authentication Required'),
                _('Session timed out or user not Admin'),
                sprintf("%suser/logout?msg=%s&ref=%s",$path, $message, $referrer),
                _('Re-authenticate to see this page')
            );
        }


    } else {
        // user is admin
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

        return array('content'=>$result,'message'=>$message);
    }
}
