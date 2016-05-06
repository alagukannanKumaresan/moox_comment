<?php
namespace DCNGmbH\MooxComment\ViewHelpers;

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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper; 
 
class GetRatingsInfoViewHelper extends AbstractViewHelper
{
	
	/**
	 * ratingRepository
	 *
	 * @var \DCNGmbH\MooxComment\Domain\Repository\RatingRepository
	 * @inject	 
	 */
	protected $ratingRepository;		
	
	/**
	 * Get Info
	 *
	 * @param int $uid
	 * @param string $tablenames
	 * @param string $ratingmode
	 * @param bool $extended
	 * @param string $as output variable	
	 * @return array info
	 */
	public function render($uid,$tablenames,$ratingmode = "like_dislike", $extended = FALSE, $as = NULL)
	{		
		$info = array();
		$extendedInfo = array();

		$filter['uid_foreign'] = $uid;
		$filter['tablenames'] = $tablenames;
		$filter['confirmed'] = true;
		
		if(in_array($ratingmode,array("like_dislike","stars"))){
			$info['rating'] = $this->ratingRepository->findRatingInfos($filter,$ratingmode)[0];			
			if($ratingmode=="like_dislike"){
				if($info['rating']['likes']>0){
					$info['rating']['likes_percent'] = round(($info['rating']['likes']/$info['rating']['count'])*100);
				} 
				if($info['rating']['dislikes']>0){
					$info['rating']['dislikes_percent'] = round(($info['rating']['dislikes']/$info['rating']['count'])*100);
				}
			}
			if($extended){
				$infosPerRating = $this->ratingRepository->findInfosPerRating($filter,$ratingmode);
				if(is_array($infosPerRating)){
					foreach($infosPerRating AS $infoPerRating){
						$extendedInfo[] = [
							"rating" => $infoPerRating['rating'],
							"count" => $infoPerRating['count'],
							"percent" => round(($infoPerRating['count']/$info['rating']['count'])*100),
						];
					}
				}
			}
		}
		if($ratingmode=="stars" && !$info['rating']['average']){
			$info['rating']['average'] = 0;
		}
		if($extended){
			$info['rating']['extended'] = $extendedInfo;
		}
		
		if (TRUE === empty($as)) {
			return $info;
		} else {			
			$this->templateVariableContainer->add($as, $info);			
		}				
	}
}
