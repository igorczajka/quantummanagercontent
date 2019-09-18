<?php
/**
 * @package    Radical MultiField
 *
 * @author     delo-design.ru <info@delo-design.ru>
 * @copyright  Copyright (C) 2018 "Delo Design". All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       https://delo-design.ru
 */

use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\Layout\FileLayout;

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('subform');

/**
 * Class JFormFieldRadicalsubform
 */
class JFormFieldQuantummanagerscopesinsert extends JFormFieldSubform
{


	/**
	 * @var string
	 */
	public $type = 'QuantumManagerScopesInsert';


	/**
	 * @return string
	 */
	public function getInput()
	{
		$lang = Factory::getLanguage()->load('com_quantummanager', JPATH_ROOT . '/administrator/components/com_quantummanager');
		JLoader::register('QuantummanagerHelper', JPATH_ROOT . '/administrator/components/com_quantummanager/helpers/quantummanager.php');
		$scopesForInput = [];
		$currentValue = $this->value;
		$scopes = QuantummanagerHelper::getAllScope('all');
		$defaultValues = $this->defaultValues();
		$i = 0;
		foreach ($scopes as $scope) {
			$findValue = null;

			foreach ($currentValue as $value) {
				if ($value['id'] === $scope->id) {
					$findValue = $value;
				}
			}

			$title = '';

			if (substr_count($scope->title, 'COM_QUANTUMMANAGER')) {
				$title = Text::_($scope->title);
			}

			$defaultTemplate = '';
			$defaultFieldsform = '';

			if (isset($defaultValues[$scope->id])) {
				$defaultTemplate = $defaultValues[$scope->id]['template'];
				$defaultFieldsform = $defaultValues[$scope->id]['fieldsform'];
			}

			$scopesForInput['scopes' . $i] = [
				'title' => $scope->title,
				'id' => $scope->id,
				'fieldsform' => $findValue !== null ? $findValue['fieldsform'] : $defaultFieldsform,
				'template' => $findValue !== null ? $findValue['template'] : $defaultTemplate,
			];

			$i++;
		}

		$this->value = $scopesForInput;
		$html = parent::getInput();
		return $html;
	}


}