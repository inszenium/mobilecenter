<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2017 inszenium
 *
 * @package   MobileCenter
 * @author    Kirsten Roschanski <kirsten@kirsten-roschanski.de>
 * @license   LGPL
 * @copyright 2017
 */

/**
 * Table tl_vehicle
 */
 
$GLOBALS['TL_DCA']['tl_vehicle'] = array
(
	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'alias' => 'index',
				'start,stop,published' => 'index'
			)
		)
	),
	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                  => 1,
			'fields'                => array('headline'),
			'headerFields'          => array('headline', 'number'),
			'panelLayout'           => 'filter;sort,search,limit',
		),
		'label' => array
		(
			'fields'              => array('order_number','headline'),
			'format'              => '[%s] - %s',
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_vehicle']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_vehicle']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_vehicle']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_vehicle']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_vehicle']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_vehicle', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_vehicle']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),
	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('addImage', 'published'),
		'default'                     => '{title_legend},headline,alias;{vehicle_legend},order_number,class,category,make,model,model_description,description,accident_damaged,roadworthy,damage_and_unrepaired,price,currency,vat;{features_legend},features;{specifics_legend},exterior_color,door_count,emission_class,emission_sticker,fuel,gearbox,climatisation,mobile_condition,usage_type,interior_color,interior_type,number_of_previous_owners,mileage,general_inspection,first_registration,power,schwacke_code,num_seats,cubic_capacity,hsn,tsn;{emission_fuel_consumption_legend},envkv_compliant,energy_efficiency_class,co2_emission,inner,outer,combined,unit,petrol_type;{image_legend},addImage;{expert_legend:hide},cssClass,noComments,featured;{publish_legend},published'
	),
	// Subpalettes
	'subpalettes' => array
	(
		'addImage'                    => 'multiSRC,sortBy,metaIgnore',
		'published'                   => 'start,stop'
	),
	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
		),
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		
		// title_legend
		'headline' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['headline'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_vehicle', 'generateHeadline')
			),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'alias', 'unique'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_vehicle', 'generateAlias')
			),
			'sql'                     => "varchar(128) COLLATE utf8_bin NOT NULL default ''"
		),
		
		// vehicle_legend
		'order_number' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['order_number'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'clr w50 w50h'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['description'],
			'inputType'               => 'textarea',
			'exclude'                 => true,
			'eval'                    => array('rte'=>'tinyMCE', 'tl_class'=>'clr'),
			'sql'                     => "text NULL"
		),
		'class' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['class'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'				  => array_keys($GLOBALS['TL_LANG']['mobile']['classes']), 
			'reference'               => &$GLOBALS['TL_LANG']['mobile']['classes'],      
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'clr w50 w50h'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'category' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['category'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'				  => array_keys($GLOBALS['TL_LANG']['mobile']['categories']), 
			'reference'               => &$GLOBALS['TL_LANG']['mobile']['categories'],      
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50 w50h'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'make' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['makes'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			
			//~ 'inputType'               => 'select',
			//~ 'options'				  => array_keys($GLOBALS['TL_LANG']['mobile']['makes']), 
			//~ 'reference'               => &$GLOBALS['TL_LANG']['mobile']['make'],      
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'clr w50 w50h'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'model' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['model'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50 w50h'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'model_description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['model_description'],
			'search'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'clr w50 w50h'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'accident_damaged' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['accident_damaged'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'default'				  => 'false',
			'options'                 => array('true','false'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('tl_class'=>'w50 w50h'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'roadworthy' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['roadworthy'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'default'				  => 'false',
			'options'                 => array('true','false'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('tl_class'=>'w50 w50h'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'damage_and_unrepaired' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['damage_and_unrepaired'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'default'				  => 'false',
			'options'                 => array('true','false'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('tl_class'=>'clr w50 w50h'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'price' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['price'],
			'search'                  => true,
			'sorting'                 => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'price', 'tl_class'=>'clr w50'),
			'sql'                     => "decimal(12,2) NOT NULL default '0.00'"
		),
		'currency' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['currency'],
			'search'                  => true,
			'sorting'                 => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'vat' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['vat'],
			'search'                  => true,
			'sorting'                 => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'clr w50 w50h'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		
		// features
		'features' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['features'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'				  => array_keys($GLOBALS['TL_LANG']['mobile']['features']), 
			'reference'               => &$GLOBALS['TL_LANG']['mobile']['features'],      
			'eval'                    => array('multiple'=>true, 'tl_class'=>'w50 w50h clr', 'chosen'=>true),
			'sql'                     => "blob NULL"
		),
		
		// specifics
		'exterior_color' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['exterior_color'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'				  => array_keys($GLOBALS['TL_LANG']['mobile']['color']), 
			'reference'               => &$GLOBALS['TL_LANG']['mobile']['color'],      
			'eval'                    => array('tl_class'=>'w50 w50h', 'includeBlankOption' => true),
			'sql'                     => "blob NULL"
		),
		'interior_color' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['exterior_color'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'				  => array_keys($GLOBALS['TL_LANG']['mobile']['color']), 
			'reference'               => &$GLOBALS['TL_LANG']['mobile']['color'],      
			'eval'                    => array('tl_class'=>'w50 w50h', 'includeBlankOption' => true),
			'sql'                     => "blob NULL"
		),
		'door_count' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['door_count'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'				  => array_keys($GLOBALS['TL_LANG']['mobile']['doorcounts']), 
			'reference'               => &$GLOBALS['TL_LANG']['mobile']['doorcounts'],      
			'eval'                    => array('tl_class'=>'w50 w50h', 'includeBlankOption' => true),
			'sql'                     => "blob NULL"
		),
		'emission_class' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['emission_class'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'				  => array_keys($GLOBALS['TL_LANG']['mobile']['emissionclasses']), 
			'reference'               => &$GLOBALS['TL_LANG']['mobile']['emissionclasses'],      
			'eval'                    => array('tl_class'=>'w50 w50h', 'includeBlankOption' => true),
			'sql'                     => "blob NULL"
		),
		'emission_sticker' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['emission_sticker'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'				  => array_keys($GLOBALS['TL_LANG']['mobile']['emissionstickers']), 
			'reference'               => &$GLOBALS['TL_LANG']['mobile']['emissionstickers'],      
			'eval'                    => array('tl_class'=>'w50 w50h', 'includeBlankOption' => true),
			'sql'                     => "blob NULL"
		),
		'fuel' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['fuel'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'				  => array_keys($GLOBALS['TL_LANG']['mobile']['fuels']), 
			'reference'               => &$GLOBALS['TL_LANG']['mobile']['fuels'],      
			'eval'                    => array('tl_class'=>'w50 w50h', 'includeBlankOption' => true),
			'sql'                     => "blob NULL"
		),
		'gearbox' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['gearbox'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'				  => array_keys($GLOBALS['TL_LANG']['mobile']['gearboxes']), 
			'reference'               => &$GLOBALS['TL_LANG']['mobile']['gearboxes'],      
			'eval'                    => array('tl_class'=>'w50 w50h', 'includeBlankOption' => true),
			'sql'                     => "blob NULL"
		),
		'climatisation' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['climatisation'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'				  => array_keys($GLOBALS['TL_LANG']['mobile']['climatisations']), 
			'reference'               => &$GLOBALS['TL_LANG']['mobile']['climatisations'],      
			'eval'                    => array('tl_class'=>'w50 w50h', 'includeBlankOption' => true),
			'sql'                     => "blob NULL"
		),
		'mobile_condition' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['mobile_condition'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'				  => array_keys($GLOBALS['TL_LANG']['mobile']['conditions']), 
			'reference'               => &$GLOBALS['TL_LANG']['mobile']['conditions'],      
			'eval'                    => array('tl_class'=>'clr w50 w50h', 'includeBlankOption' => true),
			'sql'                     => "blob NULL"
		),
		'usage_type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['usage_type'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'				  => array_keys($GLOBALS['TL_LANG']['mobile']['usagetypes']), 
			'reference'               => &$GLOBALS['TL_LANG']['mobile']['usagetypes'],      
			'eval'                    => array('tl_class'=>'w50 w50h', 'includeBlankOption' => true),
			'sql'                     => "blob NULL"
		),
		'interior_type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['interior_type'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'				  => array_keys($GLOBALS['TL_LANG']['mobile']['interiorTypes']), 
			'reference'               => &$GLOBALS['TL_LANG']['mobile']['interiorTypes'],      
			'eval'                    => array('tl_class'=>'w50 w50h', 'includeBlankOption' => true),
			'sql'                     => "blob NULL"
		),
		'interior_color' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['exterior_color'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'				  => array_keys($GLOBALS['TL_LANG']['mobile']['interiorColors']), 
			'reference'               => &$GLOBALS['TL_LANG']['mobile']['interiorColors'],      
			'eval'                    => array('tl_class'=>'w50 w50h', 'includeBlankOption' => true),
			'sql'                     => "blob NULL"
		),
		'number_of_previous_owners' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['number_of_previous_owners'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
		),
		'mileage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['mileage'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
		),
		'general_inspection' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['general_inspection'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'first_registration' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['first_registration'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'power' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['power'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
		),
		'schwacke_code' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['schwacke_code'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "smallint(8) unsigned NOT NULL default '0'"
		),
		'num_seats' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['num_seats'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
		),
		'cubic_capacity' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['cubic_capacity'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
		),
		'hsn' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['hsn'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
		),
		'tsn' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['tsn'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'tsn' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['tsn'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		
		'envkv_compliant' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['envkv_compliant'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'default'				  => 'false',
			'options'                 => array('true','false'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('tl_class'=>'w50 w50h'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'energy_efficiency_class' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['energy_efficiency_class'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('A','B','C','D','E','F','G'),
			'eval'                    => array('tl_class'=>'w50 w50h'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),	
		'co2_emission' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['co2_emission'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "smallint(5) unsigned NOT NULL default '0'"
		),			
		'petrol_inner' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['petrol_inner'],
			'search'                  => true,
			'sorting'                 => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'price', 'tl_class'=>'w50'),
			'sql'                     => "decimal(12,2) NOT NULL default '0.00'"
		),		
		'petrol_outer' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['petrol_outer'],
			'search'                  => true,
			'sorting'                 => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'price', 'tl_class'=>'w50'),
			'sql'                     => "decimal(12,2) NOT NULL default '0.00'"
		),
		'petrol_combined' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['petrol_combined'],
			'search'                  => true,
			'sorting'                 => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'price', 'tl_class'=>'w50'),
			'sql'                     => "decimal(12,2) NOT NULL default '0.00'"
		),
		'unit' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['unit'],
			'search'                  => true,
			'sorting'                 => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'petrol_type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['petrol_type'],
			'search'                  => true,
			'sorting'                 => true,
			'filter'                  => true,
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'				  => array_keys($GLOBALS['TL_LANG']['mobile']['petroltypes']), 
			'reference'               => &$GLOBALS['TL_LANG']['mobile']['petroltypes'],      
			'eval'                    => array('tl_class'=>'w50 w50h', 'includeBlankOption' => true),
			'sql'                     => "blob NULL"
		),		
		
		// Images
		'addImage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['addImage'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'multiSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['multiSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('multiple'=>true, 'fieldType'=>'checkbox', 'orderField'=>'orderSRC', 'files'=>true, 'mandatory'=>true, 'isGallery'=>true),
			'sql'                     => "blob NULL",
		),
		'sortBy' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['sortBy'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('custom', 'name_asc', 'name_desc', 'date_asc', 'date_desc', 'random'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_vehicle'],
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'orderSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['orderSRC'],
			'sql'                     => "blob NULL"
		),		
		'metaIgnore' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['metaIgnore'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50 m12'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		
		// default
		'cssClass' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['cssClass'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['published'],
			'exclude'                 => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true, 'doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['start'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_vehicle']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		)
	)
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Kirsten Roschanski <https://github.com/kirsten-roschanski>
 */
class tl_vehicle extends Backend
{

  /**
	 * Auto-generate the alias if it has not been set yet
	 *
	 * @param mixed         $varValue
	 * @param DataContainer $dc
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function generateAlias($varValue, DataContainer $dc)
	{
		$autoAlias = false;
		// Generate alias if there is none
		if ($varValue == '') {
			$autoAlias = true;
      if ($dc->activeRecord->headline != '') {
        $varValue = standardize(String::restoreBasicEntities($dc->activeRecord->order_number . ' ' . $dc->activeRecord->headline));
      } else {
        $varValue = standardize(String::restoreBasicEntities($dc->activeRecord->order_number . ' ' . $dc->activeRecord->make . ' ' . $dc->activeRecord->model-description));
      }  
		}
		$objAlias = $this->Database->prepare("SELECT id FROM tl_vehicle WHERE alias=?")->execute($varValue);
		// Check whether the news alias exists
		if ($objAlias->numRows > 1 && !$autoAlias) {
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}
		// Add ID to alias
		if ($objAlias->numRows && $autoAlias) {
			$varValue .= '-' . $dc->id;
		}
		return $varValue;
	}
  
  /**
	 * Auto-generate the headline if it has not been set yet
	 *
	 * @param mixed         $varValue
	 * @param DataContainer $dc
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function generateHeadline($varValue, DataContainer $dc)
	{
		// Generate Headline if there is none
		if ($varValue == '') {
			$varValue = $dc->activeRecord->make . ' ' . ($dc->activeRecord->model-description ? $dc->activeRecord->model-description : $dc->activeRecord->model);
		}
    
		return $varValue;
	}
  

	/**
	 * Return the "toggle visibility" button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('tid')))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);
		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}
		$objPage = $this->Database->prepare("SELECT * FROM tl_vehicle WHERE id=?")
								  ->limit(1)
								  ->execute($row['pid']);

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
	}  
  
  /**
	 * Disable/enable a user group
	 *
	 * @param integer       $intId
	 * @param boolean       $blnVisible
	 * @param DataContainer $dc
	 */
	public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
	{
		// Check permissions to edit
		Input::setGet('id', $intId);
		Input::setGet('act', 'toggle');

		$objVersions = new Versions('tl_vehicle', $intId);
		$objVersions->initialize();
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_vehicle']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_vehicle']['fields']['published']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, ($dc ?: $this));
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, ($dc ?: $this));
				}
			}
		}
		// Update the database
		$this->Database->prepare("UPDATE tl_vehicle SET tstamp=". time() .", published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
					   ->execute($intId);
		$objVersions->create();
		$this->log('A new version of record "tl_vehicle.id='.$intId.'" has been created'.$this->getParentEntries('tl_vehicle', $intId), __METHOD__, TL_GENERAL);
	}

}
