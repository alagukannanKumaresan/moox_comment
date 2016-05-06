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
 
class Template extends AbstractEntity
{
	
	/**
	 * Titel
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $title;
	
	/**
	 * Mail-Betreff
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $subject;
	
	/**
	 * Kategorie
	 *
	 * @var string
	 */
	protected $category;
	
	/**
	 * Vorlage
	 *
	 * @var string
	 */
	protected $template;	
	
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
	 * Returns the subject
	 *
	 * @return string $subject
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * Sets the subject
	 *
	 * @param string $subject
	 * @return void
	 */
	public function setSubject($subject)
	{
		$this->subject = $subject;
	}
	
	/**
	 * Returns the category
	 *
	 * @return string $category
	 */
	public function getCategory()
	{
		return $this->category;
	}

	/**
	 * Sets the category
	 *
	 * @param string $category
	 * @return void
	 */
	public function setCategory($category)
	{
		$this->category = $category;
	}
	
	/**
	 * Returns the template
	 *
	 * @return string $template
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 * Sets the template
	 *
	 * @param string $template
	 * @return void
	 */
	public function setTemplate($template)
	{
		$this->template = $template;
	}
}
?>