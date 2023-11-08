<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\Facts\Tests\Unit;

use OxidEsales\Facts\Config\ConfigFile;
use OxidEsales\Facts\Edition\EditionSelector;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class EditionSelectorTest extends TestCase
{
    public function testReturnsEditionFromConfig(): void
    {
        $config = $this->getConfigStub('CE');

        $editionSelector = new EditionSelector($config);

        $this->assertSame('CE', $editionSelector->getEdition());
    }

    public static function providerGetCommunityEdition(): array
    {
        return [
            ['CE'],
            ['ce'],
            ['cE'],
            ['Ce'],
        ];
    }

    /**
     * @dataProvider providerGetCommunityEdition
     */
    public function testForcingEditionIsCaseInsensitive($edition): void
    {
        $config = $this->getConfigStub($edition);

        $editionSelector = new EditionSelector($config);

        $this->assertSame('CE', $editionSelector->getEdition());
        $this->assertTrue($editionSelector->isCommunity());
        $this->assertFalse($editionSelector->isProfessional());
        $this->assertFalse($editionSelector->isEnterprise());
    }

    /**
     * @dataProvider providerGetCommunityEdition
     */
    public function testGetCommunityEdition($edition): void
    {
        $config = $this->getConfigStub($edition);

        $editionSelector = new EditionSelector($config);

        $this->assertTrue($editionSelector->isCommunity());
        $this->assertFalse($editionSelector->isProfessional());
        $this->assertFalse($editionSelector->isEnterprise());
    }

    public static function providerGetProfessionalEdition(): array
    {
        return [
            ['PE'],
            ['pe'],
            ['pE'],
            ['Pe'],
        ];
    }

    /**
     * @dataProvider providerGetProfessionalEdition
     */
    public function testGetProfessionalEdition($edition): void
    {
        $config = $this->getConfigStub($edition);

        $editionSelector = new EditionSelector($config);

        $this->assertFalse($editionSelector->isCommunity());
        $this->assertTrue($editionSelector->isProfessional());
        $this->assertFalse($editionSelector->isEnterprise());
    }

    public static function providerGetEnterpriseEdition(): array
    {
        return [
            ['EE'],
            ['Ee'],
            ['eE'],
            ['Ee'],
        ];
    }

    /**
     * @dataProvider providerGetEnterpriseEdition
     */
    public function testGetEnterpriseEdition($edition): void
    {
        $config = $this->getConfigStub($edition);

        $editionSelector = new EditionSelector($config);

        $this->assertFalse($editionSelector->isCommunity());
        $this->assertFalse($editionSelector->isProfessional());
        $this->assertTrue($editionSelector->isEnterprise());
    }

    private function getConfigStub($edition): MockObject
    {
        return $this->createConfiguredMock(ConfigFile::class, ['getVar' => $edition]);
    }
}
