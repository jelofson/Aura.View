<?php
namespace Aura\View\Helper;

class InputTest extends AbstractHelperTest
{
    /**
     * @dataProvider provideValueTypes
     */
    public function testValueTypes($type)
    {
        $input = new Input;
        
        // given value should override the attribute
        $actual = $input(
            $this->escape([
                'type' => $type,
                'value' => 'should not be here',
            ]),
            $this->escape('field value')
        );
        
        $expect = "<input type=\"$type\" value=\"field value\" />";
        $this->assertSame($expect, $actual);
        
        // no value given so attribute should still be there
        $actual = $input(
            $this->escape([
                'type' => $type,
                'value' => 'field value',
            ])
        );
        
        $expect = "<input type=\"$type\" value=\"field value\" />";
        $this->assertSame($expect, $actual);
    }
    
    /**
     * @dataProvider provideCheckedTypes
     */
    public function testCheckedTypes($type)
    {
        $input = new Input;
        
        // value should be checked
        $actual = $input(
            $this->escape([
                'type' => $type,
                'value' => 'yes',
            ]),
            $this->escape('yes')
        );
        
        $expect = "<input type=\"$type\" value=\"yes\" checked=\"checked\" />";
        
        $this->assertSame($expect, $actual);

        // value should not be checked
        $actual = $input(
            $this->escape([
                'type' => $type,
                'value' => 'yes',
            ]),
            $this->escape('no')
        );
        
        $expect = "<input type=\"$type\" value=\"yes\" />";
    }
    
    /**
     * @dataProvider provideButtonTypes
     */
    public function testButtonTypes($type)
    {
        $input = new Input;
        
        // given value should not override the attribute
        $actual = $input(
            $this->escape([
                'type' => $type,
                'value' => 'button value',
            ]),
            $this->escape('should not be here')
        );
        
        $expect = "<input type=\"$type\" value=\"button value\" />";
        
        $this->assertSame($expect, $actual);
    }
    
    public function testLabelWithAttribs()
    {
        $input = new Input;
        
        $actual = $input(
            $this->escape([
                'type' => 'radio',
                'value' => 'yes',
                'id' => 'radio-yes'
            ]),
            $this->escape('no'),
            $this->escape('Radio Label'),
            $this->escape([
                'class' => 'test',
            ])
        );
        
        $expect = '<label class="test" for="radio-yes">'
                . '<input type="radio" value="yes" id="radio-yes" /> Radio Label</label>';
        
        $this->assertSame($expect, $actual);
    }

    public function testLabelWithoutAttribs()
    {
        $input = new Input;
        
        $actual = $input(
            $this->escape([
                'type' => 'radio',
                'value' => 'yes',
            ]),
            $this->escape('no'),
            $this->escape('Radio Label')
        );
        
        $expect = '<label><input type="radio" value="yes" /> Radio Label</label>';
        
        $this->assertSame($expect, $actual);
    }
    
    public function provideValueTypes()
    {
        return [
            ['color'],
            ['date'],
            ['datetime'],
            ['datetime-local'],
            ['email'],
            ['hidden'],
            ['month'],
            ['number'],
            ['password'],
            ['range'],
            ['search'],
            ['tel'],
            ['text'],
            ['time'],
            ['url'],
            ['week'],
        ];
    }

    public function provideCheckedTypes()
    {
        return [
            ['checkbox'],
            ['radio'],
        ];
    }

    public function provideButtonTypes()
    {
        return [
            ['button'],
            ['file'],
            ['image'],
            ['reset'],
            ['submit'],
        ];
    }

}

