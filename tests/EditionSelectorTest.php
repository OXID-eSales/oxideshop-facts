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

use OxidEsales\Facts\Edition\EditionSelector;

class EditionSelectorTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsEditionFromConfig()
    {
        $config = $this->getConfigStub('CE');

        $editionSelector = new EditionSelector($config);

        $this->assertSame('CE', $editionSelector->getEdition());
    }

    public function providerGetCommunityEdition()
    {
        return [
            ['CE'],
            ['ce'],
            ['cE'],
            ['Ce'],
        ];
    }

    /**
     * Test that returns edition independent from camel case.
     *
     * @param string $edition
     *
     * @dataProvider providerGetCommunityEdition
     */
    public function testForcingEditionIsCaseInsensitive($edition)
    {
        $config = $this->getConfigStub($edition);

        $editionSelector = new EditionSelector($config);

        $this->assertSame('CE', $editionSelector->getEdition());
        $this->assertTrue($editionSelector->isCommunity());
        $this->assertFalse($editionSelector->isProfessional());
        $this->assertFalse($editionSelector->isEnterprise());
    }

    /**
     * Test that returns community edition independent from camel case.
     *
     * @param string $edition
     *
     * @dataProvider providerGetCommunityEdition
     */
    public function testGetCommunityEdition($edition)
    {
        $config = $this->getConfigStub($edition);

        $editionSelector = new EditionSelector($config);

        $this->assertTrue($editionSelector->isCommunity());
        $this->assertFalse($editionSelector->isProfessional());
        $this->assertFalse($editionSelector->isEnterprise());
    }

    public function providerGetProfessionalEdition()
    {
        return [
            ['PE'],
            ['pe'],
            ['pE'],
            ['Pe'],
        ];
    }

    /**
     * Test that returns professional edition independent from camel case.
     *
     * @param string $edition
     *
     * @dataProvider providerGetProfessionalEdition
     */
    public function testGetProfessionalEdition($edition)
    {
        $config = $this->getConfigStub($edition);

        $editionSelector = new EditionSelector($config);

        $this->assertFalse($editionSelector->isCommunity());
        $this->assertTrue($editionSelector->isProfessional());
        $this->assertFalse($editionSelector->isEnterprise());
    }

    public function providerGetEnterpriseEdition()
    {
        return [
            ['EE'],
            ['Ee'],
            ['eE'],
            ['Ee'],
        ];
    }

    /**
     * Test that returns professional edition independent from camel case.
     *
     * @param string $edition
     *
     * @dataProvider providerGetEnterpriseEdition
     */
    public function testGetEnterpriseEdition($edition)
    {
        $config = $this->getConfigStub($edition);

        $editionSelector = new EditionSelector($config);

        $this->assertFalse($editionSelector->isCommunity());
        $this->assertFalse($editionSelector->isProfessional());
        $this->assertTrue($editionSelector->isEnterprise());
    }

    /**
     * Creates a stub for config file.
     * Allows to force an edition.
     *
     * @param string $edition Edition name to return, etc. 'CE'
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getConfigStub($edition)
    {
        $config = $this->getMock('ConfigFile', ['getVar']);
        $config->method('getVar')->will($this->returnValue($edition));

        return $config;
    }
}
