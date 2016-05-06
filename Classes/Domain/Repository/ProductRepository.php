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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
 
if (ExtensionManagementUtility::isLoaded('moox_shop') && class_exists('\DCNGmbH\MooxShop\Domain\Repository\ProductRepository')) {
	class ProductRepository extends \DCNGmbH\MooxShop\Domain\Repository\ProductRepository 
	{
			
	}
}
?>