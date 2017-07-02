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

defined('TYPO3_MODE') or die();

call_user_func(function ($extKey) {
    $extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$extKey]);
    foreach( [ 'footer', 'meta', 'main' ] as $navigationType ){
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
        // Add new page type:
        $GLOBALS['PAGES_TYPES'][$newDoktype] = [
            'type' => 'web',
            'allowedTables' => '*',
        ];

        // Allow backend users to drag and drop the new page type:
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
            'options.pageTree.doktypesToShowInNewPageDragArea := addToList(' . $newDoktype . ')'
        );

        // Add new page type as possible select item:
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
            'pages',
            'doktype',
            [
                'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xlf:pages.doktype.' . $navigationType . '_navigation',
                $newDoktype,
                'actions-menu'
            ],
            '199',
            'after'
        );

        // Add icon for new page type:
        \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
            $GLOBALS['TCA']['pages'],
            [
                'ctrl' => [
                    'typeicon_classes' => [
                        $newDoktype => 'actions-menu',
                    ],
                ],
                'types' => [
                    $newDoktype => [
                        'showitem' => $GLOBALS['TCA']['pages']['types'][254]['showitem']
                    ]
                ]
            ]
        );
    }
}, 'easy_navigation');
