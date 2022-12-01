<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'JW Forms',
    'description' => 'With this extension you can provide a list of downloadable files in FE.',
    'category' => 'plugin',
    'author' => 'Stefan Froemken',
    'author_email' => 'sfroemken@jweiland.net',
    'author_company' => 'jweiland.net',
    'state' => 'stable',
    'version' => '3.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.32-11.5.99',
            'glossary2' => '5.0.0-0.0.0',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
