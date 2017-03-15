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
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'inszenium',
)); 

/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'inszenium\MobileCenter\Exposee'               => 'system/modules/mobilecenter/classes/MobileCenter/Exposee.php',
  
	'inszenium\MobileCenter\ModuleVehicleList'     => 'system/modules/mobilecenter/classes/MobileCenter/Modules/ModuleVehicleList.php',
	'inszenium\MobileCenter\ModuleVehicleDetails'  => 'system/modules/mobilecenter/classes/MobileCenter/Modules/ModuleVehicleDetails.php',
	'inszenium\MobileCenter\VehicleModule'         => 'system/modules/mobilecenter/classes/MobileCenter/Modules/VehicleModule.php',
	'inszenium\MobileCenter\VehicleModel'          => 'system/modules/mobilecenter/classes/MobileCenter/Models/VehicleModel.php',
));  

/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_vehiclelist'                 => 'system/modules/mobilecenter/templates/modules',
	'mod_vehicledetails'              => 'system/modules/mobilecenter/templates/modules',
	'ce_vehicle'                      => 'system/modules/mobilecenter/templates/vehicle',
	'vehicle_exposee'                 => 'system/modules/mobilecenter/templates/print',
	'filter_vehicles'                 => 'system/modules/mobilecenter/templates/form',
));
