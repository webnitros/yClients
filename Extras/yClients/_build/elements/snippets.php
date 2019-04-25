<?php

return [
    'yClientsCalendar' => [
        'file' => 'yclientscalendar',
        'description' => 'yClients snippet to list items',
        'properties' => [
            'tpl' => [
                'type' => 'textfield',
                'value' => 'tpl.yClients.calendar',
            ],
            'tplModel' => [
                'type' => 'textfield',
                'value' => 'tpl.yClients.modal',
            ]
        ],
    ],
    'yClientsSchedule' => [
        'file' => 'yclientsschedule',
        'description' => 'yClients snippet to list schedules',
        'properties' => [
            'day' => [
                'type' => 'textfield',
                'value' => 'today',
            ],
            'company_id' => [
                'type' => 'textfield',
                'value' => '',
            ],
            'from' => [
                'type' => 'textfield',
                'value' => '',
            ],
            'till' => [
                'type' => 'textfield',
                'value' => '',
            ],
            'tpl' => [
                'type' => 'textfield',
                'value' => 'tpl.yClients.event.row',
            ],
            'tplOuter' => [
                'type' => 'textfield',
                'value' => 'tpl.yClients.event.outer',
            ],
            'tplEmpty' => [
                'type' => 'textfield',
                'value' => 'tpl.yClients.event.empty',
            ],
            'toPlaceholder' => [
                'type' => 'textfield',
                'value' => '',
            ],
            'toSeparatePlaceholders' => [
                'type' => 'textfield',
                'value' => '',
            ],
            'wrapIfEmpty' => array(
                'type' => 'combo-boolean',
                'value' => true,
            ),
            'outputSeparator' => array(
                'type' => 'textfield',
                'value' => "\n",
            ),
        ],
    ],
    'yClientsTariffs' => [
        'file' => 'yclientstariffs',
        'description' => 'yClients snippet to list tariffs',
        'properties' => [
            'company_id' => [
                'type' => 'textfield',
                'value' => '',
            ],
            'tpl' => [
                'type' => 'textfield',
                'value' => 'tpl.yclients.tariffs.row',
            ],
            'tplOuter' => [
                'type' => 'textfield',
                'value' => 'tpl.yclients.tariffs.outer',
            ],
            'toPlaceholder' => [
                'type' => 'textfield',
                'value' => '',
            ],
            'toSeparatePlaceholders' => [
                'type' => 'textfield',
                'value' => '',
            ],
            'wrapIfEmpty' => array(
                'type' => 'combo-boolean',
                'value' => true,
            ),
            'outputSeparator' => array(
                'type' => 'textfield',
                'value' => "\n",
            ),
        ],
    ],
    'yClientsStaffs' => [
        'file' => 'yclientsstaffs',
        'description' => 'yClients snippet to list staffs',
        'properties' => [
            'tpl' => [
                'type' => 'textfield',
                'value' => 'tpl.yclients.staffs.row',
            ],
            'tplOuter' => [
                'type' => 'textfield',
                'value' => 'tpl.yclients.staffs.outer',
            ],
            'toPlaceholder' => [
                'type' => 'textfield',
                'value' => '',
            ],
            'toSeparatePlaceholders' => [
                'type' => 'textfield',
                'value' => '',
            ],
            'wrapIfEmpty' => array(
                'type' => 'combo-boolean',
                'value' => true,
            ),
            'outputSeparator' => array(
                'type' => 'textfield',
                'value' => "\n",
            ),
        ],
    ],
];