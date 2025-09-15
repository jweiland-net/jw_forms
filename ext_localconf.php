<?php

/*
 * This file is part of the package jweiland/jw-forms.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

if (!defined('TYPO3')) {
    die('Access denied.');
}

use JWeiland\JwForms\Controller\FormController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::configurePlugin(
    'JwForms',
    'Forms',
    [
        FormController::class => 'list, search, show',
    ],
    [
        FormController::class => 'search',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);
