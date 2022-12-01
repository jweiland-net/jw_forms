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
    'clearCacheOnLoad' => 0,
    'version' => '2.0.2',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.17-10.4.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
