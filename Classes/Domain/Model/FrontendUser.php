<?php
namespace DCNGmbH\MooxComment\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Dominic Martin <dm@dcn.de>, DCN GmbH
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use \TYPO3\CMS\Extbase\Persistence\ObjectStorage; 
 
/**
 *
 *
 * @package moox_comment
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class FrontendUser extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser  {
	
	/**
	 * gender
	 *
	 * @var integer
	 */
    protected $gender;
	
	/**
	 * auto name
	 *
	 * @var string
	 */
    protected $autoName;
	
	/**
     * get gender
	 *
     * @return integer $gender gender
     */
    public function getGender() {
       return $this->gender;
    }
     
    /**
     * set gender
	 *
     * @param integer $gender gender
	 * @return void
     */
    public function setGender($gender) {
        $this->gender = $gender;
    }
	
	/**
	 * get auto name
	 *
	 * @return integer $autoName auto name
	 */
	public function getAutoName() {
	   $autoName = $this->firstName." ".$this->lastName." (".$this->username.")";
	   return $autoName;
	}
}
?>