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
 * LINESTRING parser tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class LineStringParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParsingLineStringValue()
    {
        $value    = 'LINESTRING(34.23 -87, 45.3 -92)';
        $parser   = new Parser($value);
        $expected = array(
            'srid'  => null,
            'type'  => 'LINESTRING',
            'value' => array(
                array(34.23, -87),
                array(45.3, -92)
            )
        );

        $actual = $parser->parse();

        $this->assertEquals($expected, $actual);
    }

    public function testParsingLineStringValueWithSrid()
    {
        $value    = 'SRID=4326;LINESTRING(34.23 -87, 45.3 -92)';
        $parser   = new Parser($value);
        $expected = array(
            'srid'  => 4326,
            'type'  => 'LINESTRING',
            'value' => array(
                array(34.23, -87),
                array(45.3, -92)
            )
        );

        $actual = $parser->parse();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException        \CrEOF\Geo\WKT\Exception\UnexpectedValueException
     * @expectedExceptionMessage [Syntax Error] line 0, col 21: Error: Expected CrEOF\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "45.3" in value "LINESTRING(34.23 -87 45.3 -92)"
     */
    public function testParsingLineStringValueMissingComma()
    {
        $value  = 'LINESTRING(34.23 -87 45.3 -92)';
        $parser = new Parser($value);

        $parser->parse();
    }

    /**
     * @expectedException        \CrEOF\Geo\WKT\Exception\UnexpectedValueException
     * @expectedExceptionMessage [Syntax Error] line 0, col 26: Error: Expected CrEOF\Geo\WKT\Lexer::T_INTEGER, got ")" in value "LINESTRING(34.23 -87, 45.3)"
     */
    public function testParsingLineStringValueMissingCoordinate()
    {
        $value  = 'LINESTRING(34.23 -87, 45.3)';
        $parser = new Parser($value);

        $parser->parse();
    }
}
