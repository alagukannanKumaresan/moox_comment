<?php
namespace DCNGmbH\MooxComment\ViewHelpers\Widget;

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

use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper;
 
class PaginateViewHelper extends AbstractWidgetViewHelper
{
	/**
	 * controller
	 *
	 * @var DCNGmbH\MooxComment\ViewHelpers\Widget\Controller\PaginateController
	 * @inject
	 */
	protected $controller;

	/**
	 * Render everything
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryResultInterface $objects
	 * @param string $as
	 * @param mixed $configuration
	 * @param array $initial
	 * @internal param array $initial
	 * @return string
	 */
	public function render(\TYPO3\CMS\Extbase\Persistence\QueryResultInterface $objects, $as, $configuration = array('itemsPerPage' => 10, 'insertAbove' => FALSE, 'insertBelow' => TRUE), $initial = array())
	{
		return $this->initiateSubRequest();
	}
}
