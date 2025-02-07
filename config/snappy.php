
<?php

return [
    'pdf' => [
        'enabled' => true,
        'binary'  => '"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe"',
        'options' => [
            'enable-local-file-access' => true, // Allow local assets
            'no-outline' => true,
            'page-size' => 'A4',
            'margin-top' => 10,
            'margin-right' => 10,
            'margin-bottom' => 10,
            'margin-left' => 10,
            'dpi' => 300,

            "footer-left" => "Page [page] / [topage]",
            "footer-right" => "As of " . now()->translatedFormat('F j, Y g:i A'),
            'disable-smart-shrinking' => true,
        ],
    ],
];
