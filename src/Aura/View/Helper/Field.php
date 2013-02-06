<?php
/**
 * 
 * This file is part of the Aura Project for PHP.
 * 
 * @package Aura.View
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\View\Helper;

/**
 * 
 * Helper to generate form field elements.
 * 
 * @package Aura.View
 * 
 */
class Field extends AbstractHelper
{
    protected $input;
    
    protected $radios;
    
    protected $select;
    
    protected $textarea;
    
    public function __construct(
        Input    $input,
        Radios   $radios,
        Select   $select,
        Textarea $textarea
    ) {
        $this->input    = $input;
        $this->radios   = $radios;
        $this->select   = $select;
        $this->textarea = $textarea;
    }
    
    /**
     * 
     * The $spec must consist of five elements:
     * 
     * 'type' (string): The field type.
     * 
     * 'name' (string): The field name.
     * 
     * 'attribs' (array): An array of attributes.
     * 
     * 'options' (array): An array of options (typically for radios and
     * select).
     * 
     * 'value' (array): The current value for the field.
     * 
     */
    public function __invoke($spec)
    {
        extract($spec); // type, name, attribs, options, value, label
        switch (strtolower($type)) {
            case 'radios':
                return $this->radios($name, $attribs, $options, $value);
                break;
            case 'select':
                return $this->select($name, $attribs, $options, $value);
                break;
            case 'textarea':
                return $this->textarea($name, $attribs, $value);
            default:
                return $this->input($type, $name, $attribs, $value, $label);
                break;
        }
    }
    
    protected function input($type, $name, $attribs, $value, $label)
    {
        unset($attribs['type']);
        unset($attribs['name']);
        $attribs = array_merge(['type' => $type, 'name' => $name], $attribs);
        $input = $this->input;
        return $input($attribs, $value, $label);
    }
    
    protected function radios($name, $attribs, $options, $checked)
    {
        unset($attribs['type']);
        unset($attribs['name']);
        $attribs = array_merge(['type' => 'radio', 'name' => $name], $attribs);
        $radios = $this->radios;
        return $radios($attribs, $options, $checked);
    }
    
    protected function select($name, $attribs, $options, $selected)
    {
        unset($attribs['name']);
        $attribs = array_merge(['name' => $name], $attribs);
        
        // set the overall attributes
        $select = $this->select;
        $select($attribs);
        
        // set the options and optgroups
        foreach ($options as $key => $val) {
            if (is_array($val)) {
                // the key is an optgroup label
                $select->optgroup($key);
                // the values are an array of values and labels
                foreach ($val as $subkey => $subval) {
                    $select->option($subkey, $subval);
                }
            } else {
                // the key is an option value and the val is an option label
                $select->option($key, $val);
            }
        }
        
        // set the selected value
        $select->selected($selected);
        
        // return the html
        return $select->fetch();
    }
    
    protected function textarea($name, $attribs, $value)
    {
        unset($attribs['name']);
        $attribs = array_merge(['name' => $name], $attribs);
        
        $textarea = $this->textarea;
        return $textarea($attribs, $value);
    }
}