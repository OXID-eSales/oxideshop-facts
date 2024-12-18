<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\Facts;

use OxidEsales\EshopCommunity\Internal\Framework\Database\Configuration\DataObject\DatabaseConfiguration;
use OxidEsales\EshopCommunity\Internal\Framework\Edition\Edition;
use OxidEsales\EshopCommunity\Internal\Framework\Edition\EditionDirectoriesLocator;
use OxidEsales\EshopCommunity\Internal\Framework\Env\DotenvLoader;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\BasicContext;
use OxidEsales\Facts\Config\ConfigFile;
use OxidEsales\Facts\Edition\EditionSelector;
use Symfony\Component\Filesystem\Path;

/**
 * Class responsible to return information about OXID eShop.
 * Could be used without shop bootstrap
 * for example before setup of a shop.
 */
#[\AllowDynamicProperties]
class Facts
{
    /**
     * @var string The composer vendor name of the OXID eSales AG.
     */
    const COMPOSER_VENDOR_OXID_ESALES = 'oxid-esales';

    /**
     * @var string The composer package name of the OXID eShop Community Edition.
     */
    const COMPOSER_PACKAGE_OXIDESHOP_CE = 'oxideshop-ce';

    /**
     * @var string The composer package name of the OXID eShop Professional Edition.
     */
    const COMPOSER_PACKAGE_OXIDESHOP_PE = 'oxideshop-pe';

    /**
     * @var string The composer package name of the OXID eShop Enterprise Edition.
     */
    const COMPOSER_PACKAGE_OXIDESHOP_EE = 'oxideshop-ee';

    /**
     * @var null | ConfigFile
     */
    protected $configReader = null;

    protected string $startPath;

    private DatabaseConfiguration $databaseConfiguration;

    private BasicContext $context;

    private EditionSelector $editionSelector;

    /**
     * Facts constructor.
     *
     * @param string $startPath Start path.
     * @param null   $configFile Optional ConfigFile
     */
    public function __construct($startPath = __DIR__, $configFile = null)
    {
        $this->startPath = $startPath;
        $this->configReader = $configFile;

        $this->context = new BasicContext();

        $this->loadEnvironmentVariables();

        $this->databaseConfiguration = (new DatabaseConfiguration($this->context->getDatabaseUrl()));
        $this->editionSelector = new EditionSelector();
    }

    /**
     * @return string Root path of shop.
     */
    public function getShopRootPath()
    {
        return $this->context->getShopRootPath();
    }

    /**
     * @return string Path to vendor directory.
     */
    public function getVendorPath()
    {
        return $this->context->getVendorPath();
    }

    /**
     * @return string Path to source directory.
     */
    public function getSourcePath()
    {
        return $this->context->getSourcePath();
    }

    /**
     * @return string Path to source directory.
     */
    public function getCommunityEditionSourcePath()
    {
        $vendorPath = $this->getVendorPath();

        if ($this->isProjectEshopInstallation()) {
            $communityEditionSourcePath =
                Path::join(
                    $vendorPath,
                    self::COMPOSER_VENDOR_OXID_ESALES,
                    self::COMPOSER_PACKAGE_OXIDESHOP_CE,
                    'source'
                );
        } else {
            $communityEditionSourcePath = $this->getSourcePath();
        }

        return $communityEditionSourcePath;
    }

    /**
     * @return string
     */
    public function getCommunityEditionRootPath()
    {
        return (new EditionDirectoriesLocator)->getEditionRootPath(Edition::Community);
    }

    /**
     * @return string
     */
    public function getProfessionalEditionRootPath()
    {
        $vendorPath = $this->getVendorPath();

        return Path::join($vendorPath, self::COMPOSER_VENDOR_OXID_ESALES, self::COMPOSER_PACKAGE_OXIDESHOP_PE);
    }

    /**
     * @return string
     */
    public function getEnterpriseEditionRootPath()
    {
        $vendorPath = $this->getVendorPath();

        return Path::join($vendorPath, self::COMPOSER_VENDOR_OXID_ESALES, self::COMPOSER_PACKAGE_OXIDESHOP_EE);
    }

    /**
     * @return string Path to ``out`` directory.
     */
    public function getOutPath()
    {
        return $this->context->getOutPath();
    }

    /**
     * @return string Eshop edition as capital two letters code.
     */
    public function getEdition()
    {
        return $this->editionSelector->getEdition();
    }

    /**
     * @return bool
     */
    public function isEnterprise()
    {
        return $this->editionSelector->isEnterprise();
    }

    /**
     * @return bool
     */
    public function isProfessional()
    {
        return $this->editionSelector->isProfessional();
    }

    /**
     * @return bool
     */
    public function isCommunity()
    {
        return $this->editionSelector->isCommunity();
    }

    /**
     * @return mixed
     */
    public function getDatabaseName()
    {

        return $this->databaseConfiguration->getName();
    }

    /**
     * @return mixed
     */
    public function getDatabaseUserName()
    {
        return $this->databaseConfiguration->getUser();
    }

    /**
     * @return mixed
     */
    public function getDatabasePassword()
    {
        return $this->databaseConfiguration->getPass();
    }

    /**
     * @return mixed
     */
    public function getDatabaseHost()
    {
        return $this->databaseConfiguration->getHost();
    }

    /**
     * @return mixed
     */
    public function getDatabasePort()
    {
        return $this->databaseConfiguration->getPort();
    }

    /**
     * @return string
     */
    public function getShopUrl()
    {
        return $this->context->getShopBaseUrl();
    }

    /**
     * @return array
     *
     * @deprecated this method will be remove in next major version
     * and it will moved to doctrine-migration-wrapper component
     */
    public function getMigrationPaths(): array
    {
        $editionSelector = new EditionSelector();

        $migrationPaths = [
            'ce' => $this->getSourcePath() . '/migration/migrations.yml',
            'pr' => $this->getSourcePath() . '/migration/migrations.yml',
        ];

        if ($editionSelector->isProfessional() || $editionSelector->isEnterprise()) {
            $migrationPaths['pe'] = $this->getProfessionalEditionRootPath() . '/migration/migrations.yml';
        }

        if ($editionSelector->isEnterprise()) {
            $migrationPaths['ee'] = $this->getEnterpriseEditionRootPath() . '/migration/migrations.yml';
        }

        return $migrationPaths;
    }

    /**
     * Safeguard for ConfigFile object.
     *
     * @return ConfigFile
     */
    protected function getConfigReader()
    {
        if (is_null($this->configReader)) {
            $this->configReader = new ConfigFile();
        }
        return $this->configReader;
    }

    /**
     * Determine, if the given OXID eShop is a project installation.
     *
     * @return bool Is the given OXID eShop installation a poject installation?
     */
    private function isProjectEshopInstallation()
    {
        $vendorCommunityEditionPath =
            Path::join(
                $this->getVendorPath(),
                self::COMPOSER_VENDOR_OXID_ESALES,
                self::COMPOSER_PACKAGE_OXIDESHOP_CE
            );

        return is_dir($vendorCommunityEditionPath);
    }

    private function loadEnvironmentVariables(): void
    {
        (new DotenvLoader($this->getProjectRoot()))->loadEnvironmentVariables();
    }

    private function getProjectRoot(): string
    {
        return $this->context->getShopRootPath();
    }
}
