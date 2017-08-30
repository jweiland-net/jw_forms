<?php
namespace JWeiland\JwForms;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Update class for the extension manager.
 */
class ext_update
{
    /**
     * Table fields to migrate
     *
     * @var array
     */
    protected $tables = array(
        'tx_jwforms_domain_model_form' => array(
            'file' => array(
                'sourcePath' => 'fileadmin/user_upload/formulare/',
                // Relative to fileadmin
                'targetPath' => 'user_upload/formulare/',
                'titleTexts' => 'title',
                'links' => 'url_to_file',
                'alternativeTexts' => 'title'
            )
        ),
    );
    
    /**
     * @var \TYPO3\CMS\Core\Resource\ResourceStorage
     */
    protected $storage;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->database = $GLOBALS['TYPO3_DB'];
        /** @var $storageRepository \TYPO3\CMS\Core\Resource\StorageRepository */
        $storageRepository = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
        $storages = $storageRepository->findAll();
        $this->storage = $storages[0];
    }
    
    /**
     * Main update function called by the extension manager.
     *
     * @return string
     */
    public function main()
    {
        $this->performUpdate();
        return 'Alles bingo';
    }

    /**
     * Called by the extension manager to determine if the update menu entry
     * should by showed.
     *
     * @return bool
     */
    public function access()
    {
        return true;
    }
    
    /**
     * Get records from table where the field to migrate is not empty (NOT NULL and != '')
     * and also not numeric (which means that it is migrated)
     *
     * @param string $table
     * @param string $fieldToMigrate
     * @param array $relationFields
     *
     * @return array
     *
     * @throws \RuntimeException
     */
    protected function getRecordsFromTable($table, $fieldToMigrate, $relationFields) {
        $fields = implode(',', array_merge($relationFields, array('uid', 'pid')));
        $deletedCheck = isset($GLOBALS['TCA'][$table]['ctrl']['delete'])
            ? ' AND ' . $GLOBALS['TCA'][$table]['ctrl']['delete'] . '=0'
            : '';
        $where = $fieldToMigrate . ' IS NOT NULL'
            . ' AND ' . $fieldToMigrate . ' != \'\''
            . ' AND CAST(CAST(' . $fieldToMigrate . ' AS DECIMAL) AS CHAR) <> CAST(' . $fieldToMigrate . ' AS CHAR)'
            . $deletedCheck;
        $result = $this->database->exec_SELECTgetRows($fields, $table, $where, '', 'uid');
        if ($result === NULL) {
            throw new \RuntimeException('Database query failed. Error was: ' . $this->database->sql_error());
        }
        return $result;
    }
    
    /**
     * Performs the database update.
     *
     * @return boolean TRUE on success, FALSE on error
     */
    public function performUpdate() {
        try {
            foreach ($this->tables as $table => $tableConfiguration) {
                // find all additional fields we should get from the database
                foreach ($tableConfiguration as $fieldToMigrate => $fieldConfiguration) {
                    $fieldsToGet = array($fieldToMigrate);
                    if (isset($fieldConfiguration['titleTexts'])) {
                        $fieldsToGet[] = $fieldConfiguration['titleTexts'];
                    }
                    if (isset($fieldConfiguration['alternativeTexts'])) {
                        $fieldsToGet[] = $fieldConfiguration['alternativeTexts'];
                    }
                    if (isset($fieldConfiguration['captions'])) {
                        $fieldsToGet[] = $fieldConfiguration['captions'];
                    }
                    if (isset($fieldConfiguration['links'])) {
                        $fieldsToGet[] = $fieldConfiguration['links'];
                    }
                    
                    $records = $this->getRecordsFromTable($table, $fieldToMigrate, $fieldsToGet);
                    foreach ($records as $record) {
                        $this->migrateField($table, $record, $fieldToMigrate, $fieldConfiguration, $customMessages);
                    }
                }
            }
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
        return 'Alles gut gegangen';
    }
    
    /**
     * Migrates a single field.
     *
     * @param string $table
     * @param array $row
     * @param string $fieldname
     * @param array $fieldConfiguration
     * @param string $customMessages
     *
     * @return void
     *
     * @throws \Exception
     */
    protected function migrateField($table, $row, $fieldname, $fieldConfiguration, &$customMessages) {
        $titleTextContents = array();
        $alternativeTextContents = array();
        $captionContents = array();
        $linkContents = array();
        
        $fieldItems = GeneralUtility::trimExplode(',', $row[$fieldname], TRUE);
        if (empty($fieldItems) || is_numeric($row[$fieldname])) {
            return array();
        }
        if (isset($fieldConfiguration['titleTexts'])) {
            $titleTextField = $fieldConfiguration['titleTexts'];
            $titleTextContents = explode(LF, $row[$titleTextField]);
        }
        
        if (isset($fieldConfiguration['alternativeTexts'])) {
            $alternativeTextField = $fieldConfiguration['alternativeTexts'];
            $alternativeTextContents = explode(LF, $row[$alternativeTextField]);
        }
        if (isset($fieldConfiguration['captions'])) {
            $captionField = $fieldConfiguration['captions'];
            $captionContents = explode(LF, $row[$captionField]);
        }
        if (isset($fieldConfiguration['links'])) {
            $linkField = $fieldConfiguration['links'];
            $linkContents = explode(LF, $row[$linkField]);
        }
        $fileadminDirectory = rtrim($GLOBALS['TYPO3_CONF_VARS']['BE']['fileadminDir'], '/') . '/';
        $i = 0;
        
        if (!PATH_site) {
            throw new \Exception('PATH_site was undefined.');
        }
        
        $storageUid = (int)$this->storage->getUid();
        
        foreach ($fieldItems as $item) {
            $fileUid = NULL;
            $sourcePath = PATH_site . $fieldConfiguration['sourcePath'] . $item;
            $targetDirectory = PATH_site . $fileadminDirectory . $fieldConfiguration['targetPath'];
            $targetPath = $targetDirectory . basename($item);
            
            // maybe the file was already moved, so check if the original file still exists
            if (file_exists($sourcePath)) {
                if (!is_dir($targetDirectory)) {
                    GeneralUtility::mkdir_deep($targetDirectory);
                }
                
                // see if the file already exists in the storage
                $fileSha1 = sha1_file($sourcePath);
                
                $existingFileRecord = $this->database->exec_SELECTgetSingleRow(
                    'uid',
                    'sys_file',
                    'sha1=' . $this->database->fullQuoteStr($fileSha1, 'sys_file') . ' AND storage=' . $storageUid
                );
                // the file exists, the file does not have to be moved again
                if (is_array($existingFileRecord)) {
                    $fileUid = $existingFileRecord['uid'];
                } else {
                    // just move the file (no duplicate)
                    rename($sourcePath, $targetPath);
                }
            }
            
            if ($fileUid === NULL) {
                // get the File object if it hasn't been fetched before
                try {
                    // if the source file does not exist, we should just continue, but leave a message in the docs;
                    // ideally, the user would be informed after the update as well.
                    /** @var File $file */
                    $file = $this->storage->getFile($fieldConfiguration['targetPath'] . $item);
                    $fileUid = $file->getUid();
                    
                } catch (\InvalidArgumentException $e) {
                    
                    $format = 'File \'%s\' does not exist. Referencing field: %s.%d.%s. The reference was not migrated.';
                    $message = sprintf($format, $fieldConfiguration['sourcePath'] . $item, $table, $row['uid'], $fieldname);
                    $customMessages .= PHP_EOL . $message;
                    
                    continue;
                }
            }
            
            if ($fileUid > 0) {
                $fields = array(
                    // TODO add sorting/sorting_foreign
                    'fieldname' => $fieldname,
                    'table_local' => 'sys_file',
                    // the sys_file_reference record should always placed on the same page
                    // as the record to link to, see issue #46497
                    'pid' => ($table === 'pages' ? $row['uid'] : $row['pid']),
                    'uid_foreign' => $row['uid'],
                    'uid_local' => $fileUid,
                    'tablenames' => $table,
                    'crdate' => time(),
                    'tstamp' => time(),
                    'sorting' => ($i + 256),
                    'sorting_foreign' => $i,
                );
                if (isset($titleTextField)) {
                    $fields['title'] = trim($titleTextContents[$i]);
                }
                if (isset($alternativeTextField)) {
                    $fields['alternative'] = trim($alternativeTextContents[$i]);
                }
                if (isset($captionField)) {
                    $fields['description'] = trim($captionContents[$i]);
                }
                if (isset($linkField)) {
                    $fields['link'] = trim($linkContents[$i]);
                }
                $this->database->exec_INSERTquery('sys_file_reference', $fields);
                ++$i;
            }
        }
        
        $this->database->exec_UPDATEquery($table, 'uid=' . $row['uid'], array($fieldname => $i));
    }
}
