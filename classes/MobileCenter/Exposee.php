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
 * Class Exposee
 *
 * Set Footer & Header
 *
 * @copyright  2017 inszenium
 * @author     Kirsten Roschanski <kirsten@kirsten-roschanski.de>
 */
class Exposee extends \TCPDF
{
    public function Header() {         
      // Logo
      $image_file = "<img src=\"" . TL_PATH . "/" . $this->HeaderData->path . "\">";            
      $this->writeHTMLCell(
            $w = 0, $h = 0, $x = '', $y = 3,
            $image_file, $border = 0, $ln = 1, $fill = 0,
            $reseth = true, $align = 'top', $autopadding = true);
    } 
    public function Footer() { 
      // Position at 15 mm from bottom
      $this->SetY(-15);
      $this->writeHTMLCell(
            $w = 0, $h = 0, $x = '', $y = '',
            $this->FooterData, $border = 0, $ln = 1, $fill = 0,
            $reseth = true, $align = 'top', $autopadding = true);
    } 

}
