<?php

namespace RobLoach\LibretroNetplayRegistry\Test;

use PHPUnit\Framework\TestCase;
use RobLoach\LibretroNetplayRegistry\InputParser;

class InputParserTest extends TestCase
{
    public function testGetEntry()
    {
        $inputParser = new InputParser(array(
            'username' => 'RobLoach',
            'corename' => 'PicoDrive',
            'coreversion' => '1.0.0',
            'gamename' => 'Streets Of Rage 2',
            'gamecrc' => 'dsfjkldsf'
        ));

        $entry = $inputParser->getEntry();

        $this->assertEquals('1.0.0', $entry['coreversion']);
        $this->assertEquals('55435', $entry['port']);
    }
}
