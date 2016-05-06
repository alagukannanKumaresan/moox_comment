<?php
namespace DCNGmbH\MooxComment\Service;

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

use TYPO3\CMS\Core\SingletonInterface;
 
class AccessControlService implements SingletonInterface
{	
	/**
	 * objectManager
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 * @inject
	 */
	protected $objectManager;	
	
	/**
	 * frontendUserRepository
	 *
	 * @var \DCNGmbH\MooxComment\Domain\Repository\FrontendUserRepository
	 * @inject
	 */
	protected $frontendUserRepository;
	
	/**
     * Do we have a logged in feuser
     *
	 * @return bool
     */
    public function hasLoggedInFrontendUser()
	{		
        return ($GLOBALS['TSFE']->loginUser == 1) ? TRUE : FALSE;
    }
 
    /**
     * Get the uid of the current feuser
     *
	 * @return mixed
     */
    public function getFrontendUserUid()
	{
        if ($this->hasLoggedInFrontendUser() && !empty($GLOBALS['TSFE']->fe_user->user['uid'])) {
            return intval($GLOBALS['TSFE']->fe_user->user['uid']);
        }
        return NULL;
    }	
}
?>