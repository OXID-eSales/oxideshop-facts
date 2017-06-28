<?php
/**
 * This file is part of OXID eSales Facts.
 *
 * OXID eSales Facts is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eSales Facts is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eSales Facts.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2017
 */

namespace OxidEsales\Facts\Tests\Unit;

use org\bovigo\vfs\vfsStream;
use OxidEsales\Facts\Facts;

class FactsTest extends \PHPUnit_Framework_TestCase
{
    public function testGetShopRootPath()
    {
        $facts = $this->buildFacts();

        $expectedRoot = vfsStream::url('root/oxideshop_ce');
        $this->assertSame($expectedRoot, $facts->getShopRootPath());
    }

    public function testGetVendorPath()
    {
        $facts = $this->buildFacts();

        $expectedVendor = vfsStream::url('root/oxideshop_ce/vendor');
        $this->assertEquals($expectedVendor, $facts->getVendorPath());
    }

    public function testGetSourcePath()
    {
        $facts = $this->buildFacts();

        $expectedSource = $this->getShopSourcePath();
        $this->assertEquals($expectedSource, $facts->getSourcePath());
    }

    public function testGetCommunityEditionSourcePathNormalInstallation()
    {
        $facts = $this->buildFacts();

        $this->assertEquals($this->getShopSourcePath(), $facts->getCommunityEditionSourcePath());
    }

    public function testGetCommunityEditionSourcePathProjectInstallation()
    {
        $facts = $this->buildFacts(true);

        $this->assertEquals($this->getProjectShopSourcePath(), $facts->getCommunityEditionSourcePath());
    }

    private function buildFacts($isProjectInstallation = false)
    {

        $vendorOxidesaleDirectory = [
            'oxideshop-facts' => [
                'bin' => [],
                'src' => []
            ]
        ];
        if ($isProjectInstallation) {
            $vendorOxidesaleDirectory['oxideshop-ce'] = [];
        }

        $structure = [
            'oxideshop_ce' => [
                'source' => [
                    'Core' => [],
                    'Application' => []
                ],
                'vendor' => [
                    'bin' => [],
                    'oxid-esales' => $vendorOxidesaleDirectory
                ]
            ],
            'vendor' => []
        ];

        vfsStream::setup('root', null, $structure);
        $root = vfsStream::url('root');

        $__DIR__stub = $root . '/oxideshop_ce/vendor/oxid-esales/oxideshop-facts/src';

        $configFile = $this->getMock('ConfigFile');

        $facts = new Facts($__DIR__stub, $configFile);

        return $facts;
    }

    /**
     * Get the path to the OXID eShop source directory.
     *
     * @return string The path to the OXID eShop source directory.
     */
    private function getShopSourcePath()
    {
        return vfsStream::url('root/oxideshop_ce/source');
    }

    /**
     * Get the path to the OXID eShop source directory.
     *
     * @return string The path to the OXID eShop source directory.
     */
    private function getProjectShopSourcePath()
    {
        return vfsStream::url('root/oxideshop_ce/vendor/oxid-esales/oxideshop-ce/source');
    }
}
