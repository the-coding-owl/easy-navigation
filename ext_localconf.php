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
	$extensionUtility = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TheCodingOwl\EasyNavigation\ExtensionUtility::class);
	$extConf = $extensionUtility->getExtensionConfiguration();
	$navigationConstants = 'plugin.tx_easynavigation.settings{' . PHP_EOL;
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
		// add the doktypes to the TypoScript constant section of the template to be able to use them in the TypoScript templates
		$navigationConstants .= $navigationType . '.doktype=' . $newDoktype . PHP_EOL;

		// Allow backend users to drag and drop the new page type:
		\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
			'options.pageTree.doktypesToShowInNewPageDragArea := addToList(' . $newDoktype . ')'
		);
	}
	$navigationConstants .= '}' . PHP_EOL;
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants($navigationConstants);
}, 'easy_navigation');
