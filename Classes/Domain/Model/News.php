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
 
/**
 *
 *
 * @package moox_comment
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class News extends \Tx_MooxNews_Domain_Model_News {
	
	/**
	 * comment active
	 *
	 * @var \int
	 */
    protected $commentActive;
	
	/**
	 * rating active
	 *
	 * @var \int
	 */
    protected $ratingActive;
	
	/**
	 * review active
	 *
	 * @var \int
	 */
    protected $reviewActive;
	
	/**
     * get commentActive
	 *
     * @return \integer $commentActive commentActive
     */
    public function getCommentActive() {
       return $this->commentActive;
    }
     
    /**
     * set commentActive
	 *
     * @param integer $commentActive commentActive
	 * @return void
     */
    public function setCommentActive($commentActive) {
        $this->commentActive = $commentActive;
    }
	
	/**
     * get ratingActive
	 *
     * @return \integer $ratingActive ratingActive
     */
    public function getRatingActive() {
       return $this->ratingActive;
    }
     
    /**
     * set ratingActive
	 *
     * @param integer $ratingActive ratingActive
	 * @return void
     */
    public function setRatingActive($ratingActive) {
        $this->ratingActive = $ratingActive;
    }
	
	/**
     * get reviewActive
	 *
     * @return \integer $reviewActive reviewActive
     */
    public function getReviewActive() {
       return $this->reviewActive;
    }
     
    /**
     * set reviewActive
	 *
     * @param integer $reviewActive reviewActive
	 * @return void
     */
    public function setReviewActive($reviewActive) {
        $this->reviewActive = $reviewActive;
    }
}
?>