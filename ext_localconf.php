<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'JWeiland.' . $_EXTKEY,
    'Forms',
    array(
        'Form' => 'list, search, show',
    ),
    // non-cacheable actions
    array(
        'Form' => 'search',
    )
);
