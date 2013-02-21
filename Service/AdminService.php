<?php

namespace NS\AdminBundle\Service;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Admin service
 *
 */
final class AdminService
{
	/**
	 * @var string[]
	 */
	private $bundles;

	/**
	 * @var Kernel
	 */
	private $kernel;

	/**
	 * @param string[] $bundles
	 * @param Kernel   $kernel
	 */
	public function __construct(array $bundles, Kernel $kernel)
	{
		$this->bundles = $bundles;
		$this->kernel  = $kernel;
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

	/**
	 * Retrieves active bundles
	 *
	 * @return Bundle[]
	 */
	public function getActiveBundles()
	{
		$bundles = $this->bundles;

		return array_filter($this->kernel->getBundles(), function(Bundle $bundle) use($bundles){
			return in_array($bundle->getName(), $bundles);
		});
	}
}
