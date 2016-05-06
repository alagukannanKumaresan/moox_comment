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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
 
class Content extends AbstractEntity
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
     * get header
	 *
     * @return string $header header
     */
    public function getHeader()
	{
       return $this->header;
    }
     
    /**
     * set header
	 *
     * @param string $header header
	 * @return void
     */
    public function setHeader($header)
	{
        $this->header = $header;
    }
	
	/**
     * get list type
	 *
     * @return string $listType list type
     */
    public function getListType()
	{
		return $this->listType;
    }
     
    /**
     * set list type
	 *
     * @param string $listType list type
	 * @return void
     */
    public function setListType($listType)
	{
        $this->listType = $listType;
    }
	
	/**
     * get pi flexform
	 *
     * @return string $piFlexform pi flexform
     */
    public function getPiFlexform()
	{
		return $this->piFlexform;
    }
     
    /**
     * set pi flexform
	 *
     * @param string $piFlexform pi flexform
	 * @return void
     */
    public function setPiFlexform($piFlexform)
	{
        $this->piFlexform = $piFlexform;
    }
}
?>