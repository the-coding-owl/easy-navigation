<?php
/**
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


use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

/**
 * This class contains extension specific functionality
 *
 * @package TheCodingOwl\EasyNavigation
 */
class ExtensionUtility implements SingletonInterface
{
	/**
	 * Get the extension configuration alias extConf
	 *
	 * @return array
	 */
	public function getExtensionConfiguration(): array
	{
		if (version_compare(VersionNumberUtility::getCurrentTypo3Version(), '9.0.0', '>=')) {
			$extensionConfiguration = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['easy_navigation'];
		} else {
			$extensionConfiguration = unserialize(
				$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['easy_navigation'],
				['allowed_classes' => false]
			);
		}
		return $extensionConfiguration;
	}
}
