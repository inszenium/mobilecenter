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
class ModuleVehicleDetails extends VehicleModule
{

  /**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'mod_vehicledetails';
  
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
    
    // Set the item from the auto_item parameter
		if (!isset($_GET['vehicle']) && $GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
		{
			\Input::setGet('vehicle', \Input::get('auto_item'));
		}

		// Do not index or cache the page if no news item has been specified
		if (!\Input::get('vehicle'))
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;
			return '';
		}
    
    // Send the file to the browser
		if (\Input::get('pdf-file', true))
		{
			$this->printExposee();
		}    
		return parent::generate();
	}

  /**
	 * Generate the module
	 */
	protected function compile()
	{  
		global $objPage;
		\System::loadLanguageFile('tl_vehicle');
		\System::loadLanguageFile('tl_default');

		$this->Template->vehicle = '';

		// Get the news item
		$arrVehicle = VehicleModel::findPublishedByIdOrAlias(\Input::get('vehicle'));  

		if ($arrVehicle === null)
		{
			// Do not index or cache the page
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			// Send a 404 header
			header('HTTP/1.1 404 Not Found');
		    $this->Template->referer = 'javascript:history.go(-1)';
		    $this->Template->back    = $GLOBALS['TL_LANG']['MSC']['goBack']; 
			$this->Template->vehicle = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['invalidPage'], \Input::get('vehicle')) . '</p>';
			return;
		}
    
        $arrVehicle['pdffile']   = '{{env::request}}?pdf-file=true';
        
        
        $arrFeatures  = deserialize($arrVehicle['features']);
        $arrVehicle['features'] = array();
        foreach($arrFeatures as $feature) {
			$arrVehicle['features'][]  = $GLOBALS['TL_LANG']['mobile']['features'][$feature];
		}
		$arrVehicle['features']  = implode(', ', deserialize($arrVehicle['features']));
		
		$arrVehicle['multiSRC'] = deserialize($arrVehicle['multiSRC']);
		$objFiles = \FilesModel::findMultipleByUuids($arrVehicle['multiSRC']);
		
		if ($objFiles === null)
		{
			if (!\Validator::isUuid($arrVehicle['multiSRC'][0]))
			{
				return '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
			}
		}
		
		$images = array();
		
		// Get all images
		while ($objFiles->next())
		{
			// Continue if the files has been processed or does not exist
			if (isset($images[$objFiles->path]) || !file_exists(TL_ROOT . '/' . $objFiles->path))
			{
				continue;
			}
			// Single files
			if ($objFiles->type == 'file')
			{
				$objFile = new \File($objFiles->path, true);
				if (!$objFile->isImage)
				{
					continue;
				}
				$arrMeta = $this->getMetaData($objFiles->meta, $objPage->language);
				// Use the file name as title if none is given
				if ($arrMeta['title'] == '')
				{
					$arrMeta['title'] = specialchars($objFile->basename);
				}
				// Add the image
				$images[$objFiles->path] = array
				(
					'id'        => $objFiles->id,
					'name'      => $objFile->basename,
					'singleSRC' => $objFiles->path,
					'alt'       => $arrMeta['title'],
					'imageUrl'  => $arrMeta['link'],
					'caption'   => $arrMeta['caption']
				);
			}
			// Folders
			else
			{
				$objSubfiles = \FilesModel::findByPid($objFiles->uuid);
				if ($objSubfiles === null)
				{
					continue;
				}
				while ($objSubfiles->next())
				{
					// Skip subfolders
					if ($objSubfiles->type == 'folder')
					{
						continue;
					}
					$objFile = new \File($objSubfiles->path, true);
					if (!$objFile->isImage)
					{
						continue;
					}
					$arrMeta = $this->getMetaData($objSubfiles->meta, $objPage->language);
					// Use the file name as title if none is given
					if ($arrMeta['title'] == '')
					{
						$arrMeta['title'] = specialchars($objFile->basename);
					}
					// Add the image
					$images[$objSubfiles->path] = array
					(
						'id'        => $objSubfiles->id,
						'name'      => $objFile->basename,
						'singleSRC' => $objSubfiles->path,
						'alt'       => $arrMeta['title'],
						'imageUrl'  => $arrMeta['link'],
						'caption'   => $arrMeta['caption']
					);
				}
			}
		}
		
		$arrVehicle['images'] = array_values($images);
		
		
        $this->Template->setData($arrVehicle);  
		$this->Template->referer = 'javascript:history.go(-1)';
		$this->Template->back    = $GLOBALS['TL_LANG']['MSC']['goBack']; 
  }  
  
  /**
	 * Generate an PDF
	 * @param ShopConfigID
	 * @return string
	 */  
  public function printExposee() 
  {    
    $arrVehicle = VehicleModel::findPublishedByIdOrAlias(\Input::get('vehicle'));    

    if($arrVehicle === null) {
        \Controller::reload();
        exit;
    }    
    
    $arrVehicle['vehicle_typenumber']  = $arrVehicle['vehicle_typenumber'] == 'D' ? 'G' : $arrVehicle['vehicle_typenumber'];
    
    $strVehicle = $this->generateExposeePage($arrVehicle, 'vehicle_exposee');
    $strVehicleAlias = $arrVehicle['internal']."-".$arrVehicle['alias'];

    $this->generateExposeePDF($strVehicle, $strVehicleAlias);
  }
  
  /**
	 * Generate a PDF file and optionally send it to the browser
	 * @param string
	 * @param object
	 * @param boolean
	 */
	protected function generateExposeePDF($strVehicle, $strExposeeTitle='exposee', $output='D')
	{    
    // TCPDF configuration
    $l                    = array();
    $l['a_meta_dir']      = 'ltr';
    $l['a_meta_charset']  = $GLOBALS['TL_CONFIG']['characterSet'];
    $l['a_meta_language'] = substr($GLOBALS['TL_LANGUAGE'], 0, 2);
    $l['w_page']          = 'page';
    
    // Include library
    require_once(TL_ROOT . '/system/config/tcpdf.php');

    // Prevent TCPDF from destroying absolute paths
    unset($_SERVER['DOCUMENT_ROOT']);

    // Create new PDF document
    $pdf = new Exposee(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor(PDF_AUTHOR);

    // Remove default header/footer
    $pdf->setPrintHeader($this->pdf_header != '' ? true : false);
    $pdf->setPrintFooter($this->pdf_footer != '' ? true : false);
    
    // set default header data
    $pdf->HeaderData = \FilesModel::findByUuid($this->pdf_header);
    $pdf->FooterData = $this->pdf_footer;

    // Set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

    // Set auto page breaks
    $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    
    // Set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // Set some language-dependent strings
    $pdf->setLanguageArray($l);
    
    // Initialize document and add a page
    $pdf->AddPage();
    
    // Set font
    $pdf->SetFont(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN);

		// Write the Vehicle content
		$pdf->writeHTML($strVehicle, true, 0, true, 0);

    // Close and output PDF document
    $pdf->lastPage();
    //~ 
    if($output == 'D') {
      $pdf->Output(standardize(ampersand($strExposeeTitle, false), true) . '.pdf', $output);
      // Stop script execution
      exit;
    }
    if($output == 'F') {
      $pdf->Output($strExposeeTitle . '.pdf', $output);
    }    
    
  }  
  
    /**
	 * Add comments to a template
	 * @param array
	 */
	protected function generateExposeePage($arrData, $strTemplate='vehicle_exposee')
	{   
    // Load tl_fahrzeugmanager_vehicle language file
    \System::loadLanguageFile('tl_vehicle');
  
    $objTemplate = new \FrontendTemplate($strTemplate);
    $objTemplate->arrData = $arrData;
    foreach ($arrData as $id => $value)
    {
      $objTemplate->$id = array(
        'label' => preg_replace("/\[(.+)\]/", '', $GLOBALS['TL_LANG']['tl_vehicle'][$id][0]),
        'value' => $value,
      );
    }

    // Generate article
		$strArticle = $this->replaceInsertTags($objTemplate->parse(), false);
		$strArticle = html_entity_decode($strArticle, ENT_QUOTES, $GLOBALS['TL_CONFIG']['characterSet']);
		$strArticle = $this->convertRelativeUrls($strArticle, '', true);

    // Remove form elements and JavaScript links
    $arrSearch = array
    (
      '@<form.*</form>@Us',
      '@<a [^>]*href="[^"]*javascript:[^>]+>.*</a>@Us'
    );

    $strArticle = preg_replace($arrSearch, '', $strArticle);

    // Handle line breaks in preformatted text
    $strArticle = preg_replace_callback('@(<pre.*</pre>)@Us', 'nl2br_callback', $strArticle);

    // Default PDF export using TCPDF
    $arrSearch = array
    (
      '@<span style="text-decoration: ?underline;?">(.*)</span>@Us',
      '@(<img[^>]+>)@',
      '@(<div[^>]+block[^>]+>)@',
      '@[\n\r\t]+@',
      '@<br /><div class="mod_article@',
      '@href="([^"]+)(pdf=[0-9]*(&|&amp;)?)([^"]*)"@'
    );

    $arrReplace = array
    (
      '<u>$1</u>',
      '<br />$1',
      '<br />$1',
      ' ',
      '<div class="mod_article',
      'href="$1$4"'
    );

    $strArticle = preg_replace($arrSearch, $arrReplace, $strArticle); 

    return($strArticle);
  }           
  
}  
