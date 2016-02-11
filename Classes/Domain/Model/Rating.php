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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use \TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use \TYPO3\CMS\Extbase\Utility\LocalizationUtility;
 
/**
 *
 *
 * @package moox_comment
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Rating extends AbstractEntity {
	
	/**
	 * uid
	 *
	 * @var \integer
	 */
    protected $uid;
	
	/**
	 * pid
	 *
	 * @var \integer
	 */
    protected $pid;
	
	/**
	 * tstamp
	 *
	 * @var \integer
	 */
    protected $tstamp;		
	
	/**
	 * starttime
	 *
	 * @var \integer
	 */
    protected $starttime;
	
	/**
	 * endtime
	 *
	 * @var \integer
	 */
    protected $endtime;
	
	/**
	 * crdate
	 *
	 * @var \integer
	 */
    protected $crdate;
	
	/**
	 * hidden
	 *
	 * @var \integer
	 */
    protected $hidden;
	
	/**
	 * uid foreign
	 *
	 * @var \integer
	 */
    protected $uidForeign;
	
	/**
	 * url foreign 
	 *
	 * @var \string
	 */
    protected $urlForeign;
	
	/**
	 * title foreign 
	 *
	 * @var \string
	 */
    protected $titleForeign;
	
	/**
	 * tablenames
	 *
	 * @var \string
	 */
    protected $tablenames;
	
	/**
	 * fe user
	 *
	 * @var \DCNGmbH\MooxComment\Domain\Model\FrontendUser
	 */
	protected $feUser = NULL;
		
	/**
	 * title
	 *	
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $title;
	
	/**
	 * rating
	 *
	 * @var \string	
	 */
	protected $rating;
	
	
	/**
	 * confirmed
	 *
	 * @var \integer
	 */
    protected $confirmed;	
	
	/**
     * get uid
	 *
     * @return \integer $uid uid
     */
    public function getUid() {
       return $this->uid;
    }
     
    /**
     * set uid
	 *
     * @param \integer $uid uid
	 * @return void
     */
    public function setUid($uid) {
        $this->uid = $uid;
    }
	
	/**
     * get pid
	 *
     * @return \integer $pid pid
     */
    public function getPid() {
       return $this->pid;
    }
     
    /**
     * set pid
	 *
     * @param \integer $pid pid
	 * @return void
     */
    public function setPid($pid) {
        $this->pid = $pid;
    }
	
	/**
     * get tstamp
	 *
     * @return \integer $tstamp tstamp
     */
    public function getTstamp() {
       return $this->tstamp;
    }
     
    /**
     * set tstamp
	 *
     * @param \integer $tstamp tstamp
	 * @return void
     */
    public function setTstamp($tstamp) {
        $this->tstamp = $tstamp;
    }		
	
	/**
     * get starttime
	 *
     * @return \integer $starttime starttime
     */
    public function getStarttime() {
       return $this->starttime;
    }
     
    /**
     * set starttime
	 *
     * @param \integer $starttime starttime
	 * @return void
     */
    public function setStarttime($starttime) {
        $this->starttime = $starttime;
    }
	
	/**
     * get endtime
	 *
     * @return \integer $endtime endtime
     */
    public function getEndtime() {
       return $this->endtime;
    }
     
    /**
     * set endtime
	 *
     * @param \integer $endtime endtime
	 * @return void
     */
    public function setEndtime($endtime) {
        $this->endtime = $endtime;
    }
	
	/**
     * get crdate
	 *
     * @return \integer $crdate crdate
     */
    public function getCrdate() {
       return $this->crdate;
    }
     
    /**
     * set crdate
	 *
     * @param \integer $crdate crdate
	 * @return void
     */
    public function setCrdate($crdate) {
        $this->crdate = $crdate;
    }
	
	/**
	 * Get year of crdate
	 *
	 * @return \integer
	 */
	public function getYearOfCrdate() {
		return $this->getCrdate()->format('Y');
	}

	/**
	 * Get month of crdate
	 *
	 * @return \integer
	 */
	public function getMonthOfCrdate() {
		return $this->getCrdate()->format('m');
	}

	/**
	 * Get day of crdate
	 *
	 * @return \integer
	 */
	public function getDayOfCrdate() {
		return (int)$this->crdate->format('d');
	}
	
	/**
	 * Returns the hidden
	 *
	 * @return \integer $hidden
	 */
	public function getHidden() {
		return $this->hidden;
	}

	/**
	 * Sets the hidden
	 *
	 * @param \integer $hidden
	 * @return void
	 */
	public function setHidden($hidden) {
		$this->hidden = $hidden;
	}			
	
	/**
     * get uid foreign
	 *
     * @return \integer $uidForeign uid foreign
     */
    public function getUidForeign() {
       return $this->uidForeign;
    }
     
    /**
     * set uid foreign
	 *
     * @param \integer $uidForeign uid foreign
	 * @return void
     */
    public function setUidForeign($uidForeign) {
        $this->uidForeign = $uidForeign;
    }
	
	/**
     * get title foreign
	 *
     * @return \string $titleForeign title foreign
     */
    public function getTitleForeign() {
       return $this->titleForeign;
    }
     
    /**
     * set title foreign
	 *
     * @param \string $titleForeign title foreign
	 * @return void
     */
    public function setTitleForeign($titleForeign) {
        $this->titleForeign = $titleForeign;
    }
	
	/**
     * get url foreign
	 *
     * @return \string $urlForeign url foreign
     */
    public function getUrlForeign() {
       return $this->urlForeign;
    }
     
    /**
     * set url foreign
	 *
     * @param \string $urlForeign url foreign
	 * @return void
     */
    public function setUrlForeign($urlForeign) {
        $this->urlForeign = $urlForeign;
    }
	
	/**
     * get tablenames
	 *
     * @return \string $tablenames tablenames
     */
    public function getTablenames() {
       return $this->tablenames;
    }
     
    /**
     * set tablenames
	 *
     * @param \string $tablenames tablenames
	 * @return void
     */
    public function setTablenames($tablenames) {
        $this->tablenames = $tablenames;
    }
	
	/**
	 * Returns the fe user
	 *
	 * @return \DCNGmbH\MooxComment\Domain\Model\FrontendUser $feUser
	 */
	public function getFeUser() {
		return $this->feUser;
	}

	/**
	 * Sets the fe user
	 *
	 * @param \DCNGmbH\MooxComment\Domain\Model\FrontendUser $feUser
	 * @return void
	 */
	public function setFeUser(\DCNGmbH\MooxComment\Domain\Model\FrontendUser $feUser) {
		$this->feUser = $feUser;
	}
	
	/**
	 * Returns the title
	 *
	 * @return \string $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets the title
	 *
	 * @param \string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}
	
	/**
	 * Returns the rating
	 *
	 * @return \string $rating
	 */
	public function getRating() {
		return $this->rating;
	}

	/**
	 * Sets the rating
	 *
	 * @param \string $rating
	 * @return void
	 */
	public function setRating($rating) {
		$this->rating = $rating;
	}
		
	/**
     * get confirmed
	 *
     * @return \integer $confirmed
     */
    public function getConfirmed() {
       return $this->confirmed;
    }
     
    /**
     * set confirmed
	 *
     * @param integer $confirmed confirmed
	 * @return void
     */
    public function setConfirmed($confirmed) {
        $this->confirmed = $confirmed;
    }	

	/**
     * get hash
	 *
     * @return \string $hash
     */
    public function getHash() {
       return md5($this->uid.$GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'].$this->crdate);
    }
}
?>