<?php
$menu['sidebar']['emoncms'][] = array(
    'text' => _("Debug"),
    'path' => 'debug',
    'active' => 'debug',
    'icon' => 'bug_report'
);

$menu['includes']['icons'][] = <<<ICONS
    <symbol id="icon-bug_report" viewBox="0 0 32 32">
        <!--<title>bug_report</title>-->
        <path d="M18.688 16v-2.688h-5.375v2.688h5.375zM18.688 21.313v-2.625h-5.375v2.625h5.375zM26.688 10.688v2.625h-2.813c0.063 0.438 0.125 0.938 0.125 1.375v1.313h2.688v2.688h-2.688v1.313c0 0.438-0.063 0.875-0.125 1.313h2.813v2.688h-3.75c-1.375 2.375-4 4-6.938 4s-5.563-1.625-6.938-4h-3.75v-2.688h2.813c-0.063-0.438-0.125-0.875-0.125-1.313v-1.313h-2.688v-2.688h2.688v-1.313c0-0.438 0.063-0.938 0.125-1.375h-2.813v-2.625h3.75c0.625-1.063 1.438-1.938 2.438-2.625l-2.188-2.188 1.875-1.875 2.938 2.875c0.625-0.125 1.25-0.188 1.875-0.188s1.25 0.063 1.875 0.188l2.938-2.875 1.875 1.875-2.188 2.188c1 0.688 1.813 1.563 2.438 2.625h3.75z"></path>
    </symbol>
ICONS;