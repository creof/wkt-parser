<?php
/**
 * Copyright (C) 2015 Derek J. Lambert
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CrEOF\Geo\WKT\Tests;

use CrEOF\Geo\WKT\Parser;

/**
 * POINT parser tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class PointParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParsingPointValue()
    {
        $value    = 'POINT(34.23 -87)';
        $parser   = new Parser($value);
        $expected = array(
            'srid'  => null,
            'type'  => 'POINT',
            'value' => array(34.23, -87)
        );

        $actual = $parser->parse();

        $this->assertEquals($expected, $actual);
    }

    public function testParsingPointValueWithSrid()
    {
        $value    = 'SRID=4326;POINT(34.23 -87)';
        $parser   = new Parser($value);
        $expected = array(
            'srid'  => 4326,
            'type'  => 'POINT',
            'value' => array(34.23, -87)
        );

        $actual = $parser->parse();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException        \CrEOF\Geo\WKT\Exception\UnexpectedValueException
     * @expectedExceptionMessage [Syntax Error] line 0, col 5: Error: Expected CrEOF\Geo\WKT\Lexer::T_INTEGER, got "432.6" in value "SRID=432.6;POINT(34.23 -87)"
     */
    public function testParsingPointValueWithBadSrid()
    {
        $value  = 'SRID=432.6;POINT(34.23 -87)';
        $parser = new Parser($value);

        $parser->parse();
    }

    /**
     * @expectedException        \CrEOF\Geo\WKT\Exception\UnexpectedValueException
     * @expectedExceptionMessage [Syntax Error] line 0, col 11: Error: Expected CrEOF\Geo\WKT\Lexer::T_INTEGER, got ")" in value "POINT(34.23)"
     */
    public function testParsingPointValueMissingCoordinate()
    {
        $value  = 'POINT(34.23)';
        $parser = new Parser($value);

        $parser->parse();
    }

    /**
     * @expectedException        \CrEOF\Geo\WKT\Exception\UnexpectedValueException
     * @expectedExceptionMessage [Syntax Error] line 0, col -1: Error: Expected CrEOF\Geo\WKT\Lexer::T_INTEGER, got end of string. in value "POINT(34.23"
     */
    public function testParsingPointValueShortString()
    {
        $value  = 'POINT(34.23';
        $parser = new Parser($value);

        $parser->parse();
    }

    public function testParsingPointValueScientificWithSrid()
    {
        $value    = 'SRID=4326;POINT(4.23e-005 -8E-003)';
        $parser   = new Parser($value);
        $expected = array(
            'srid'  => 4326,
            'type'  => 'POINT',
            'value' => array(0.0000423, -0.008)
        );

        $actual = $parser->parse();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException        \CrEOF\Geo\WKT\Exception\UnexpectedValueException
     * @expectedExceptionMessage [Syntax Error] line 0, col 20: Error: Expected CrEOF\Geo\WKT\Lexer::T_INTEGER, got "test" in value "SRID=4326;POINT(4.23test-005 -8e-003)"
     */
    public function testParsingPointValueWrongScientificWithSrid()
    {
        $value    = 'SRID=4326;POINT(4.23test-005 -8e-003)';
        $parser   = new Parser($value);

        $parser->parse();
    }


    /**
     * @expectedException        \CrEOF\Geo\WKT\Exception\UnexpectedValueException
     * @expectedExceptionMessage [Syntax Error] line 0, col 8: Error: Expected CrEOF\Geo\WKT\Lexer::T_INTEGER, got "," in value "POINT(10, 10)"
     */
    public function testParsingPointValueWithComma()
    {
        $value  = 'POINT(10, 10)';
        $parser = new Parser($value);

        $parser->parse();
    }
}
