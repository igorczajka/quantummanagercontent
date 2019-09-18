<?php
/**
 * @package    quantummanagercontent
 * @author     Dmitry Tsymbal <cymbal@delo-design.ru>
 * @copyright  Copyright © 2019 Delo Design & NorrNext. All rights reserved.
 * @license    GNU General Public License version 3 or later; see license.txt
 * @link       https://www.norrnext.com
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Session\Session;

class PlgButtonQuantummanagercontent extends CMSPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var  boolean
	 *
	 * @since   1.1.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Display the button.
	 *
	 * @param   string  $name  The name of the button to add.
	 *
	 * @throws  Exception
	 *
	 * @return  CMSObject  The button options as CMSObject.
	 *
	 * @since   1.1.0
	 */
	public function onDisplay($name, $asset, $author)
	{
		$user = Factory::getUser();

		// Can create in any category (component permission) or at least in one category
		$canCreateRecords = $user->authorise('core.create', 'com_content')
			|| count($user->getAuthorisedCategories('com_content', 'core.create')) > 0;

		// Instead of checking edit on all records, we can use **same** check as the form editing view
		$values           = (array) Factory::getApplication()->getUserState('com_content.edit.article.id');
		$isEditingRecords = count($values);

		// This ACL check is probably a double-check (form view already performed checks)
		$hasAccess = $canCreateRecords || $isEditingRecords;
		if (!$hasAccess)
		{
			return;
		}

		$function = 'function(){}';

		$link = 'index.php?option=com_quantummanager&amp;layout=content&amp;tmpl=component&amp;e_name=' . $name . '&amp;asset=com_content&amp;author='
			. Session::getFormToken() . '=1&amp;function=' . $function;

		$button          = new CMSObject();
		$button->modal   = true;
		$button->class   = 'btn';
		$button->link    = $link;
		$button->text    = Text::_('PLG_BUTTON_QUANTUMMANAGERCONTENT_BUTTON');
		$button->name    = 'file-add';
		$button->options = "{handler: 'iframe', size: {x: 800, y: 500}}";

		return $button;
	}


	public function onAjaxQuantummanagercontent()
	{

		$app = Factory::getApplication();
		$data = $app->input->getArray();
		$html = 'test';

		if(!isset($data['file'], $data['scope']))
		{
			$app->close();
		}

		JLoader::register('QuantummanagerHelper', JPATH_ROOT . '/administrator/components/com_quantummanager/helpers/quantummanager.php');

		$scope = $data['scope'];
		$file = QuantummanagerHelper::preparePath($data['path'], false, $scope, true);
		$scopesTemplate = $this->params->get('scopes');
		$variables = [
			'{file}' => $file,
		];

		foreach ($data as $key => $value)
		{
			if(preg_match("#^\{.*?\}$#isu", $key))
			{
				$variables[$key] = trim($value);
			}
		}


		foreach ($scopesTemplate as $template)
		{

			if($template->id === $scope)
			{

				if(empty($template->template))
				{

				}

				$variablesFind = [];
				$variablesReplace = [];

				foreach ($variables as $key => $value)
				{
					$variablesFind[] = $key;
					$variablesReplace[] = $value;
				}

				$html = str_replace($variablesFind, $variablesReplace, $template->template);
				$html = preg_replace("#[a-zA-Z]{1,}\=\"\"#isu", '', $html);

			}
		}

		echo $html;

		$app->close();
	}


}