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
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['VehicleList']          = '{title_legend},name,headline,type;{config_legend},numberOfItems,perPage,skipFirst,sorting,jumpTo;{template_legend:hide},customTpl,imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['VehicleDetails']       = '{title_legend},name,headline,type;{template_legend:hide},customTpl,imgSize;{pdf_legend:hide},pdf_header,pdf_footer;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';


$GLOBALS['TL_DCA']['tl_module']['fields']['pdf_header'] = array
(
  'label'                   => &$GLOBALS['TL_LANG']['tl_module']['pdf_header'],
  'exclude'                 => true,
  'inputType'               => 'fileTree',
  'eval'                    => array('filesOnly'=>true, 'extensions'=>Config::get('validImageTypes'), 'fieldType'=>'radio', 'mandatory'=>true),
  'sql'                     => "binary(16) NULL"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['pdf_footer'] = array
(
  'label'                   => &$GLOBALS['TL_LANG']['tl_module']['pdf_footer'],
  'inputType'               => 'textarea',
  'eval'                    => array('allowHtml' => true),
  'sql'                     => "text NULL"
);
