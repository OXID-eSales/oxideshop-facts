<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\Facts\Tests\Unit;

use OxidEsales\Facts\Config\ConfigFile;
use OxidEsales\Facts\Edition\EditionSelector;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Stub;
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

    #[DataProvider('providerGetCommunityEdition')]
    public function testForcingEditionIsCaseInsensitive(string $edition): void
    {
        $config = $this->getConfigStub($edition);

        $editionSelector = new EditionSelector($config);

        $this->assertSame('CE', $editionSelector->getEdition());
        $this->assertTrue($editionSelector->isCommunity());
        $this->assertFalse($editionSelector->isProfessional());
        $this->assertFalse($editionSelector->isEnterprise());
    }

    #[DataProvider('providerGetCommunityEdition')]
    public function testGetCommunityEdition(string $edition): void
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

    #[DataProvider('providerGetProfessionalEdition')]
    public function testGetProfessionalEdition(string $edition): void
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

    #[DataProvider('providerGetEnterpriseEdition')]
    public function testGetEnterpriseEdition(string $edition): void
    {
        $config = $this->getConfigStub($edition);

        $editionSelector = new EditionSelector($config);

        $this->assertFalse($editionSelector->isCommunity());
        $this->assertFalse($editionSelector->isProfessional());
        $this->assertTrue($editionSelector->isEnterprise());
    }

    private function getConfigStub($edition): Stub
    {
        $stub = $this->createStub(ConfigFile::class);
        $stub->method('getVar')->willReturn($edition);

        return $stub;
    }
}
