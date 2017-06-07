<?php
/**
 * This file is part of OXID eSales Demo Data Installer.
 *
 * OXID eSales Demo Data Installer is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eSales Demo Data Installer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eSales Demo Data Installer.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2017
 */

namespace OxidEsales\DemoDataInstaller\Tests\Unit;

use org\bovigo\vfs\vfsStream;
use OxidEsales\Facts\Facts;

class FactsTest extends \PHPUnit_Framework_TestCase
{
    public function testGetShopRoot()
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

        $expectedSource = vfsStream::url('root/oxideshop_ce/source');
        $this->assertEquals($expectedSource, $facts->getSourcePath());
    }

    private function buildFacts()
    {
        $structure = [
            'oxideshop_ce' => [
                'source' => [
                    'Core' => [],
                    'Application' => []
                ],
                'vendor' => [
                    'bin' => [],
                    'oxid-esales' => [
                        'oxideshop-facts' => [
                            'bin' => [],
                            'src' => []
                        ]
                    ]
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
}
