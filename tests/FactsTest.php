<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\Facts\Tests\Unit;

use OxidEsales\EshopCommunity\Internal\Framework\Database\Configuration\DataObject\DatabaseConfiguration;
use OxidEsales\EshopCommunity\Internal\Framework\Edition\Edition;
use OxidEsales\EshopCommunity\Internal\Framework\Edition\EditionDirectoriesLocator;
use OxidEsales\EshopCommunity\Internal\Framework\Edition\EditionPaths;
use OxidEsales\EshopCommunity\Internal\Framework\FileSystem\ProjectDirectoriesLocator;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\BasicContext;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\BasicContextInterface;
use OxidEsales\Facts\Facts;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Path;

final class FactsTest extends TestCase
{
    private Facts $facts;
    private BasicContextInterface $context;

    public function setUp(): void
    {
        parent::setUp();

        $this->facts = new Facts();
        $this->context = new BasicContext();
    }

    public function testGetShopRootPath(): void
    {
        $expectedRoot = $this->context->getShopRootPath();
        $this->assertSame($expectedRoot, $this->facts->getShopRootPath());
    }

    public function testGetVendorPath(): void
    {
        $expectedVendor = $this->context->getVendorPath();
        $this->assertEquals($expectedVendor, $this->facts->getVendorPath());
    }

    public function testGetSourcePath(): void
    {
        $expectedSource = $this->context->getSourcePath();
        $this->assertEquals($expectedSource, $this->facts->getSourcePath());
    }

    public function testGetCommunityEditionSourcePathNormalInstallation(): void
    {
        $this->assertEquals(
            $this->context->getEditionSourcePath(Edition::Community),
            $this->facts->getCommunityEditionSourcePath()
        );
    }

    public function testGetProfessionalEditionSourcePathNormalInstallation(): void
    {
        $projectDirectoriesLocator = new ProjectDirectoriesLocator();

        $professionalEdition = Edition::Professional;
        $path = Path::join(
            $projectDirectoriesLocator->getVendorPath(),
            EditionPaths::from($professionalEdition->value)->getVendorFolderName(),
            EditionPaths::from($professionalEdition->value)->getProjectFolderName(),
        );

        $this->assertEquals(
            $path,
            $this->facts->getProfessionalEditionRootPath()
        );
    }

    public function testGetCommunityEditionRootPathNormalInstallation(): void
    {
        $path = (new EditionDirectoriesLocator())->getEditionRootPath(Edition::Community);

        $this->assertEquals(
            $path,
            $this->facts->getCommunityEditionRootPath()
        );
    }

    public function testGetOutPath(): void
    {
        $this->assertEquals(
            $this->context->getOutPath(),
            $this->facts->getOutPath()
        );
    }

    public function testGetEdition(): void
    {
        $this->assertEquals(
            $this->context->getEdition()->value,
            $this->facts->getEdition()
        );
    }
}
