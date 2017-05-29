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

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}
call_user_func(function($extKey){
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($extKey, 'Configuration/TypoScript', 'Navigation Template');
    // add the doktypes to the TypoScript constant section of the template to be able to use them in the TypoScript templates
    $navigationConstants = 'plugin.tx_easynavigation.settings{';
    $extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$extKey]);
    foreach( [ 'main', 'meta', 'footer' ] as $navigationType ){
        $newDoktype = $extConf[$navigationType . 'NavigationDoktype'];
        if( empty($newDoktype) ){
            if( $navigationType === 'main' ){
                $newDoktype = 124;
            } elseif( $navigationType === 'meta' ) {
                $newDoktype = 125;
            } elseif( $navigationType === 'footer' ) {
                $newDoktype = 126;
            }
        }
        $navigationConstants .= $navigationType . '.doktype=' . $newDoktype;
    }
    $navigationConstants .= '}';
    TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants($navigationConstants);
}, 'easy_navigation');
