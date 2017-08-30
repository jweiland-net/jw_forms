<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'JWeiland.' . $_EXTKEY,
    'Forms',
    'JW Forms'
);

// load tt_content to $GLOBALS['TCA'] array and add flexform
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['jwforms_forms'] = 'layout,select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['jwforms_forms'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('jwforms_forms', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/JwForms.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'JW Forms');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jwforms_domain_model_form', 'EXT:jwforms/Resources/Private/Language/locallang_csh_tx_jwforms_domain_model_form.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jwforms_domain_model_form');

// add categories to JwForms table
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable($_EXTKEY, 'tx_jwforms_domain_model_form', 'categories');
