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
 * GEOMETRYCOLLECTION Parser tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class GeometryCollectionParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParsingGeometryCollectionValue()
    {
        $value    = 'GEOMETRYCOLLECTION(POINT(10 10), POINT(30 30), LINESTRING(15 15, 20 20))';
        $parser   = new Parser($value);
        $expected = array(
            'srid'  => null,
            'type'  => 'GEOMETRYCOLLECTION',
            'value' => array(
                array(
                    'type'  => 'POINT',
                    'value' => array(10, 10)
                ),
                array(
                    'type'  => 'POINT',
                    'value' => array(30, 30)
                ),
                array(
                    'type'  => 'LINESTRING',
                    'value' => array(
                        array(15, 15),
                        array(20, 20)
                    )
                )
            )
        );

        $actual = $parser->parse();

        $this->assertEquals($expected, $actual);
    }

    public function testParsingGeometryCollectionValueWithSrid()
    {
        $value    = 'SRID=4326;GEOMETRYCOLLECTION(POINT(10 10), POINT(30 30), LINESTRING(15 15, 20 20))';
        $parser   = new Parser($value);
        $expected = array(
            'srid'  => 4326,
            'type'  => 'GEOMETRYCOLLECTION',
            'value' => array(
                array(
                    'type'  => 'POINT',
                    'value' => array(10, 10)
                ),
                array(
                    'type'  => 'POINT',
                    'value' => array(30, 30)
                ),
                array(
                    'type'  => 'LINESTRING',
                    'value' => array(
                        array(15, 15),
                        array(20, 20)
                    )
                )
            )
        );

        $actual = $parser->parse();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException        \CrEOF\Geo\WKT\Exception\UnexpectedValueException
     * @expectedExceptionMessage [Syntax Error] line 0, col 19: Error: Expected CrEOF\Geo\WKT\Lexer::T_TYPE, got "PNT" in value "GEOMETRYCOLLECTION(PNT(10 10), POINT(30 30), LINESTRING(15 15, 20 20))"
     */
    public function testParsingGeometryCollectionValueWithBadType()
    {
        $value  = 'GEOMETRYCOLLECTION(PNT(10 10), POINT(30 30), LINESTRING(15 15, 20 20))';
        $parser = new Parser($value);

        $parser->parse();
    }
}
