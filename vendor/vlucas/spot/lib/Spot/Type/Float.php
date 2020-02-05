<?php
namespace Spot\Type;
use Spot\Entity;

class Float extends \Spot\Type
{
    public static $_adapterType = 'decimal';
    public static $_adapterOptions = array('precision' => 14, 'scale' => 10);

    /**
     * Cast given value to type required
     */
    public static function cast($value)
    {
        if(strlen($value)) {
            return (float) $value;
        }
        return null;

    }
}
