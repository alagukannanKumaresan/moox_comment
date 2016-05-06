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
 
class Product extends \DCNGmbH\MooxShop\Domain\Model\Product
{
	
	/**
	 * @var int
	 */
    protected $commentActive;
	
	/**
	 * @var int
	 */
    protected $ratingActive;
	
	/**
	 * @var int
	 */
    protected $reviewActive;
	
	/**
     * @return int $commentActive commentActive
     */
    public function getCommentActive()
	{
		return $this->commentActive;
    }
     
    /**
     * @param int $commentActive commentActive
	 * @return void
     */
    public function setCommentActive($commentActive)
	{
        $this->commentActive = $commentActive;
    }
	
	/**
     * @return int $ratingActive ratingActive
     */
    public function getRatingActive()
	{
		return $this->ratingActive;
    }
     
    /**
     * @param int $ratingActive ratingActive
	 * @return void
     */
    public function setRatingActive($ratingActive)
	{
        $this->ratingActive = $ratingActive;
    }
	
	/**
     * @return int $reviewActive reviewActive
     */
    public function getReviewActive()
	{
       return $this->reviewActive;
    }
     
    /**
     * @param int $reviewActive reviewActive
	 * @return void
     */
    public function setReviewActive($reviewActive)
	{
        $this->reviewActive = $reviewActive;
    }
}
?>