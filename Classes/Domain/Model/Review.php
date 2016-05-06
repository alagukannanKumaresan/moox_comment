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
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
 
class Review extends AbstractEntity
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
	 * starttime
	 *
	 * @var int
	 */
    protected $starttime;
	
	/**
	 * endtime
	 *
	 * @var int
	 */
    protected $endtime;
	
	/**
	 * crdate
	 *
	 * @var int
	 */
    protected $crdate;
	
	/**
	 * hidden
	 *
	 * @var int
	 */
    protected $hidden;
	
	/**
	 * parent
	 *
	 * @var int
	 */
    protected $parent;
	
	/**
	 * uid foreign
	 *
	 * @var int
	 */
    protected $uidForeign;
	
	/**
	 * url foreign 
	 *
	 * @var string
	 */
    protected $urlForeign;
	
	/**
	 * title foreign 
	 *
	 * @var string
	 */
    protected $titleForeign;
	
	/**
	 * tablenames
	 *
	 * @var string
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
	 * @var string
	 * @validate NotEmpty
	 */
	protected $title;
	
	/**
	 * name
	 *
	 * @var string	
	 */
	protected $name;
	
	/**
	 * email
	 *
	 * @var string	
	 */
	protected $email;
	
	/**
	 * comment
	 *
	 * @var string	
	 */
	protected $comment;
	
	/**
	 * rating
	 *
	 * @var string	
	 */
	protected $rating;
	
	
	/**
	 * confirmed
	 *
	 * @var int
	 */
    protected $confirmed;	
	
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
     * get starttime
	 *
     * @return int $starttime starttime
     */
    public function getStarttime()
	{
       return $this->starttime;
    }
     
    /**
     * set starttime
	 *
     * @param int $starttime starttime
	 * @return void
     */
    public function setStarttime($starttime)
	{
        $this->starttime = $starttime;
    }
	
	/**
     * get endtime
	 *
     * @return int $endtime endtime
     */
    public function getEndtime()
	{
       return $this->endtime;
    }
     
    /**
     * set endtime
	 *
     * @param int $endtime endtime
	 * @return void
     */
    public function setEndtime($endtime)
	{
        $this->endtime = $endtime;
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
	 * Returns the hidden
	 *
	 * @return int $hidden
	 */
	public function getHidden()
	{
		return $this->hidden;
	}

	/**
	 * Sets the hidden
	 *
	 * @param int $hidden
	 * @return void
	 */
	public function setHidden($hidden)
	{
		$this->hidden = $hidden;
	}	
	
	/**
     * get parent
	 *
     * @return int $parent parent
     */
    public function getParent()
	{
       return $this->parent;
    }
     
    /**
     * set parent
	 *
     * @param int $parent parent
	 * @return void
     */
    public function setParent($parent)
	{
        $this->parent = $parent;
    }
	
	/**
     * get uid foreign
	 *
     * @return int $uidForeign uid foreign
     */
    public function getUidForeign()
	{
       return $this->uidForeign;
    }
     
    /**
     * set uid foreign
	 *
     * @param int $uidForeign uid foreign
	 * @return void
     */
    public function setUidForeign($uidForeign)
	{
        $this->uidForeign = $uidForeign;
    }
	
	/**
     * get title foreign
	 *
     * @return string $titleForeign title foreign
     */
    public function getTitleForeign()
	{
       return $this->titleForeign;
    }
     
    /**
     * set title foreign
	 *
     * @param string $titleForeign title foreign
	 * @return void
     */
    public function setTitleForeign($titleForeign)
	{
        $this->titleForeign = $titleForeign;
    }
	
	/**
     * get url foreign
	 *
     * @return string $urlForeign url foreign
     */
    public function getUrlForeign()
	{
       return $this->urlForeign;
    }
     
    /**
     * set url foreign
	 *
     * @param string $urlForeign url foreign
	 * @return void
     */
    public function setUrlForeign($urlForeign)
	{
        $this->urlForeign = $urlForeign;
    }
	
	/**
     * get tablenames
	 *
     * @return string $tablenames tablenames
     */
    public function getTablenames()
	{
       return $this->tablenames;
    }
     
    /**
     * set tablenames
	 *
     * @param string $tablenames tablenames
	 * @return void
     */
    public function setTablenames($tablenames)
	{
        $this->tablenames = $tablenames;
    }
	
	/**
	 * Returns the fe user
	 *
	 * @return \DCNGmbH\MooxComment\Domain\Model\FrontendUser $feUser
	 */
	public function getFeUser()
	{
		return $this->feUser;
	}

	/**
	 * Sets the fe user
	 *
	 * @param \DCNGmbH\MooxComment\Domain\Model\FrontendUser $feUser
	 * @return void
	 */
	public function setFeUser(\DCNGmbH\MooxComment\Domain\Model\FrontendUser $feUser)
	{
		$this->feUser = $feUser;
	}
	
	/**
	 * Returns the title
	 *
	 * @return string $title
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Sets the title
	 *
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	/**
	 * Returns the name
	 *
	 * @return string $name
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Sets the name
	 *
	 * @param string $name
	 * @return void
	 */
	public function setName($name)
	{
		$this->name = $name;
	}
	
	/**
	 * Returns the email
	 *
	 * @return string $email
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Sets the email
	 *
	 * @param string $email
	 * @return void
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}
	
	/**
	 * Returns the comment
	 *
	 * @return string $comment
	 */
	public function getComment()
	{
		return $this->comment;
	}

	/**
	 * Sets the comment
	 *
	 * @param string $comment
	 * @return void
	 */
	public function setComment($comment)
	{
		$this->comment = $comment;
	}
	
	/**
	 * Returns the rating
	 *
	 * @return string $rating
	 */
	public function getRating()
	{
		return $this->rating;
	}

	/**
	 * Sets the rating
	 *
	 * @param string $rating
	 * @return void
	 */
	public function setRating($rating)
	{
		$this->rating = $rating;
	}
		
	/**
     * get confirmed
	 *
     * @return int $confirmed
     */
    public function getConfirmed()
	{
       return $this->confirmed;
    }
     
    /**
     * set confirmed
	 *
     * @param int $confirmed confirmed
	 * @return void
     */
    public function setConfirmed($confirmed)
	{
        $this->confirmed = $confirmed;
    }	
	
	/**
     * get moderator
	 *
     * @return int $moderator
     */
    public function getModerator()
	{
       return $this->moderator;
    }
     
    /**
     * set moderator
	 *
     * @param int $moderator moderator
	 * @return void
     */
    public function setModerator($moderator)
	{
        $this->moderator = $moderator;
    }

	/**
     * get hash
	 *
     * @return string $hash
     */
    public function getHash()
	{
       return md5($this->uid.$GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'].$this->crdate);
    }
}
?>