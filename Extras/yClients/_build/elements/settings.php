<?php

return [
    'api_url' => [
        'xtype' => 'textfield',
        'value' => 'https://api.yclients.com/api/v1/',
        'area' => 'area_yclients_api',
    ],
    'api_login' => [
        'xtype' => 'textfield',
        'value' => '', // bustep.ru@yandex.ru
        'area' => 'area_yclients_api',
    ],
    'api_password' => [
        'xtype' => 'textfield',
        'value' => '', // r3khwh
        'area' => 'area_yclients_api',
    ],

    'api_token' => [
        'xtype' => 'textfield',
        'value' => '', // fbd58893be6f5cc6b645b352cc1d8be1
        'area' => 'area_yclients_api',
    ],
    'company_id' => [
        'xtype' => 'textfield',
        'value' => '', // 101186
        'area' => 'area_yclients_api',
    ],
    'lifetime' => [
        'xtype' => 'textfield',
        'value' => '10000',
        'area' => 'yclients_main',
    ],
    'frontend_js' => [
        'xtype' => 'textfield',
        'value' => '[[+jsUrl]]web/default.js',
        'area' => 'yclients_main',
    ],
    'frontend_css' => [
        'xtype' => 'textfield',
        'value' => '[[+cssUrl]]web/default.css',
        'area' => 'yclients_main',
    ],

    'frontend_js_datepicker' => [
        'xtype' => 'textfield',
        'value' => '[[+jsUrl]]web/lib/bootstrap-datepicker.min.js',
        'area' => 'yclients_main',
    ],

    'frontend_js_datepicker_ru' => [
        'xtype' => 'textfield',
        'value' => '[[+jsUrl]]web/lib/bootstrap-datepicker.ru.min.js',
        'area' => 'yclients_main',
    ],
    'frontend_css_datepicker' => [
        'xtype' => 'textfield',
        'value' => '[[+cssUrl]]web/lib/bootstrap-datepicker.css',
        'area' => 'yclients_main',
    ],
];