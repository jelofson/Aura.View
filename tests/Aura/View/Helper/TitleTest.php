<?php
namespace Aura\View\Helper;

/**
 * Test class for Title.
 * Generated by PHPUnit on 2011-04-02 at 08:29:14.
 */
class TitleTest extends AbstractHelperTest
{
    public function test__invoke()
    {
        $title = new Title;
        $actual = $title();
        $this->assertInstanceOf('Aura\View\Helper\Title', $actual);
    }
    
    public function testEverything()
    {
        $title = new Title;
        $this->assertInstanceOf('Aura\View\Helper\Title', $title);
        
        $title->set($this->escape('This & That'));
        
        $title->append($this->escape(' Suf1'));
        $title->append($this->escape(' Suf2'));
        
        $title->prepend($this->escape('Pre1 '));
        $title->prepend($this->escape('Pre2 '));
        
        $actual = $title->get();
        $expect = '<title>Pre2 Pre1 This &amp; That Suf1 Suf2</title>' . PHP_EOL;
        $this->assertSame($expect, $actual);
    }

    public function testSetIndent()
    {
        $title = new Title;
        $title->setIndent('  ');
        $title->setIndentLevel(1);
        
        $title->set($this->escape('This & That'));

        $actual = $title->get();
        $expect = '  <title>This &amp; That</title>' . PHP_EOL;
        $this->assertSame($expect, $actual);
    }
}
