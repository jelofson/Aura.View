<?php
namespace Aura\View;

use org\bovigo\vfs\vfsStream as Vfs;

/**
 * Test class for TemplateFinder.
 * Generated by PHPUnit on 2011-03-27 at 14:43:53.
 */
class TemplateFinderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TemplateFinder
     */
    protected $finder;

    protected $dirs = [];
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->vfs = Vfs::setup('root');
        $this->finder = new TemplateFinder;
        
        // prepare a set of directories for paths
        $this->dirs = [
            Vfs::url('root' . DIRECTORY_SEPARATOR . 'foo'),
            Vfs::url('root' . DIRECTORY_SEPARATOR . 'bar'),
            Vfs::url('root' . DIRECTORY_SEPARATOR . 'baz'),
        ];
        
        foreach ($this->dirs as $dir) {
            mkdir($dir, 0777, true);
        }
    }
    
    public function testSetAndGetPaths()
    {
        // should be no paths yet
        $expect = [];
        $actual = $this->finder->getPaths();
        $this->assertSame($expect, $actual);
        
        // set the paths
        $this->finder->setPaths($this->dirs);
        $expect = $this->dirs;
        $actual = $this->finder->getPaths();
        $this->assertSame($expect, $actual);
    }

    public function testPrepend()
    {
        foreach ($this->dirs as $path) {
            $this->finder->prepend($path);
        }
        
        $expect = array_values(array_reverse($this->dirs));
        $actual = $this->finder->getPaths();
        $this->assertSame($expect, $actual);
    }

    public function testAppend()
    {
        foreach ($this->dirs as $path) {
            $this->finder->append($path);
        }
        
        $expect = array_values($this->dirs);
        $actual = $this->finder->getPaths();
        $this->assertSame($expect, $actual);
    }

    public function testFind()
    {
        // set the paths to the dirs
        $this->finder->setPaths($this->dirs);
        
        // place a file in one of the dirs at random
        $key = array_rand($this->dirs);
        $dir = $this->dirs[$key];
        $file = $dir . DIRECTORY_SEPARATOR . 'zim.php';
        file_put_contents($file, 'empty');
        
        // now find it
        $expect = $file;
        $actual = $this->finder->find('zim.php');
        $this->assertSame($expect, $actual);
        
        // find it again for code coverage
        $expect = $file;
        $actual = $this->finder->find('zim.php');
        $this->assertSame($expect, $actual);
        
        // look for a file that doesn't exist
        $actual = $this->finder->find('no-such-file');
        $this->assertFalse($actual);
    }
}
