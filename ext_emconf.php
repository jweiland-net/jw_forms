<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'JW Forms',
    'description' => 'This extension gives you the possibility to display title and file ' .
        'of forms (PDF, ...) by starting letter and search forms of this list.',
    'category' => 'plugin',
    'author' => 'Stefan Froemken',
    'author_email' => 'sfroemken@jweiland.net',
    'author_company' => 'jweiland.net',
    'state' => 'stable',
    'version' => '3.0.0',
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
