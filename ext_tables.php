<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'JwForms',
    'Forms',
    'JW Forms'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('jw_forms', 'Configuration/TypoScript', 'JW Forms');

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['jwforms_forms'] = 'layout,select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['jwforms_forms'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('jwforms_forms', 'FILE:EXT:jw_forms/Configuration/FlexForms/JwForms.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jwforms_domain_model_form');
