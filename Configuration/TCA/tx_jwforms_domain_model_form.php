<?php

/*
 * This file is part of the package jweiland/jw-forms.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Resource\File;

return [
    'ctrl' => [
        'title' => 'LLL:EXT:jw_forms/Resources/Private/Language/locallang_db.xlf:tx_jwforms_domain_model_form',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'versioningWS' => true,
        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'iconfile' => 'EXT:jw_forms/Resources/Public/Icons/tx_jwforms_domain_model_form.gif',
        'searchFields' => 'title,tags',
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => '--palette--;;language, --palette--;;titleHidden,
            file, url_to_file, tags,
            --div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.category,
            categories,
            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.access,
            --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.palettes.access;access',
        ],
    ],
    'palettes' => [
        'language' => ['showitem' => 'sys_language_uid, l10n_parent'],
        'titleHidden' => ['showitem' => 'title, hidden'],
        'access' => [
            'showitem' => 'starttime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel,endtime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel',
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_source' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'group',
                'allowed' => 'tx_jwforms_domain_model_form',
                'size' => 1,
                'maxitems' => 1,
                'minitems' => 0,
                'default' => 0,
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => '',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
        ],
        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jw_forms/Resources/Private/Language/locallang_db.xlf:tx_jwforms_domain_model_form.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'file' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jw_forms/Resources/Private/Language/locallang_db.xlf:tx_jwforms_domain_model_form.file',
            'config' => [
                'type' => 'file',
            ],
        ],
        'url_to_file' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jw_forms/Resources/Private/Language/locallang_db.xlf:tx_jwforms_domain_model_form.url_to_file',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'tags' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jw_forms/Resources/Private/Language/locallang_db.xlf:tx_jwforms_domain_model_form.tags',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'categories' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jw_forms/Resources/Private/Language/locallang_db.xlf:tx_jwforms_domain_model_form.categories',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
                'treeConfig' => [
                    'parentField' => 'parent',
                    'appearance' => [
                        'showHeader' => true,
                        'expandAll' => true,
                        'maxLevels' => 99,
                    ],
                ],
                'MM' => 'sys_category_record_mm',
                'MM_match_fields' => [
                    'fieldname' => 'categories',
                    'tablenames' => 'tx_jwforms_domain_model_form',
                ],
                'MM_opposite_field' => 'items',
                'foreign_table' => 'sys_category',
                'foreign_table_where' => ' AND (sys_category.sys_language_uid = 0 OR sys_category.l10n_parent = 0) ORDER BY sys_category.sorting',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 99,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
    ],
];
