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
 
namespace inszenium\MobileCenter;

/**
 * Class VehicleList
 *
 * List all Vehicles
 *
 * @copyright  2017 inszenium
 * @author     Kirsten Roschanski <kirsten@kirsten-roschanski.de>
 */
class ModuleVehicleList extends VehicleModule
{

  /**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'mod_vehiclelist';
  
  /**
	 * Form fields
	 *
	 * @var array
	 */
	protected $form;
  
  
  /**
	 * Return a wildcard in the back end
	 *
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE') {
      
			$objTemplate = new \BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['MobileCenter'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id    = $this->id;
			$objTemplate->link  = $this->name;
			$objTemplate->href  = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
    
		return parent::generate();
	}

  /**
	 * Generate the module
	 */
	protected function compile()
	{  
    global $objPage;
    
    $offset = intval($this->skipFirst);
		$limit = null;

		// Maximum number of items
		if ($this->numberOfItems > 0)	{
			$limit = $this->numberOfItems;
		}
    
    $this->Template->filter_vehicles = $this->generateFilter();
        
    $intTotal = $objVehicles = VehicleModel::findPublished()->numRows;
    
    if ($intTotal <= 0) {
			$this->Template->vehicles = $GLOBALS['TL_LANG']['MSC']['emptyVehicleList'];
			return;
		}

		$total = $intTotal - $offset;

		// Split the results
		if ($this->perPage > 0 && (!isset($limit) || $this->numberOfItems > $this->perPage))
		{      
			// Adjust the overall limit
			if (isset($limit))
			{
				$total = min($limit, $total);
			}

			// Get the current page
			$id = 'page_n' . $this->id;
			$page = \Input::get($id) ?: 1;

			// Do not index or cache the page if the page number is outside the range
			if ($page < 1 || $page > max(ceil($total/$this->perPage), 1))
			{     
				global $objPage;
				$objPage->noSearch = 1;
				$objPage->cache = 0;

				// Send a 404 header
				header('HTTP/1.1 404 Not Found');
				return;
			}

			// Set limit and offset
			$limit = $this->perPage;
			$offset += (max($page, 1) - 1) * $this->perPage;
			$skip = intval($this->skipFirst);

			// Overall limit
			if ($offset + $limit > $total + $skip)
			{
				$limit = $total + $skip - $offset;
			}

			// Add the pagination menu
			$objPagination = new \Pagination($total, $this->perPage, $GLOBALS['TL_CONFIG']['maxPaginationLinks'], $id);
			$this->Template->pagination = $objPagination->generate("\n  ");
		}
    
    $objVehicles = VehicleModel::findPublished($limit, $offset);
    
    while ($objVehicles->next()) {
			$arrTemp = $objVehicles->row();     
			$arrTemp['title'] = specialchars($objVehicles->headline, true);
      
      $arrVehicles[$objVehicles->sorting] = $arrTemp;
		}    
    
    $this->Template->vehicles = $this->parseVehicles($arrVehicles);    
  }  
  
 /**
	 * Generate the filter
	 */
	protected function generateFilter()
	{  
    global $objPage;

    $GLOBALS['TL_LANGUAGE'] = $objPage->language;

		\System::loadLanguageFile('tl_vehicle');
		\System::loadLanguageFile('default');
		$this->loadDataContainer('tl_vehicle');  
		$this->loadDataContainer('default');  
    
    $doNotSubmit = false;
		$arrWidgets = array();
		$strFormId = 'filter_vehicles';
    
    $objTemplate = new \FrontendTemplate('filter_vehicles');
		$objTemplate->class = $strClass;
		$objTemplate->titel = $arrVehicle['headline'];
    
    // Initialize the widgets
		foreach ($GLOBALS['TL_DCA']['tl_vehicle']['fields'] as $field => $arrField)
		{
      // Change inputType
      $arrField['inputType'] = $arrField['inputType'] == 'checkboxWizard' ? 'select' : $arrField['inputType'];

      if($field == 'brand') {
        $arrField['inputType'] = 'select';
        $arrField['options']   = $this->Database->query("SELECT DISTINCT brand FROM tl_vehicle ORDER BY brand")->fetchEach('brand');
      }
      
      if($field == 'milage') {       
        $arrField['inputType'] = 'select';
        $arrField['options']   = array(25000,50000,75000,100000,150000);
        $arrField['reference'] = &$GLOBALS['TL_LANG']['vehicle_filter']['milage'];
      } 
      
      if($field == 'price') {       
        $arrField['inputType'] = 'select';
        $arrField['options']   = array(5000,10000,15000,20000,30000,40000,50000);
        $arrField['reference'] = &$GLOBALS['TL_LANG']['vehicle_filter']['price'];
      } 
      
      if($field == 'fuel') {
        $arrField['inputType'] = 'checkbox';
        $arrField['options']   = $this->Database->query("SELECT DISTINCT fuel FROM tl_vehicle WHERE fuel != ''")->fetchEach('fuel');
      }
      
      if($field == 'tax') {
        $arrField['inputType'] = 'checkbox';     
        $arrField['options']   = &$GLOBALS['TL_LANG']['vehicle_tax'];      
      }
      
      if($field == 'vehicle_typenumber') {
        $arrField['inputType'] = 'select';
        $arrField['options']   = $GLOBALS['TL_LANG']['vehicle_typenumber'];
      }
      
      
      // Load inputType
      $strClass = $GLOBALS['TL_FFL'][$arrField['inputType']];     
      
      // No Default
      $arrField['eval']['mandatory'] = false;

      // Continue if the class is not defined
			if (!class_exists($strClass))
			{
				continue;
			}

      // Change Eval by selectType
      if($arrField['inputType'] == 'select') {
			  $arrField['eval']['includeBlankOption'] = true;
			  $arrField['eval']['blankOptionLabel'] = $arrField['label'][0];
			  $arrField['eval']['multiple'] = false;
      }

      // Check Fields
			if (\Input::get($field))
			{
				$arrField['value'] = \Input::get($field);
			}

			$arrField['eval']['required'] = $arrField['eval']['mandatory'];
			$arrField['eval']['placeholder'] = $arrField['placeholder'] ? $arrField['placeholder'] : $arrField['label'][0];

      $objWidget = new $strClass($strClass::getAttributesFromDca($arrField, $field, $arrField['value'] ));
      $arrWidgets[$field] = $objWidget;

		}
    
    $objTemplate->fields = $arrWidgets;
		$objTemplate->submit = $GLOBALS['TL_LANG']['MSC']['search'];
		$objTemplate->action = ampersand(\Environment::get('request'));
		$objTemplate->messages = ''; // Backwards compatibility
		$objTemplate->formId = $strFormId;
		$objTemplate->hasError = $doNotSubmit;
    
    return $objTemplate->parse();
  }
}
