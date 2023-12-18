<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\Facts\Tests\Unit;

use org\bovigo\vfs\vfsStream;
use OxidEsales\Facts\Config\ConfigFile;
use OxidEsales\Facts\Facts;
use PHPUnit\Framework\TestCase;

class FactsTest extends TestCase
{
    public function testGetShopRootPath(): void
    {
        $facts = $this->buildFacts();

        $expectedRoot = vfsStream::url('root/oxideshop_ce');
        $this->assertSame($expectedRoot, $facts->getShopRootPath());
    }

    public function testGetVendorPath(): void
    {
        $facts = $this->buildFacts();

        $expectedVendor = vfsStream::url('root/oxideshop_ce/vendor');
        $this->assertEquals($expectedVendor, $facts->getVendorPath());
    }

    public function testGetSourcePath(): void
    {
        $facts = $this->buildFacts();

        $expectedSource = $this->getShopSourcePath();
        $this->assertEquals($expectedSource, $facts->getSourcePath());
    }

    public function testGetCommunityEditionSourcePathNormalInstallation(): void
    {
        $facts = $this->buildFacts();

        $this->assertEquals($this->getShopSourcePath(), $facts->getCommunityEditionSourcePath());
    }

    public function testGetCommunityEditionSourcePathProjectInstallation(): void
    {
        $facts = $this->buildFacts(true);

        $this->assertEquals($this->getProjectShopSourcePath(), $facts->getCommunityEditionSourcePath());
    }

    private function buildFacts($isProjectInstallation = false): Facts
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

        $configFile = $this->createMock(ConfigFile::class);

        return new Facts($__DIR__stub, $configFile);
    }

    private function getShopSourcePath(): string
    {
        return vfsStream::url('root/oxideshop_ce/source');
    }

    private function getProjectShopSourcePath(): string
    {
        return vfsStream::url('root/oxideshop_ce/vendor/oxid-esales/oxideshop-ce/source');
    }
}
