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