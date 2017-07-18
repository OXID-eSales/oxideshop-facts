<?php
/**
 * This file is part of OXID eSales OXID eShop Facts.
 *
 * OXID eSales OXID eShop Facts is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eSales OXID eShop Facts is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eSales OXID eShop Facts. If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2017
 */

namespace OxidEsales\Facts;

use OxidEsales\Facts\Config\ConfigFile;
use OxidEsales\Facts\Edition\EditionSelector;
use Webmozart\PathUtil\Path;

/**
 * Class responsible to return information about OXID eShop.
 * Could be used without shop bootstrap
 * for example before setup of a shop.
 */
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
     * @var null | ConfigFile
     */
    protected $configReader = null;

    /**
     * Facts constructor.
     *
     * @param string $startPath               Start path.
     * @param null   $configFile              Optional ConfigFile
     */
    public function __construct($startPath = __DIR__, $configFile = null)
    {
        $this->startPath = $startPath;
        $this->configReader = $configFile;
    }

    /**
     * @return string Root path of shop.
     */
    public function getShopRootPath()
    {
        $vendorPaths = [
            '/vendor',
            '/../vendor',
            '/../../vendor',
            '/../../../vendor',
            '/../../../../vendor',
        ];

        $rootPath = '';
        foreach ($vendorPaths as $vendorPath) {
            if (file_exists(Path::join($this->startPath, $vendorPath))) {
                $rootPath = Path::join($this->startPath, $vendorPath, '..');
                break;
            }
        }

        return $rootPath;
    }

    /**
     * @return string Path to vendor directory.
     */
    public function getVendorPath()
    {
        return Path::join($this->getShopRootPath(), 'vendor');
    }

    /**
     * @return string Path to source directory.
     */
    public function getSourcePath()
    {
        return Path::join($this->getShopRootPath(), 'source');
    }

    /**
     * @return string Path to source directory.
     */
    public function getCommunityEditionSourcePath()
    {
        $vendorPath = $this->getVendorPath();

        if ($this->isProjectEshopInstallation()) {
            $communityEditionSourcePath = Path::join($vendorPath, self::COMPOSER_VENDOR_OXID_ESALES, self::COMPOSER_PACKAGE_OXIDESHOP_CE, 'source');
        } else {
            $communityEditionSourcePath = $this->getSourcePath();
        }

        return $communityEditionSourcePath;
    }

    /**
     * @return string Path to ``out`` directory.
     */
    public function getOutPath()
    {
        return Path::join($this->getSourcePath(), 'out');
    }

    /**
     * @throws \Exception
     *
     * @return string Eshop edition as capital two letters code.
     */
    public function getEdition()
    {
        try {
            $editionSelector = new EditionSelector();
            $edition = $editionSelector->getEdition();
        } catch (\Exception $exception) {
            if ($exception->getCode() == ConfigFile::ERROR_CODE_CONFIGFILE_NOT_FOUND) {
                $edition = EditionSelector::findEditionByClassMap();
            } else {
                throw $exception;
            }
        }

        return $edition;
    }

    /**
     * @return mixed
     */
    public function getDatabaseName()
    {
        return $this->getConfigReader()->dbName;
    }

    /**
     * @return mixed
     */
    public function getDatabaseUserName()
    {
        return $this->getConfigReader()->dbUser;
    }

    /**
     * @return mixed
     */
    public function getDatabasePassword()
    {
        return $this->getConfigReader()->dbPwd;
    }

    /**
     * @return mixed
     */
    public function getDatabaseHost()
    {
        return $this->getConfigReader()->dbHost;
    }

    /**
     * @return mixed
     */
    public function getDatabaseDriver()
    {
        return $this->getConfigReader()->dbType;
    }

    /**
     * @return array
     */
    public function getMigrationPaths()
    {
        $editionSelector = new EditionSelector();

        $migrationPaths = [
            'ce' => $this->getConfigReader()->getVar(ConfigFile::PARAMETER_SOURCE_PATH).'/migration/migrations.yml',
        ];

        if ($editionSelector->isProfessional() || $editionSelector->isEnterprise()) {
            $migrationPaths['pe'] = $this->getConfigReader()->getVar(ConfigFile::PARAMETER_VENDOR_PATH)
                                    . '/' . self::COMPOSER_VENDOR_OXID_ESALES . '/oxideshop-pe/migration/migrations.yml';
        }

        if ($editionSelector->isEnterprise()) {
            $migrationPaths['ee'] = $this->getConfigReader()->getVar(ConfigFile::PARAMETER_VENDOR_PATH)
                                    .'/' . self::COMPOSER_VENDOR_OXID_ESALES . '/oxideshop-ee/migration/migrations.yml';
        }

        $migrationPaths['pr'] = $this->getConfigReader()->getVar(ConfigFile::PARAMETER_SOURCE_PATH)
                                    . '/migration/project_migrations.yml';

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
        $vendorCommunityEditionPath = Path::join($this->getVendorPath(), self::COMPOSER_VENDOR_OXID_ESALES, self::COMPOSER_PACKAGE_OXIDESHOP_CE);

        return is_dir($vendorCommunityEditionPath);
    }
}
