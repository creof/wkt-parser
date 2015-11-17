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
 * MULTIPOLYGON parser tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class MultiPolygonParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParsingMultiPolygonValue()
    {
        $value    = 'MULTIPOLYGON(((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5)),((1 1, 3 1, 3 3, 1 3, 1 1)))';
        $parser   = new Parser($value);
        $expected = array(
            'srid'  => null,
            'type'  => 'MULTIPOLYGON',
            'value' => array(
                array(
                    array(
                        array(0, 0),
                        array(10, 0),
                        array(10, 10),
                        array(0, 10),
                        array(0, 0)
                    ),
                    array(
                        array(5, 5),
                        array(7, 5),
                        array(7, 7),
                        array(5, 7),
                        array(5, 5)
                    )
                ),
                array(
                    array(
                        array(1, 1),
                        array(3, 1),
                        array(3, 3),
                        array(1, 3),
                        array(1, 1)
                    )
                )
            )
        );

        $actual = $parser->parse();

        $this->assertEquals($expected, $actual);
    }

    public function testParsingMultiPolygonValueWithSrid()
    {
        $value    = 'SRID=4326;MULTIPOLYGON(((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5)),((1 1, 3 1, 3 3, 1 3, 1 1)))';
        $parser   = new Parser($value);
        $expected = array(
            'srid'  => 4326,
            'type'  => 'MULTIPOLYGON',
            'value' => array(
                array(
                    array(
                        array(0, 0),
                        array(10, 0),
                        array(10, 10),
                        array(0, 10),
                        array(0, 0)
                    ),
                    array(
                        array(5, 5),
                        array(7, 5),
                        array(7, 7),
                        array(5, 7),
                        array(5, 5)
                    )
                ),
                array(
                    array(
                        array(1, 1),
                        array(3, 1),
                        array(3, 3),
                        array(1, 3),
                        array(1, 1)
                    )
                )
            )
        );

        $actual = $parser->parse();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException        \CrEOF\Geo\WKT\Exception\UnexpectedValueException
     * @expectedExceptionMessage [Syntax Error] line 0, col 64: Error: Expected CrEOF\Geo\WKT\Lexer::T_OPEN_PARENTHESIS, got "1" in value "MULTIPOLYGON(((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5)),(1 1, 3 1, 3 3, 1 3, 1 1))"
     */
    public function testParsingMultiPolygonValueMissingParenthesis()
    {
        $value  = 'MULTIPOLYGON(((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5)),(1 1, 3 1, 3 3, 1 3, 1 1))';
        $parser = new Parser($value);

        $parser->parse();
    }
}
