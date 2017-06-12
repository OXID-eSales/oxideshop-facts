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
use Webmozart\PathUtil\Path;
use Symfony\Component\Filesystem\Filesystem;
use OxidEsales\Facts\Config\ConfigFile;

class ConfigFileTest extends \PHPUnit_Framework_TestCase
{
    private $temporaryPath;
    private $vendorPath;
    private $targetPath;

    public function setUp()
    {
        $this->temporaryPath = Path::join(__DIR__, 'tmp');
        $this->vendorPath = Path::join(__DIR__, 'tmp', 'testData');
        $this->targetPath = Path::join(__DIR__, 'tmp', 'testTarget');
        $this->buildDirectory();
    }

    public function tearDown()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->temporaryPath);
    }

    public function testIncludeAndParseConfigFile()
    {
        $configFile = new ConfigFile(Path::join($this->vendorPath, 'config.inc.php'));

        $this->assertSame('test', $configFile->getVar('dbName'));
    }

    private function buildDirectory()
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
