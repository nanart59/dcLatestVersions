<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of dcLatestVersions, a plugin for Dotclear 2.
# 
# Copyright (c) 2009-2015 Jean-Christian Denis and contributors
# Copyright (c) 2020 Nan'Art and contributors
# 
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_CONTEXT_ADMIN')) {

	return null;
}

require dirname(__FILE__).'/_widgets.php';

# Dashboard item and user preference
$core->addBehavior(
	'adminDashboardItems',
	array('dcLatestVersionsAdmin', 'adminDashboardItems')
);
$core->addBehavior(
	'adminDashboardOptionsForm',
	array('dcLatestVersionsAdmin', 'adminDashboardOptionsForm')
);
$core->addBehavior(
	'adminAfterDashboardOptionsUpdate',
	array('dcLatestVersionsAdmin', 'adminAfterDashboardOptionsUpdate')
);

/**
 * @ingroup DC_PLUGIN_DCLATESTVERSIONS
 * @brief Display latest versions of Dotclear - admin methods.
 * @since 2.6
 */
class dcLatestVersionsAdmin
{
	public static function adminDashboardItems( $core, $items)
	{
		if (!$core->auth->user_prefs->dashboard->get('dcLatestVersionsItems')) {

			return null;
		}

		$builds = (string) $core->blog->settings->dcLatestVersions->builds;
		$builds = explode(',', $builds);
		if (empty($builds)) {
			//no builds, return
			return null;
		}

		$text = __('<li><a href="%u" title="Download Dotclear %v">%r</a> : %v</li>');
		$li = array();

		foreach($builds as $build) {

			$build = strtolower(trim($build));
			if (empty($build)) {
				//no update build, continue
				continue;
			}

			$updater = new dcUpdate(
				DC_UPDATE_URL,
				'dotclear',
				$build,
				DC_TPL_CACHE.'/versions'
			);

			if (false === $updater->check('0')) {
				//if updater as no content -- like no 'sexy' version file update -> continue / not return !!
				continue;
			}

			$li[] = str_replace(
				array(
					'%r',
					'%v',
					'%u'
				),
				array(
					$build,
					$updater->getVersion(),
					$updater->getFileURL()
				),
				$text
			);
		}

		if (empty($li)) {
			//no update, return
			return null;
		}

		//some plugin infos
		#plugin name
			$p_name = $core->plugins->moduleInfo('dcLatestVersions', 'name');
		#plugin version
			$p_version = $core->plugins->moduleInfo($p_name, 'version');

		# Display
		$items[] = new ArrayObject([								//new array not array[0]
			'<div class="box small" id="udclatestversionsitems">'.
			'<h3>'.html::escapeHTML(__("Dotclear's latest versions")).'</h3>'.
			'<p>' .' <i>(v. original:' .$p_version .')</i>'.'</p>'.
			'<ul>'.implode('', $li).'</ul>'.
			'</div>'
			]);
	}

	public static function adminDashboardOptionsForm( $core)
	{
		$plugin = 'dcLatestVersions';
		$plugin_version = 'dcLatestVersions';
		if (!$core->auth->user_prefs->dashboard->prefExists('dcLatestVersionsItems')) {
			$core->auth->user_prefs->dashboard->put(
				'dcLatestVersionsItems',
				false,
				'boolean'
			);
		}
		$pref = $core->auth->user_prefs->dashboard->get('dcLatestVersionsItems');

		//some plugin infos
		#plugin name
			$p_name = $core->plugins->moduleInfo('dcLatestVersions', 'name');
		#plugin version
			$p_version = $core->plugins->moduleInfo($p_name, 'version');

		echo  
		'<div class="fieldset">'.
		'<h4>'.__("Dotclear's latest versions") .'</h4>'.
		'<p>'  .' <i>(v. original:' .$p_version .')</i>'.'</p>'.
		'<p><label class="classic" for="dcLatestVersionsItems">'.
		form::checkbox('dcLatestVersionsItems', 1, $pref).' '.
		__("Show Dotclear's latest versions on dashboards.").
		'</label></p>'.
		'</div>';
	}

	public static function adminAfterDashboardOptionsUpdate($user_id)
	{
		$GLOBALS['core']->auth->user_prefs->dashboard->put(
			'dcLatestVersionsItems',
			!empty($_POST['dcLatestVersionsItems']),
			'boolean'
		);
	}
}
