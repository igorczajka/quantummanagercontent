<?php
/**
 * @package    DachaDacha Package
 * @version    __DEPLOY_VERSION__
 * @author     Septdir Workshop - septdir.com
 * @copyright  Copyright (c) 2018 - 2019 Septdir Workshop. All rights reserved.
 * @license    GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link       https://www.septdir.com/
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;

class plgEditorsXTDQuantummanagercontentInstallerScript
{
	/**
	 * Runs right after any installation action.
	 *
	 * @param   string            $type    Type of PostFlight action. Possible values are:
	 * @param   InstallerAdapter  $parent  Parent object calling object.
	 *
	 * @since  1.1.0
	 */
	function postflight($type, $parent)
	{
		// Enable plugin
		if ($type == 'install')
		{
			$this->enablePlugin($parent);
		}
	}

	/**
	 * Enable plugin after installation.
	 *
	 * @param   InstallerAdapter  $parent  Parent object calling object.
	 *
	 * @since  1.1.0
	 */
	protected function enablePlugin($parent)
	{
		// Prepare plugin object
		$plugin          = new stdClass();
		$plugin->type    = 'plugin';
		$plugin->element = $parent->getElement();
		$plugin->folder  = (string) $parent->getParent()->manifest->attributes()['group'];
		$plugin->enabled = 1;

		// Update record
		Factory::getDbo()->updateObject('#__extensions', $plugin, array('type', 'element', 'folder'));
	}
}