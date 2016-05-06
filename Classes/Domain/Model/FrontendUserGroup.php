<?php
namespace DCNGmbH\MooxComment\Domain\Model;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Extbase\Persistence\ObjectStorage; 

class FrontendUserGroup extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup
{
	
	/**
	 * uid
	 *
	 * @var int
	 */
    protected $uid;
	
	/**
	 * pid
	 *
	 * @var int
	 */
    protected $pid;
	
	/**
	 * tstamp
	 *
	 * @var int
	 */
    protected $tstamp;
	
	/**
	 * crdate
	 *
	 * @var int
	 */
    protected $crdate;
	
	/**
	 * title
	 *
	 * @var string
	 */
    protected $title;
	
	/**
	 * info
	 *
	 * @var string
	 */
    protected $info;
	
	/**
	 * zip
	 *
	 * @var string
	 */
    protected $zip;
	
	/**
	 * city
	 *
	 * @var string
	 */
    protected $city;
	
	/**
	 * address
	 *
	 * @var string
	 */
    protected $address;
	
	/**
	 * telephone
	 *
	 * @var string
	 */
    protected $telephone;
	
	/**
	 * fax
	 *
	 * @var string
	 */
    protected $fax;
	
	/**
	 * email
	 *
	 * @var string
	 */
    protected $email;
	
	/**
	 * www
	 *
	 * @var string
	 */
    protected $www;
	
	/**
     * get uid
	 *
     * @return int $uid uid
     */
    public function getUid()
	{
		return $this->uid;
    }
     
    /**
     * set uid
	 *
     * @param int $uid uid
	 * @return void
     */
    public function setUid($uid)
	{
        $this->uid = $uid;
    }
	
	/**
     * get pid
	 *
     * @return int $pid pid
     */
    public function getPid()
	{
		return $this->pid;
    }
     
    /**
     * set pid
	 *
     * @param int $pid pid
	 * @return void
     */
    public function setPid($pid)
	{
        $this->pid = $pid;
    }
	
	/**
     * get tstamp
	 *
     * @return int $tstamp tstamp
     */
    public function getTstamp()
	{
		return $this->tstamp;
    }
     
    /**
     * set tstamp
	 *
     * @param int $tstamp tstamp
	 * @return void
     */
    public function setTstamp($tstamp)
	{
        $this->tstamp = $tstamp;
    }
	
	/**
     * get crdate
	 *
     * @return int $crdate crdate
     */
    public function getCrdate()
	{
		return $this->crdate;
    }
     
    /**
     * set crdate
	 *
     * @param int $crdate crdate
	 * @return void
     */
    public function setCrdate($crdate)
	{
        $this->crdate = $crdate;
    }
	
	/**
	 * Get year of crdate
	 *
	 * @return int
	 */
	public function getYearOfCrdate()
	{
		return $this->getCrdate()->format('Y');
	}

	/**
	 * Get month of crdate
	 *
	 * @return int
	 */
	public function getMonthOfCrdate()
	{
		return $this->getCrdate()->format('m');
	}

	/**
	 * Get day of crdate
	 *
	 * @return int
	 */
	public function getDayOfCrdate()
	{
		return (int)$this->crdate->format('d');
	}		
	
	/**
     * get title
	 *
     * @return string $title
     */
    public function getTitle()
	{
		return $this->title;
    }
     
    /**
     * set title
	 *
     * @param string $title
	 * @return void
     */
    public function setTitle($title)
	{
        $this->title = $title;
    }
	
	/**
     * get info
	 *
     * @return string $info
     */
    public function getInfo()
	{
		return $this->info;
    }
     
    /**
     * set info
	 *
     * @param string $info
	 * @return void
     */
    public function setInfo($info)
	{
        $this->info = $info;
    }
	
	/**
     * get zip
	 *
     * @return string $zip
     */
    public function getZip()
	{
		return $this->zip;
    }
     
    /**
     * set zip
	 *
     * @param string $zip
	 * @return void
     */
    public function setZip($zip)
	{
        $this->zip = $zip;
    }
	
	/**
     * get city
	 *
     * @return string $city
     */
    public function getCity()
	{
		return $this->city;
    }
     
    /**
     * set city
	 *
     * @param string $city
	 * @return void
     */
    public function setCity($city)
	{
        $this->city = $city;
    }
	
	/**
     * get address
	 *
     * @return string $address
     */
    public function getAddress()
	{
		return $this->address;
    }
     
    /**
     * set address
	 *
     * @param string $address
	 * @return void
     */
    public function setAddress($address)
	{
        $this->address = $address;
    }
	
	/**
     * get telephone
	 *
     * @return string $telephone
     */
    public function getTelephone()
	{
		return $this->telephone;
    }
     
    /**
     * set telephone
	 *
     * @param string $telephone
	 * @return void
     */
    public function setTelephone($telephone)
	{
        $this->telephone = $telephone;
    }
	
	/**
     * get fax
	 *
     * @return string $fax
     */
    public function getFax()
	{
		return $this->fax;
    }
     
    /**
     * set fax
	 *
     * @param string $fax
	 * @return void
     */
    public function setFax($fax)
	{
        $this->fax = $fax;
    }
	
	/**
     * get email
	 *
     * @return string $email
     */
    public function getEmail()
	{
		return $this->email;
    }
     
    /**
     * set email
	 *
     * @param string $email
	 * @return void
     */
    public function setEmail($email)
	{
        $this->email = $email;
    }
	
	/**
     * get www
	 *
     * @return string $www
     */
    public function getWww()
	{
       return $this->www;
    }
     
    /**
     * set www
	 *
     * @param string $www
	 * @return void
     */
    public function setWww($www)
	{
        $this->www = $www;
    }		
}
?>