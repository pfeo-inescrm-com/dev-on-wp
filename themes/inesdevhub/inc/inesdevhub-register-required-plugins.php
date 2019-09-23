<?php

function inesdevhub_register_required_plugins()
{
    $plugins = [
        [
            'name'      => 'Loco Translate',
            'slug'      => 'loco-translate',
            'required'      => true
        ],
        [
            'name'      => 'Ninja Forms',
            'slug'      => 'ninja-forms',
            'required'      => true
        ],
    ];

    $config = [
        'id'      => 'inesdevhub',
        'menu'      => 'tgmpa-install-plugins',
        'parent_slug'      => 'themes.php',
        'capability'      => 'edit_theme_options',
        'has_notices'      => true,
        'dismissable'      => true
    ];

    tgmpa($plugins, $config);
}
