<?php
function getValue($arr, $key = "", $subkey = "") {
    if(empty($subkey)) {
        // return value ouside [group]
        if(isset($arr[$key])) {
            return $arr[$key];
        }
    } else {
        // return value within a [group]
        if(isset($arr[$key])) {
            if(isset($arr[$key][$subkey])) {
                if( is_iterable($arr[$key][$subkey]) ) {
                    // show array as a list of key=value pairs
                    return rawurldecode(http_build_query($arr[$key][$subkey]));
                } else {
                    // show single value of setting within [group]
                    return var_export($arr[$key][$subkey], true);
                }
            }
        } else {
            // nothing found. return blank string;
            return '';
        }
    }
}
function buildRow($arrays, $key, $_value) {
    $row = '';
    if(is_array($_value)) : 
        $row .= sprintf('<tr><th class="text-left" colspan = "100%%"><h4>%s</h4></th></tr>', strtoupper($key));
        foreach ($_value as $subkey=>$val):
            $row .= sprintf('
                <tr>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                </tr>',

                $subkey,
                getValue($arrays['_default'], $key, $subkey),
                getValue($arrays['_settings'], $key, $subkey),
                getValue($arrays['_env'], $key, $subkey),
                getValue($arrays['_merged'], $key, $subkey)
            );
        endforeach;
    endif;
    return $row;
}

$rows = '';
foreach ($arrays['_default'] as $section_name=>$section_values):
    // var_dump($arrays['_settings'][$section_name]);
    // exit(PHP_EOL.__FILE__.__LINE__);
    // show settings not in a [section]
    if(!is_iterable($section_values)) {
        $rows .= sprintf('
            <tr>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
            </tr>',

            $section_name,
            getValue($arrays['_default'], $section_name),
            getValue($arrays['_settings'], $section_name),
            getValue($arrays['_env'], $section_name),
            getValue($arrays['_merged'], $section_name)
        );
    } else {
        // show items grouped by [section] in the settings file
        $rows .= buildRow($arrays, $section_name, $section_values);
    }
endforeach;




/*
output the above table rows as one table to keep the column alignment  
*/
?>

<h2>Settings:</h2>
<p class="lead">The last column is the combined values of the 3 different sources.</p>
<p>Values in <code>default.ini</code> are overwritten by values in <code>settings.ini</code> and the <code>ENV</code> vars overwrite them all if set. </p>
<table class="table table-bordered">
    <thead>
        <tr>
            <th></th>
            <th>default.ini</th>
            <th>settings.ini</th>
            <th>ENV</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr><th class="text-left" colspan = "100%"><h4><?php echo strtoupper("Top Level") ?></h4></th></tr>
        <?php echo $rows ?>
    </tbody>
</table>
