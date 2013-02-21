<?php

namespace NS\AdminBundle\Service;

/**
 * Admin service
 *
 */
class AdminService
{
	/**
	 * @var string[]
	 */
	private $bundles;

	/**
	 * @param string[] $bundles
	 */
	public function __construct(array $bundles)
	{
		$this->bundles = $bundles;
	}

	/**
	 * Checks if bundle is active
	 *
	 * @param string $name
	 * @return bool
	 */
	public function isBundleActive($name)
	{
		return in_array($name, $this->bundles);
	}
}
