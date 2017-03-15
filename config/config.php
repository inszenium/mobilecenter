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
 * Back end modules
 */
$GLOBALS['BE_MOD']['content']['vehicle'] = array	
(
  'tables'      => array('tl_vehicle'),
);

/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['inszenium']['VehicleList']    = 'MobileCenter\ModuleVehicleList';
$GLOBALS['FE_MOD']['inszenium']['VehicleDetails'] = 'MobileCenter\ModuleVehicleDetails';


/**
 * Content elements
 */
