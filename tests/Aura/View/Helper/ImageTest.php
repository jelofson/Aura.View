<?php
namespace Aura\View\Helper;

/**
 * Test class for Image.
 * Generated by PHPUnit on 2011-04-02 at 08:28:45.
 */
class ImageTest extends AbstractHelperTest
{
    public function test__invoke()
    {
        $image = new Image;
        $src = '/images/example.gif';
        $actual = $image($this->escape($src));
        $expect = '<img src="/images/example.gif" alt="example.gif" />';
        $this->assertSame($actual, $expect);
    }
}
