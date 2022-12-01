<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'JwForms',
    'Forms',
    [
        \JWeiland\JwForms\Controller\FormController::class => 'list, search, show',
    ],
    [
        \JWeiland\JwForms\Controller\FormController::class => 'search',
    ]
);
