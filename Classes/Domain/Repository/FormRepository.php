<?php
namespace JWeiland\JwForms\Domain\Repository;

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
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class FormRepository
 *
 * @package JWeiland\JwForms\Domain\Repository
 */
class FormRepository extends Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = [
        'title' => QueryInterface::ORDER_ASCENDING
    ];

    /**
     * find all records starting with given letter
     *
     * @param string $letter
     * @param string $searchWord
     * @param array $settings
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByStartingLetter($letter, $searchWord, array $settings = []) {
        $query = $this->createQuery();
        $placeHolders = [
            'tx_jwforms_domain_model_form',
            implode(',', $query->getQuerySettings()->getStoragePageIds())
        ];

        // add query for letter
        if ($letter) {
            if ($letter == '0-9') {
                $orQueryForLetter = array_fill(0, 10, 'tx_jwforms_domain_model_form.title LIKE ?');
                $range = range(0, 9, 1);
                array_walk($range, function(&$item) {
                    $item = $item . '%';
                });
                $placeHolders = array_merge($placeHolders, $range);
            } else {
                $orQueryForLetter = ['tx_jwforms_domain_model_form.title LIKE ?'];
                $placeHolders[] = $letter . '%';
            }
            $additionalOrClauseForLetter = ' AND (' . implode(' OR ', $orQueryForLetter) . ') ';
        } else {
            $additionalOrClauseForLetter = '';
        }

        // add query for searchWord
        if ($searchWord) {
            $orQueryForSearchWord = [];
            $orQueryForSearchWord[] = 'tx_jwforms_domain_model_form.title LIKE ?';
            $orQueryForSearchWord[] = 'tx_jwforms_domain_model_form.tags LIKE ?';
            $orQueryForSearchWord[] = 'sys_category.title LIKE ?';
            $additionalOrClauseForSearchWord = ' AND (' . implode(' OR ', $orQueryForSearchWord) . ') ';
            $placeHolders[] = '%' . $searchWord . '%';
            $placeHolders[] = '%' . $searchWord . '%';
            $placeHolders[] = '%' . $searchWord . '%';
        } else {
            $additionalOrClauseForSearchWord = '';
        }

        // add query for categories
        if ($settings['categories']) {
            // create OR-Query for categories
            $orQueryForCategories = [];
            foreach (GeneralUtility::intExplode(',', $settings['categories']) as $category) {
                $orQueryForCategories[] = 'sys_category_record_mm.uid_local IN (?)';
                $placeHolders[] = (integer) $category;
            }
            $additionalOrClauseForCategories = ' AND (' . implode(' OR ', $orQueryForCategories) . ') ';
        } else {
            $additionalOrClauseForCategories = '';
        }

        return $query->statement('
            SELECT DISTINCT tx_jwforms_domain_model_form.*
            FROM tx_jwforms_domain_model_form
            LEFT JOIN sys_category_record_mm
            ON tx_jwforms_domain_model_form.uid=sys_category_record_mm.uid_foreign
            LEFT JOIN sys_category
            ON sys_category_record_mm.uid_local=sys_category.uid
            WHERE sys_category_record_mm.tablenames = ?
            AND tx_jwforms_domain_model_form.pid IN (?)' .
            $additionalOrClauseForLetter .
            $additionalOrClauseForSearchWord .
            $additionalOrClauseForCategories .
            BackendUtility::BEenableFields('tx_jwforms_domain_model_form') .
            BackendUtility::deleteClause('tx_jwforms_domain_model_form') . '
            ORDER BY title ASC',
            $placeHolders
        )->execute();
    }

    /**
     * get an array with available starting letters
     *
     * @param string $categories
     *
     * @return array
     */
    public function getStartingLetters($categories) {
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();

        $placeHolders = [];
        $placeHolders[] = 'tx_jwforms_domain_model_form';
        $placeHolders[] = 'categories';
        $placeHolders[] = implode(',', $query->getQuerySettings()->getStoragePageIds());

        $additionalWhereQuery = '';

        // add query for categories
        if (!empty($categories)) {
            // create OR-Query for categories
            $orQueryForCategories = [];
            foreach (GeneralUtility::intExplode(',', $categories) as $category) {
                $orQueryForCategories[] = 'sys_category_record_mm.uid_local IN (?)';
                $placeHolders[] = (int)$category;
            }
            $additionalWhereQuery .= ' AND (' . implode(' OR ', $orQueryForCategories) . ') ';
        }

        list($availableLetters) = $query->statement('
            SELECT GROUP_CONCAT(DISTINCT UPPER(LEFT(tx_jwforms_domain_model_form.title, 1))) as letters
            FROM tx_jwforms_domain_model_form
            LEFT JOIN sys_category_record_mm
            ON tx_jwforms_domain_model_form.uid=sys_category_record_mm.uid_foreign
            LEFT JOIN sys_category
            ON sys_category_record_mm.uid_local=sys_category.uid
            WHERE sys_category_record_mm.tablenames = ?
            AND sys_category_record_mm.fieldname = ?
            AND tx_jwforms_domain_model_form.pid IN (?)' .
            $additionalWhereQuery .
            BackendUtility::BEenableFields('tx_jwforms_domain_model_form')	.
            BackendUtility::deleteClause('tx_jwforms_domain_model_form')	. '
        ', $placeHolders
        )->execute(true);

        return $availableLetters;
    }
}
