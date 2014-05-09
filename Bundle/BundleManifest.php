<?php

namespace NS\AdminBundle\Bundle;

/**
 * Class BundleManifest
 *
 * @package NS\AdminBundle\Bundle
 */
class BundleManifest
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var boolean
     */
    private $system = false;

    /**
     * @var bool
     */
    private $alwaysActive = false;

    /**
     * @var string
     */
    private $iconSvg = 'bundles/nsadmin/ns_admin/bundle-default.svg';

    /**
     * @param boolean $system
     */
    public function setSystem($system)
    {
        $this->system = $system;
    }

    /**
     * @return boolean
     */
    public function isSystem()
    {
        return $this->system;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param boolean $alwaysActive
     */
    public function setAlwaysActive($alwaysActive)
    {
        $this->alwaysActive = $alwaysActive;
    }

    /**
     * @return boolean
     */
    public function isAlwaysActive()
    {
        return $this->alwaysActive;
    }

    /**
     * @param string $iconSvg
     */
    public function setIconSvg($iconSvg)
    {
        $this->iconSvg = $iconSvg;
    }

    /**
     * @return string
     */
    public function getIconSvg()
    {
        return $this->iconSvg;
    }
}