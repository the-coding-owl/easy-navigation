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
