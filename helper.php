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

class QuantummanagercontentHelper
{


	/**
	 *
	 * @return array
	 *
	 * @since version
	 */
	public static function getFieldsForScopes()
	{

		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName(array('params')))
			->from('#__extensions')
			->where( 'element=' . $db->quote('quantummanagercontent'));
		$extension = $db->setQuery( $query )->loadObject();
		$params = json_decode($extension->params, JSON_OBJECT_AS_ARRAY);

		if(!isset($params['scopes']) || empty($params['scopes']) || count((array)$params['scopes']) === 0)
		{
			$scopes = self::defaultValues();
		}
		else
		{
			$scopes = $params['scopes'];
		}

		$output = [];

		foreach ($scopes as $scope)
		{
			$output[$scope['id']] = $scope['fieldsform'];
		}

		return $output;
	}


	/**
	 *
	 * @return array
	 *
	 * @since version
	 */
	public static function defaultValues()
	{
		return [
			'images' => [
				'id' => 'images',
				'template' => '<img src="{file}" alt="{alt}" width="{width}" height="{height}" />',
				'fieldsform' => json_encode([
					'fieldsform0' => [
						'nametemplate' => 'width',
						'name' => Text::_('PLG_BUTTON_QUANTUMMANAGERCONTENT_SCOPES_IMAGES_FIELDSFORM_WIDTH_NAME'),
						'default' => '',
						'type' => 'number',
					],
					'fieldsform1' => [
						'nametemplate' => 'height',
						'name' => Text::_('PLG_BUTTON_QUANTUMMANAGERCONTENT_SCOPES_IMAGES_FIELDSFORM_HEIGHT_NAME'),
						'default' => '',
						'type' => 'number',
					],
					'fieldsform3' => [
						'nametemplate' => 'alt',
						'name' => Text::_('PLG_BUTTON_QUANTUMMANAGERCONTENT_SCOPES_IMAGES_FIELDSFORM_ALT_NAME'),
						'default' => '',
						'type' => 'text',
					]
				])
			],
			'docs' => [
				'id' => 'docs',
				'template' => '<a href="{file}" target="_blank">{name}</a>',
				'fieldsform' => json_encode([
					'fieldsform0' => [
						'nametemplate' => 'name',
						'name' => Text::_('PLG_BUTTON_QUANTUMMANAGERCONTENT_SCOPES_IMAGES_FIELDSFORM_ALT_NAME'),
						'default' => Text::_('PLG_BUTTON_QUANTUMMANAGERCONTENT_SCOPES_IMAGES_FIELDSFORM_DEFAULT_NAME'),
						'type' => 'text',
					],
				])
			],
			'music' => [
				'id' => 'music',
				'template' => '<audio controls src="{file}"> ' . Text::_('PLG_BUTTON_QUANTUMMANAGERCONTENT_SCOPES_MUSIC_TEMPLATE_TEXT') . '</audio>',
				'fieldsform' => '',
			],
			'videos' => [
				'id' => 'videos',
				'template' => '<video src="{file}" autoplay>' . Text::_('PLG_BUTTON_QUANTUMMANAGERCONTENT_SCOPES_VIDEOS_TEMPLATE_TEXT') . '</video>',
				'fieldsform' => '',
			]
		];
	}


}