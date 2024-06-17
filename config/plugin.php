<?php

/**
 * @var $config DT\Plugin\League\Config\Configuration
 */
$config->merge( [
    'plugin' => [
        'text_domain' => 'dt-plugin',
        'nonce_name' => 'dt-plugin',
        'dt_version' => 1.19,
        'paths' => [
            'src' => 'src',
            'resources' => 'resources',
            'routes' => 'routes',
            'views' => 'resources/views',
        ]
    ]
]);
