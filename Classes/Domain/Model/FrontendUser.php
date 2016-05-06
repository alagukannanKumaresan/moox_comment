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

class FrontendUser extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
{
	
	/**
	 * gender
	 *
	 * @var int
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
     * @return int $gender gender
     */
    public function getGender()
	{
		return $this->gender;
    }
     
    /**
     * set gender
	 *
     * @param int $gender gender
	 * @return void
     */
    public function setGender($gender)
	{
        $this->gender = $gender;
    }
	
	/**
	 * get auto name
	 *
	 * @return int $autoName auto name
	 */
	public function getAutoName()
	{
	   $autoName = $this->firstName." ".$this->lastName." (".$this->username.")";
	   return $autoName;
	}
}
?>