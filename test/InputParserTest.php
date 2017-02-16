<?php

namespace RobLoach\LibretroNetplayRegistry\Test;

use PHPUnit\Framework\TestCase;
use RobLoach\LibretroNetplayRegistry\InputParser;

/**
 * Class InputParserTest.
 */
class InputParserTest extends TestCase
{
    public function testGetEntry()
    {
        $inputParser = new InputParser(array(
            'username' => 'RobLoach',
            'corename' => 'PicoDrive',
            'coreversion' => '1.0.0',
            'gamename' => 'Streets Of _ Rage 2',
            'gamecrc' => 'dsfjkldsf'
        ));

        $entry = $inputParser->getEntry();

        $this->assertEquals('1.0.0', $entry['coreversion']);
        $this->assertEquals('55435', $entry['port']);
    }

    public function testCleanProperty()
    {
        $inputParser = new InputParser();
        $expected = 's Streets & Rage 2 (USA) [b] scriptdocument.location.hrefhttp//libretro.com/script';
        $input = '%s Streets & Rage 2 (USA) [b] <script>document.location.href="http://libretro.com"</script>';
        $actual = $inputParser->cleanProperty($input);
        $this->assertEquals($expected, $actual);
    }

    public function testAutofillUsername()
    {
        $inputParser = new InputParser(array(
            'corename' => 'PicoDrive',
            'coreversion' => '1.0.0',
            'gamename' => 'Streets Of Rage 2 (){}[].,',
            'gamecrc' => 'dsfjkldsf'
        ));

        $entry = $inputParser->getEntry();

        $this->assertEquals('0.0.0.0', $entry['username']);
        $this->assertEquals('Streets Of Rage 2 (){}[].,', $entry['gamename']);
    }
}
