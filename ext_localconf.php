<?php

if (!defined('TYPO3')) {
    die ('Access denied.');
}

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use JWeiland\JwForms\Controller\FormController;

ExtensionUtility::configurePlugin(
    'JwForms',
    'Forms',
    [
        FormController::class => 'list, search, show',
    ],
    [
        FormController::class => 'search',
    ]
);
