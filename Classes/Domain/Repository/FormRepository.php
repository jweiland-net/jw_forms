<?php

/*
 * This file is part of the package jweiland/jw-forms.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\JwForms\Domain\Repository;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
                    $orConstraintsForLetter[] = $query->like('title', $number . '%');
                }
            } else {
                $orConstraintsForLetter[] = $query->like('title', $letter . '%');
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
                $orConstraintsForCategories[] = $query->equals('categories.uid', $category);
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

    protected function getConnectionPool(): ConnectionPool
    {
        return GeneralUtility::makeInstance(ConnectionPool::class);
    }
}
