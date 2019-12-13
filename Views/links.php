<h2>EmonCMS Debugging</h2>

<h4 class="text-muted">System Variables:</h4>
<nav class="nav nav-pills flex-column flex-sm-row">
<?php foreach($links as $link=>$label): ?>
<a class="flex-sm-fill text-sm-center nav-link border" href="<?php echo $path . _($link) ?>"><?php echo _($label) ?></a>
<?php endforeach; ?>
</nav>
