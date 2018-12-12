<?php

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

namespace TheCodingOwl\EasyNavigation;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * This class carries all userfunctions that can not be associated with a certain part of the system
 *
 * @author Kevin Ditscheid <kevinditscheid@gmail.com>
 */
class UserFunc
{
	/**
	 * Find a navigation entry in the rootline of the current page
	 *
	 * @param string $content A prerendered content, not used here
	 * @param array $configuration The array of TypoScript configuration for the UserFunction
	 *
	 * @return string
	 */
	public function findNavigationEntry(string $content, array $configuration): string
	{
		$navigationType = $configuration['navigationType'];
		$extensionUtility = GeneralUtility::makeInstance(ExtensionUtility::class);
		$extConf = $extensionUtility->getExtensionConfiguration();
		$doktype = $extConf[$navigationType . 'NavigationDoktype'];
		if (empty($doktype)) {
			if ($navigationType === 'main') {
				$doktype = 124;
			} elseif ($navigationType === 'meta') {
				$doktype = 125;
			} elseif ($navigationType === 'footer') {
				$doktype = 126;
			}
		}
		$queryBuilder = $this->getPagesQueryBuilder();
		$queryBuilder->select('uid');
		$queryBuilder->from('pages');
		$queryBuilder->where('doktype=:doktype');
		$queryBuilder->createNamedParameter($doktype, \PDO::PARAM_INT, ':doktype');
		$rootLine = $this->getTypoScriptFrontendController()->rootLine;
		$placeholders = [];
		if (is_array($rootLine)) {
			array_walk($rootLine, function ($row) use ($queryBuilder, &$placeholders) {
				$placeholders[] = $queryBuilder->createNamedParameter($row['uid'], \PDO::PARAM_INT);
			});
		}
		if (count($placeholders) > 0) {
			$queryBuilder->andWhere('pid IN(' . implode(',', $placeholders) . ')');
		}
		$row = $queryBuilder->execute()->fetch();
		return $row['uid'] ?? '';
	}

	/**
	 * Get the TypoScriptFrontendController aka TSFE
	 * @return TypoScriptFrontendController
	 */
	protected function getTypoScriptFrontendController(): TypoScriptFrontendController
	{
		return $GLOBALS['TSFE'];
	}

	/**
	 * Get the query builder for the pages-table
	 *
	 * @return QueryBuilder
	 */
	protected function getPagesQueryBuilder(): QueryBuilder
	{
		$db = GeneralUtility::makeInstance(ConnectionPool::class);
		return $db->getQueryBuilderForTable('pages');
	}
}
