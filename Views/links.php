<h2>EmonCMS Debugging</h2>

<h4 class="text-muted">Values:</h4>
<ul class="nav nav-pills flex-column flex-sm-row pager text-larger">
<?php foreach($links as $link=>$label) {
    printf('<li><a class="flex-sm-fill text-sm-center nav-link border" href="%s" style="padding:.5em .8em!important">%s</a></li>', $link, $label);
} ?>
</ul>
<div class="row">
    <div class="span-6">
        <h4>Settings Files:</h4>
        <ul class="nav nav-list">
            <li class="nav-header">Defaults (<?php echo count_values($arrays['_default']) ?>)</li>
            <li> <?php echo fileAvailable('default-settings.ini') ?></li>
            <li class="nav-header">User overrides (<?php echo count_values($arrays['_settings']) . '/' . count_values($arrays['_default']) ?>)</li>
            <li><?php echo fileAvailable('settings.php') ?></li>
            <li><?php echo fileAvailable('settings.ini') ?></li>
            <li class="nav-header">Environment (<?php echo count_values($arrays['_env_found']) .'/'. count_values($arrays['_env']) ?>)</li>
            <li><?php echo fileAvailable('settings.env.ini') ?></li>
        </ul>
    </div>
    <div class="span-6">
        <h4>Default Settings:</h4>
        <ul class="nav nav-list">
            <?php foreach ($arrays['_default'] as $key=>$value): ?>
                <?php if (is_iterable($value)) {
                    echo "<li class=\"nav-header\">$key</li>";
                    echo "<ul>";
                    foreach ($value as $k=>$v){
                        if (is_iterable($v)) {
                            echo "<li class=\"nav-header\">$k</li>";
                            echo "<ul>";
                            foreach ($v as $k2=>$v2) {
                                printf ("<li>%s</li>", $k2);
                            }
                            echo "</ul>";
                            echo "</li>";
                        } else {
                            printf ("<li>%s</li>", $k);
                            
                        }
                    }
                    echo "</ul>";
                } else {
                    echo "<li>$key</li>";
                } ?>
            <?php endforeach; ?>
        </ul>
    </div>
</div>




<?php
function fileAvailable($filename) {
    global $arrays;
    $icon = file_exists($filename) ? '<i class="icon-ok"></i>': '<i class="icon-remove"></i>';
    return $filename . ' ' . $icon;
}

function count_values($list) {
    $values = array();
    if (is_array($list)) {
        // function adds to the $values array
        array_values_recursive($values, $list);
    }
    return count($values);
}

function array_values_recursive(&$values, $list) {
    // loop through array until value is another array
    foreach ($list as $value) {
        if (is_array($value)) {
            // add to the values list again (recursive)
            array_values_recursive($values, $value);
        } else {
            // add a value to the list
            $values[] = $value;
        }
    }
}