<?php

namespace NS\AdminBundle\Service;

use NS\AdminBundle\Bundle\BundleManifest;
use NS\CoreBundle\Bundle\CoreBundle;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Yaml\Yaml;

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
	 * @return BundleInterface[]
	 */
	public function getActiveBundles()
	{
        $bundles = array();
        foreach ($this->kernel->getBundles() as $bundle) {
            $manifest = $this->getBundleManifest($bundle);
            if ($manifest->isAlwaysActive() || in_array($bundle->getName(), $this->bundles)) {
                $bundles[] = $bundle;
            }
        }
        return $bundles;
	}

	/**
	 * Transforms admin route params to controller name
	 *
	 * @param string $adminBundle
	 * @param string $adminController
	 * @param string $adminAction
	 * @return string
	 */
	public function getAdminRouteController($adminBundle, $adminController, $adminAction)
	{
		$adminController = 'Admin' . ucfirst($adminController);
		return "{$adminBundle}:{$adminController}:{$adminAction}";
	}

    /**
     * Retrieves only engine bundles
     *
     * @return CoreBundle[]|BundleInterface[]
     */
    public function getAvailableBundles()
    {
        return array_filter($this->kernel->getBundles(), function(BundleInterface $bundle){
            return $bundle instanceof CoreBundle;
        });
    }

    /**
     * Retrieves bundle manifest
     *
     * @param BundleInterface $bundle
     * @return BundleManifest
     */
    public function getBundleManifest(BundleInterface $bundle)
    {
        $fileName = $bundle->getPath() . '/Resources/config/ns_admin.manifest.yml';
        if (file_exists($fileName)) {
            $yml = Yaml::parse(file_get_contents($fileName));
            $normalizer = new GetSetMethodNormalizer();
            return $normalizer->denormalize($yml, 'NS\AdminBundle\Bundle\BundleManifest');
        }
        return new BundleManifest();
    }

    /**
     * @return BundleInterface[]
     */
    public function getSystemBundles()
    {
        $bundles = array();
        $titles = array();
        foreach ($this->getAvailableBundles() as $bundle) {
            $manifest = $this->getBundleManifest($bundle);
            if ($manifest->isSystem()) {
                $bundles[] = $bundle;
                $titles[$bundle->getName()] = $manifest->getTitle();
            }
        }
        usort($bundles, function(BundleInterface $a, BundleInterface $b) use($titles){
            return strnatcasecmp($titles[$a->getName()], $titles[$b->getName()]);
        });
        return $bundles;
    }

    /**
     * @return BundleInterface[]
     */
    public function getUserBundles()
    {
        $bundles = array();
        $titles = array();
        foreach ($this->getAvailableBundles() as $bundle) {
            $manifest = $this->getBundleManifest($bundle);
            if (!$manifest->isSystem()) {
                $bundles[] = $bundle;
                $titles[$bundle->getName()] = $manifest->getTitle();
            }
        }
        usort($bundles, function(BundleInterface $a, BundleInterface $b) use($titles){
            return strnatcasecmp($titles[$a->getName()], $titles[$b->getName()]);
        });
        return $bundles;
    }
}
