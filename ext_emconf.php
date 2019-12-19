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

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Easy Navigation',
	'description' => 'An extension that makes it easy to flag a TYPO3 page record as an entry point of a menu',
	'category' => 'plugin',
	'author' => 'Kevin Ditscheid',
	'author_email' => 'kevinditscheid@gmail.com',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'version' => '1.1.2',
	'constraints' => array(
		'depends' => array(
			'typo3' => '8.7.0-10.2.99'
		),
		'conflicts' => array(
		),
		'suggests' => array(
		)
	)
);
