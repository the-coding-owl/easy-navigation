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
    $extConf = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
    )->get('easy_navigation');
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
        // Add new page type:
        $GLOBALS['PAGES_TYPES'][$newDoktype] = [
            'type' => 'web',
            'allowedTables' => '*',
        ];

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
