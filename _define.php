<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of dcLatestVersions, a plugin for Dotclear 2.
# 
# Copyright (c) 2009-2015 Jean-Christian Denis and contributors
# contact@jcdenis.fr http://jcd.lv
# 
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_RC_PATH')) {
	return null;
}
 
$this->registerModule(
	/* Name */
	"dcLatestVersions",
	/* Description*/
	"Show the latest available versions of Dotclear",
	/* Author */
	"Jean-Christian Denis, Pierre Van Glabeke",
	/* Version */
	'2020-08-11',
	/* Properies */
	array(
		'permissions' => 'usage,contentadmin',
		'type' => 'plugin',
		'dc_min' => '2.6',
		'support' => 'https://forum.dotclear.org/viewtopic.php?pid=344852',
		'details' => 'https://github.com/nanart59/dcLatestVersions'
	)
);
