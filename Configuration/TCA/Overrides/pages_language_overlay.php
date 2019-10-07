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

call_user_func(function ($extKey) {
	if (version_compare(\TYPO3\CMS\Core\Utility\VersionNumberUtility::getCurrentTypo3Version(), '9.0.0', '<')) {
		$extensionUtility = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TheCodingOwl\EasyNavigation\ExtensionUtility::class);
		$extConf = $extensionUtility->getExtensionConfiguration();
		foreach (['footer', 'meta', 'main'] as $navigationType) {
			$newDoktype = $extConf[$navigationType . 'NavigationDoktype'];
			if (empty($newDoktype)) {
				if ($navigationType === 'main') {
					$newDoktype = 124;
				} elseif ($navigationType === 'meta') {
					$newDoktype = 125;
				} elseif ($navigationType === 'footer') {
					$newDoktype = 126;
				}
			}

			// Add new page type as possible select item:
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
				'pages_language_overlay',
				'doktype',
				[
					'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xlf:pages.doktype.' . $navigationType . '_navigation',
					$newDoktype,
					'actions-menu'
				],
				'199',
				'after'
			);
		}
	}
}, 'easy_navigation');
