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
    public function __construct($startPath = __DIR__, $configFile = null)
    {
        $this->startPath = $startPath;
        if (is_null($configFile)) {
            $this->configReader = new ConfigFile();
        }
        else {
            $this->configReader = $configFile;
        }
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
     * @return string Path to ``out`` directory.
     */
    public function getOutPath()
    {
        return Path::join($this->getSourcePath(), 'out');
    }

    /**
     * @return string Eshop edition as capital two letters code.
     */
    public function getEdition()
    {
        $editionSelector = new EditionSelector();

        return $editionSelector->getEdition();
    }

    public function getDatabaseName()
    {
        return $this->configReader->dbName;
    }

    public function getDatabaseUserName()
    {
        return $this->configReader->dbUser;
    }

    public function getDatabasePassword()
    {
        return $this->configReader->dbPwd;
    }

    public function getDatabaseHost()
    {
        return $this->configReader->dbHost;
    }

    public function getDatabaseDriver()
    {
        return $this->configReader->dbType;
    }

    public function getMigrationPaths()
    {
        $editionSelector = new EditionSelector();

        $migrationPaths = [
            'ce' => $this->configReader->getVar(ConfigFile::PARAMETER_SOURCE_PATH).'/migration/migrations.yml',
        ];

        if ($editionSelector->isProfessional() || $editionSelector->isEnterprise()) {
            $migrationPaths['pe'] = $this->configReader->getVar(ConfigFile::PARAMETER_VENDOR_PATH)
                                    . '/oxid-esales/oxideshop-pe/migration/migrations.yml';
        }

        if ($editionSelector->isEnterprise()) {
            $migrationPaths['ee'] = $this->configReader->getVar(ConfigFile::PARAMETER_VENDOR_PATH)
                                    .'/oxid-esales/oxideshop-ee/migration/migrations.yml';
        }

        $migrationPaths['pr'] = $this->configReader->getVar(ConfigFile::PARAMETER_SOURCE_PATH)
                                    . '/migration/project_migrations.yml';

        return $migrationPaths;
    }
}
