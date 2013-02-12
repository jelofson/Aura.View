<?php
namespace Aura\View;

use org\bovigo\vfs\vfsStream as Vfs;

/**
 * Test class for TwoStep.
 * Generated by PHPUnit on 2011-12-26 at 08:46:27.
 */
class TwoStepTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TwoStep
     */
    protected $twostep;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->vfs = Vfs::setup('root');
        
        // prepare a set of directories for paths
        $this->dirs = [
            'foo' => Vfs::url('root' . DIRECTORY_SEPARATOR . 'foo'),
            'bar' => Vfs::url('root' . DIRECTORY_SEPARATOR . 'bar'),
            'baz' => Vfs::url('root' . DIRECTORY_SEPARATOR . 'baz'),
        ];
        foreach ($this->dirs as $dir) {
            mkdir($dir, 0777, true);
        }
        
        // put an inner view in 'foo'
        $file = $this->dirs['foo'] . DIRECTORY_SEPARATOR . 'inner_view.php';
        $code = '<strong><?= $this->inner_var; ?></strong>';
        file_put_contents($file, $code);
        
        // put an outer view in 'baz'
        $file = $this->dirs['baz'] . DIRECTORY_SEPARATOR . 'outer_view.php';
        $code = '<div><?php echo $this->outer_var . " " . $this->inner_view; ?></div>';
        file_put_contents($file, $code);
        
        // set up the TwoStep view
        $template_finder = new TemplateFinder();
        
        $helper_locator = new HelperLocator;
        $helper_locator->set('mockHelper', function () {
            return new \Aura\View\Helper\MockHelper;
        });
        
        $template = new Template($template_finder, $helper_locator);
        
        $format_types = new FormatTypes;
        
        $this->twostep = new TwoStep($template, $format_types);
    }

    public function testSetAndGetAccept()
    {
        $expect = [
            'text/html' => '1.0',
            'application/xhtml+xml' => '0.9',
        ];
        
        $this->twostep->setAccept($expect);
        $actual = $this->twostep->getAccept();
        $this->assertSame($expect, $actual);
    }

    public function testSetAndGetFormat()
    {
        $expect = '.html';
        $this->twostep->setFormat($expect);
        $actual = $this->twostep->getFormat();
        $this->assertSame($expect, $actual);
    }

    public function testSetAndGetData()
    {
        $expect = [
            'foo' => 'bar',
            'baz' => 'dib',
            'zim' => 'gir',
            'irk' => 'doom',
        ];
        $this->twostep->setData($expect);
        $this->assertSame($expect, $this->twostep->getData());
    }

    public function testSetAddAndGetInnerPaths()
    {
        $expect = [$this->dirs['foo'], $this->dirs['bar']];
        $this->twostep->setInnerPaths($expect);
        $actual = $this->twostep->getInnerPaths();
        $this->assertSame($expect, $actual);
        
        $this->twostep->addInnerPath($this->dirs['baz']);
        $expect[] = $this->dirs['baz'];
        $actual = $this->twostep->getInnerPaths();
        $this->assertSame($expect, $actual);
    }

    public function testGetInnerView_none()
    {
        // note that we never set the inner view
        $actual = $this->twostep->getInnerView();
        $this->assertNull($actual);
    }
    
    public function testSetAndGetInnerView_noFormat()
    {
        $expect = 'foo.php';
        $this->twostep->setInnerView($expect);
        $actual = $this->twostep->getInnerView();
        $this->assertSame($expect, $actual);
    }

    public function testSetAndGetInnerView_single()
    {
        $expect = 'foo.php';
        $this->twostep->setInnerView($expect);
        $actual = $this->twostep->getInnerView('.html');
        $this->assertSame($expect, $actual);
    }
    
    public function testSetAndGetInnerView_array()
    {
        $expect = [
            '.html' => 'inner.php',
            '.json' => 'inner.json.php',
        ];
        
        $this->twostep->setInnerView($expect);
        
        // get all formats
        $actual = $this->twostep->getInnerView();
        $this->assertSame($expect, $actual);
        
        // get where format is set
        $expect = 'inner.php';
        $actual = $this->twostep->getInnerView('.html');
        $this->assertSame($expect, $actual);
        
        // get where format is not set
        $this->assertFalse($this->twostep->getInnerView('.xml'));
    }
    
    public function testSetAddAndGetOuterPaths()
    {
        $expect = [$this->dirs['foo'], $this->dirs['bar']];
        $this->twostep->setOuterPaths($expect);
        $actual = $this->twostep->getOuterPaths();
        $this->assertSame($expect, $actual);
        
        $this->twostep->addOuterPath($this->dirs['baz']);
        $expect[] = $this->dirs['baz'];
        $actual = $this->twostep->getOuterPaths();
        $this->assertSame($expect, $actual);
    }
    
    public function testGetOuterView_none()
    {
        // note that we never set the outer view
        $actual = $this->twostep->getOuterView();
        $this->assertNull($actual);
    }
    
    public function testSetAndGetOuterView_noFormat()
    {
        $expect = 'foo.php';
        $this->twostep->setOuterView($expect);
        $actual = $this->twostep->getOuterView();
        $this->assertSame($expect, $actual);
    }

    public function testSetAndGetOuterView_single()
    {
        $expect = 'foo.php';
        $this->twostep->setOuterView($expect);
        $actual = $this->twostep->getOuterView('.html');
        $this->assertSame($expect, $actual);
    }
    
    public function testSetAndGetOuterView_array()
    {
        $expect = [
            '.html' => 'outer.php',
            '.json' => 'outer.json.php',
        ];
        
        $this->twostep->setOuterView($expect);
        
        // get all formats
        $actual = $this->twostep->getOuterView();
        $this->assertSame($expect, $actual);
        
        // get where format is set
        $expect = 'outer.php';
        $actual = $this->twostep->getOuterView('.html');
        $this->assertSame($expect, $actual);
        
        // get where format is not set
        $this->assertFalse($this->twostep->getOuterView('.xml'));
    }

    public function testSetAndGetInnerViewVar()
    {
        $expect = 'some_var_name';
        $this->twostep->setInnerViewVar($expect);
        $actual = $this->twostep->getInnerViewVar();
        $this->assertSame($expect, $actual);
    }
    
    public function testRenderInnerView()
    {
        $this->twostep->setData(['inner_var' => 'World!']);
        
        $this->twostep->setInnerView('inner_view');
        $this->twostep->setInnerPaths([$this->dirs['bar'], $this->dirs['foo']]);
        
        $expect = '<strong>World!</strong>';
        $actual = $this->twostep->render();
        $this->assertSame($expect, $actual);
    }
    
    public function testRenderInnerView_none()
    {
        $actual = $this->twostep->render();
        $this->assertNull($actual);
    }
    
    public function testRenderInnerView_closure()
    {
        $func = function() { return 'World!'; };
        
        $this->twostep->setInnerView($func);
        $expect = 'World!';
        $actual = $this->twostep->render();
        $this->assertSame($expect, $actual);
    }

    // don't set a format, let it negotiate one from accept headers
    public function testRender()
    {
        $this->twostep->setAccept([
            'text/html' => 1.0,
            'application/json' => 0.9,
        ]);
        
        $this->twostep->setData([
            'inner_var' => 'World!',
            'outer_var' => 'Hello',
        ]);
        
        $view = $this->twostep;
        $this->twostep->setInnerView([
            '.html' => 'inner_view',
            '.json' => function() use ($view) {
                return json_encode($view->getData());
            },
        ]);
        
        $this->twostep->setInnerPaths([$this->dirs['bar'], $this->dirs['foo']]);
        
        $this->twostep->setOuterView('outer_view');
        $this->twostep->setOuterPaths([$this->dirs['bar'], $this->dirs['baz']]);
        
        $expect = '<div>Hello <strong>World!</strong></div>';
        $actual = $this->twostep->render();
        
        $this->assertSame($expect, $actual);
    }
    
    public function testRender_noAcceptFormats()
    {
        $this->twostep->setData([
            'inner_var' => 'World!',
            'outer_var' => 'Hello',
        ]);
        
        $this->twostep->setInnerView('inner_view');
        $this->twostep->setInnerPaths([$this->dirs['bar'], $this->dirs['foo']]);
        $this->twostep->setOuterView('outer_view');
        $this->twostep->setOuterPaths([$this->dirs['bar'], $this->dirs['baz']]);
        
        $expect = '<div>Hello <strong>World!</strong></div>';
        $actual = $this->twostep->render();
        $this->assertSame($expect, $actual);
    }
    
    public function testGetContentType()
    {
        $this->twostep->setFormat('.html');
        $expect = 'text/html';
        $actual = $this->twostep->getContentType();
        $this->assertSame($expect, $actual);
    }
}
