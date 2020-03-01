<?php
/*
 * the-coding-owl/easy-navigation
 * Copyright (C) 2019 Kevin Ditscheid <kevin@the-coding-owl.de>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace TheCodingOwl\EasyNavigation;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

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
        $extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('easy_navigation');
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
