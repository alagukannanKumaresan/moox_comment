<?php
namespace DCNGmbH\MooxComment\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Dominic Martin <dm@dcn.de>, DCN GmbH
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

/**
 *
 * @package moox_comment
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @scope prototype
 * @entity
 *
 */
class Content extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {
	
	/**
	 * uid
	 *
	 * @var integer
	 */
    protected $uid;
	
	/**
	 * pid
	 *
	 * @var integer
	 */
    protected $pid;
	
	/**
	 * header
	 *
	 * @var string
	 */
    protected $header;
	
	/**
	 * list type
	 *
	 * @var string
	 */
    protected $listType;
	
	/**
	 * pi flexform
	 *
	 * @var string
	 */
    protected $piFlexform;
	
	/**
     * get uid
	 *
     * @return integer $uid uid
     */
    public function getUid() {
       return $this->uid;
    }
     
    /**
     * set uid
	 *
     * @param integer $uid uid
	 * @return void
     */
    public function setUid($uid) {
        $this->uid = $uid;
    }
	
	/**
     * get pid
	 *
     * @return integer $pid pid
     */
    public function getPid() {
       return $this->pid;
    }
     
    /**
     * set pid
	 *
     * @param integer $pid pid
	 * @return void
     */
    public function setPid($pid) {
        $this->pid = $pid;
    }
	
	/**
     * get header
	 *
     * @return string $header header
     */
    public function getHeader() {
       return $this->header;
    }
     
    /**
     * set header
	 *
     * @param string $header header
	 * @return void
     */
    public function setHeader($header) {
        $this->header = $header;
    }
	
	/**
     * get list type
	 *
     * @return string $listType list type
     */
    public function getListType() {
       return $this->listType;
    }
     
    /**
     * set list type
	 *
     * @param string $listType list type
	 * @return void
     */
    public function setListType($listType) {
        $this->listType = $listType;
    }
	
	/**
     * get pi flexform
	 *
     * @return string $piFlexform pi flexform
     */
    public function getPiFlexform() {
       return $this->piFlexform;
    }
     
    /**
     * set pi flexform
	 *
     * @param string $piFlexform pi flexform
	 * @return void
     */
    public function setPiFlexform($piFlexform) {
        $this->piFlexform = $piFlexform;
    }
}
?>