<?php

/*
 * This file is part of the package jweiland/jw-forms.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\JwForms\Domain\Repository;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class FormRepository
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
     * Find all records starting with given letter
     */
    public function findByStartingLetter(string $letter, string $searchWord, array $settings = []): QueryResultInterface
    {
        $query = $this->createQuery();
        $constraints = [];

        if ($letter) {
            $orConstraintsForLetter = [];
            if ($letter === '0-9') {
                foreach (range(0, 9) as $number) {
                    $orConstraintsForLetter[] = $query->like('title', $number .'%');
                }
            } else {
                $orConstraintsForLetter[] = $query->like('title', $letter .'%');
            }

            $constraints[] = $query->logicalOr($orConstraintsForLetter);
        }

        if ($searchWord) {
            $constraints[] = $query->logicalOr([
                $query->like('title', '%' . $searchWord . '%'),
                $query->like('tags', '%' . $searchWord . '%'),
                $query->like('categories.title', '%' . $searchWord . '%'),
            ]);
        }

        if ($settings['categories']) {
            $orConstraintsForCategories = [];
            foreach (GeneralUtility::intExplode(',', $settings['categories']) as $category) {
                $orConstraintsForCategories[] = $query->in('categories.uid', $category);
            }
            $constraints[] = $query->logicalOr($orConstraintsForCategories);
        }

        if ($constraints === []) {
            return $query->execute();
        }

        return $query->matching($query->logicalAnd($constraints))->execute();
    }

    public function getQueryBuilderToFindAllEntries(int $category = 0): QueryBuilder
    {
        $table = 'tx_jwforms_domain_model_form';
        $query = $this->createQuery();
        $queryBuilder = $this->getConnectionPool()->getQueryBuilderForTable($table);
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        // Do not set any SELECT, ORDER BY, GROUP BY statement. It will be set by glossary2 API
        $queryBuilder
            ->from($table, 'f')
            ->where(
                $queryBuilder->expr()->in(
                    'pid',
                    $queryBuilder->createNamedParameter(
                        $query->getQuerySettings()->getStoragePageIds(),
                        Connection::PARAM_INT_ARRAY
                    )
                )
            );

        if ($category) {
            $queryBuilder
                ->leftJoin(
                    'c',
                    'sys_category_record_mm',
                    'mm',
                    (string)$queryBuilder->expr()->andX(
                        $queryBuilder->expr()->eq(
                            'mm.tablenames',
                            $queryBuilder->createNamedParameter($table, \PDO::PARAM_STR)
                        ),
                        $queryBuilder->expr()->eq(
                            'mm.fieldname',
                            $queryBuilder->createNamedParameter('categories', \PDO::PARAM_STR)
                        ),
                        $queryBuilder->expr()->eq(
                            'mm.uid_foreign',
                            $queryBuilder->quoteIdentifier('f.uid')
                        )
                    )
                )
                ->andWhere(
                    $queryBuilder->expr()->eq(
                        'mm.uid_local',
                        $queryBuilder->createNamedParameter($category, \PDO::PARAM_INT)
                    )
                );
        }

        return $queryBuilder;
    }

    /**
     * Get an array with available starting letters
     */
    public function getStartingLetters(string $categories): array
    {
        /** @var Query $query */
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

        [$availableLetters] = $query->statement(
            '
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
            BackendUtility::BEenableFields('tx_jwforms_domain_model_form') .
            'AND tx_jwforms_domain_model_form.deleted = 0' . '
        ',
            $placeHolders
        )->execute(true);

        return $availableLetters;
    }

    protected function getConnectionPool(): ConnectionPool
    {
        return GeneralUtility::makeInstance(ConnectionPool::class);
    }
}
