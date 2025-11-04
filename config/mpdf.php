<?php

return [
    'mode' => 'utf-8',
    'format' => 'A4',
    'orientation' => 'P',
    'default_font_size' => 9,
    'default_font' => 'times',
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
    'margin_bottom' => 5,
    'margin_header' => 5,
    'margin_footer' => 5,
    'tempDir' => storage_path('app/mpdf/tmp'),

    // Font configuration for better character support
    'fontDir' => [
        storage_path('fonts/'),
    ],

    'fontdata' => [
        'times' => [
            'R' => 'times.ttf',
            'B' => 'timesbd.ttf',
            'I' => 'timesi.ttf',
            'BI' => 'timesbi.ttf',
        ],
        'arial' => [
            'R' => 'arial.ttf',
            'B' => 'arialbd.ttf',
            'I' => 'ariali.ttf',
            'BI' => 'arialbi.ttf',
        ],
    ],

    'autoScriptToLang' => true,
    'autoLangToFont' => true,

    // For better table handling
    'useSubstitutions' => true,
];
