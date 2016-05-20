<?php
namespace DCNGmbH\MooxComment\Domain\Repository;

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

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use DCNGmbH\MooxComment\Domain\Repository\MooxRepository;
 
class RatingRepository extends MooxRepository
{
	
	protected $defaultOrderings = ['tstamp' => QueryInterface::ORDER_DESCENDING];
	
	/**
	 * Returns a constraint array created by a given filter array
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param array $filter
	 * @param array $constraints	
	 * @return \TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface|null
	 */
	protected function createFilterConstraints(QueryInterface $query,$filter = NULL,$constraints = NULL)
	{				
		if(is_null($constraints)){
			
			$constraints = [];
			
		}
		
		if(isset($filter['uid_foreign']) && is_numeric($filter['uid_foreign']) && $filter['uid_foreign']>0 && isset($filter['tablenames']) && $filter['tablenames']!=""){			
			$constraints[] = $query->equals('uidForeign', $filter['uid_foreign']);
			$constraints[] = $query->equals('tablenames', $filter['tablenames']);
			
		} else {
			$constraints[] = $query->equals('uidForeign', 0);
			$constraints[] = $query->equals('tablenames', "dfgdfgjrezu67q348673463");
		}
		
		if(isset($filter['feUser']) && is_numeric($filter['feUser']) && $filter['feUser']>0){			
			$constraints[] = $query->equals('feUser', $filter['feUser']);			
		}
		
		$constraints[] = $query->greaterThan('confirmed', 0);
		
		if(count($constraints)<1){
			
			$constraints = NULL;
			
		}
		
		return $constraints;
	}	
	
	/**
	 * Override default findByUid function to enable also the option to turn of
	 * the enableField setting
	 *
	 * @param int $uid id of record
	 * @param bool $respectEnableFields if set to false, hidden records are shown
	 * @return \DCNGmbH\MooxComment\Domain\Model\Rating
	 */
	public function findByUid($uid, $respectEnableFields = TRUE)
	{		
		$query = $this->createQuery();
		
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->getQuerySettings()->setRespectSysLanguage(FALSE);
		$query->getQuerySettings()->setIgnoreEnableFields(!$respectEnableFields);

		return $query->matching(
			$query->logicalAnd(
				$query->equals('uid', $uid),
				$query->equals('deleted', 0)
			)
		)->execute()->getFirst();
	}	
	
	/**
	 * get rating infos
	 *
	 * @param array $filter
	 * @param string $ratingMode
	 * @return array
	 */
	public function findRatingInfos($filter = [], $ratingMode = "like_dislike")
	{		
		$query = $this->createQuery();
		
		if($ratingMode=="like_dislike"){			
			$query->statement("
				SELECT (SELECT COUNT(*) FROM tx_mooxcomment_domain_model_rating rating
				WHERE
				rating.starttime <= UNIX_TIMESTAMP() AND (rating.endtime >= UNIX_TIMESTAMP() OR rating.endtime=0) 
				AND
				rating.uid_foreign = ".$filter['uid_foreign']."
				AND
				rating.tablenames = '".$filter['tablenames']."'
				AND
				rating.deleted = 0 AND rating.hidden = 0 
				AND 
				rating.rating = 1
				AND
				rating.confirmed>0) AS likes,
				(SELECT COUNT(*) FROM tx_mooxcomment_domain_model_rating rating
				WHERE
				rating.starttime <= UNIX_TIMESTAMP() AND (rating.endtime >= UNIX_TIMESTAMP() OR rating.endtime=0)
				AND
				rating.uid_foreign = ".$filter['uid_foreign']."
				AND
				rating.tablenames = '".$filter['tablenames']."'
				AND
				rating.deleted = 0 AND rating.hidden = 0 
				AND 
				rating.rating = -1
				AND
				rating.confirmed>0) AS dislikes,
				(SELECT COUNT(*) FROM tx_mooxcomment_domain_model_rating rating
				WHERE
				rating.starttime <= UNIX_TIMESTAMP() AND (rating.endtime >= UNIX_TIMESTAMP() OR rating.endtime=0) 
				AND
				rating.uid_foreign = ".$filter['uid_foreign']."
				AND
				rating.tablenames = '".$filter['tablenames']."'
				AND
				rating.deleted = 0 AND rating.hidden = 0 
				AND 
				rating.rating IN (-1,1)
				AND
				rating.confirmed>0) AS count
			");
		} elseif($ratingMode=="stars"){
			$query->statement("
				SELECT AVG(rating) AS average, COUNT(*) AS count FROM tx_mooxcomment_domain_model_rating rating
				WHERE
				rating.starttime <= UNIX_TIMESTAMP() AND (rating.endtime >= UNIX_TIMESTAMP() OR rating.endtime=0) 
				AND
				rating.uid_foreign = ".$filter['uid_foreign']."
				AND
				rating.tablenames = '".$filter['tablenames']."'
				AND
				rating.deleted = 0 AND rating.hidden = 0 
				AND 
				rating.rating>0
				AND
				rating.confirmed>0
			");
		}
				 
		return $query->execute(true);
	}
	
	/**
	 * get infos per rating
	 *
	 * @param array $filter
	 * @param string $ratingMode
	 * @return array
	 */
	public function findInfosPerRating($filter = [], $ratingMode = "like_dislike")
	{		
		$query = $this->createQuery();
		
		if($ratingMode=="like_dislike"){
			$query->statement("
				SELECT rating,COUNT(*) AS 'count' FROM tx_mooxcomment_domain_model_rating
				WHERE
				starttime <= UNIX_TIMESTAMP() AND (endtime >= UNIX_TIMESTAMP() OR endtime=0) 
				AND
				uid_foreign = ".$filter['uid_foreign']."
				AND
				tablenames = '".$filter['tablenames']."'
				AND
				deleted = 0 AND hidden = 0 															
				AND
				confirmed>0
				AND
				rating IN (-1,1)
				GROUP BY rating
				ORDER by rating DESC
			");
		} elseif($ratingMode=="stars"){
			$query->statement("
				SELECT rating,COUNT(*) AS 'count' FROM tx_mooxcomment_domain_model_rating
				WHERE
				starttime <= UNIX_TIMESTAMP() AND (endtime >= UNIX_TIMESTAMP() OR endtime=0) 
				AND
				uid_foreign = ".$filter['uid_foreign']."
				AND
				tablenames = '".$filter['tablenames']."'
				AND
				deleted = 0 AND hidden = 0 															
				AND
				confirmed>0
				AND
				rating>0
				GROUP BY rating
				ORDER by rating DESC
			");
		}
				 
		return $query->execute(true);
	}
}
?>