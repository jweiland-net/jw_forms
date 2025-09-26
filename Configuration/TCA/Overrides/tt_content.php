<?php

/*
 * This file is part of the package jweiland/jw-forms.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::registerPlugin(
    'JwForms',
    'Forms',
    'JW Forms'
);

ExtensionManagementUtility::addStaticFile('jw_forms', 'Configuration/TypoScript', 'JW Forms');

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['jwforms_forms'] = 'layout,select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['jwforms_forms'] = 'pi_flexform';
ExtensionManagementUtility::addPiFlexFormValue(
    'jwforms_forms',
    'FILE:EXT:jw_forms/Configuration/FlexForms/JwForms.xml'
);
