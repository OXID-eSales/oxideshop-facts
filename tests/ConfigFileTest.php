<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\Facts\Tests\Unit;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Filesystem\Filesystem;
use OxidEsales\Facts\Config\ConfigFile;

final class ConfigFileTest extends TestCase
{
    private string $temporaryPath;
    private string $vendorPath;

    protected function setUp(): void
    {
        $this->temporaryPath = Path::join(__DIR__, 'tmp');
        $this->vendorPath = Path::join(__DIR__, 'tmp', 'testData');
        $this->buildDirectory();
    }

    protected function tearDown(): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->temporaryPath);
    }

    public function testIncludeAndParseConfigFile(): void
    {
        $configFile = new ConfigFile(Path::join($this->vendorPath, 'config.inc.php'));

        $this->assertSame('test', $configFile->getVar('dbName'));
    }

    private function buildDirectory(): void
    {
        $structure = [
            'config.inc.php' => '<?php $this->dbName = "test";'
        ];

        vfsStream::setup('root', null, $structure);
        $pathBlueprint = vfsStream::url('root');

        $filesystem = new Filesystem();

        $filesystem->remove($this->vendorPath);
        $filesystem->mirror($pathBlueprint, $this->vendorPath);
    }
}
