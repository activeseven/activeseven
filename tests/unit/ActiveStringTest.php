<?php

use PHPUnit\Framework\TestCase;

class ActiveStringTest extends TestCase
{
    public function testToStringReturnsCorrectValue()
    {
        //require "./src/ActiveString.php";

        $test_string    = 'Just s0m3 s!lly $tring.';
        $active_string  = new \activeseven\ActiveString( $test_string );

        $this->assertEquals($test_string,$active_string);
    }
}