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
 * MULTILINESTRING parser tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class MultiLineStringParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParsingMultiLineStringValue()
    {
        $value    = 'MULTILINESTRING((0 0,10 0,10 10,0 10),(5 5,7 5,7 7,5 7))';
        $parser   = new Parser($value);
        $expected = array(
            'srid'  => null,
            'type'  => 'MULTILINESTRING',
            'value' => array(
                array(
                    array(0, 0),
                    array(10, 0),
                    array(10, 10),
                    array(0, 10),
                ),
                array(
                    array(5, 5),
                    array(7, 5),
                    array(7, 7),
                    array(5, 7),
                )
            )
        );

        $actual = $parser->parse();

        $this->assertEquals($expected, $actual);
    }

    public function testParsingMultiLineStringValueWithSrid()
    {
        $value    = 'SRID=4326;MULTILINESTRING((0 0,10 0,10 10,0 10),(5 5,7 5,7 7,5 7))';
        $parser   = new Parser($value);
        $expected = array(
            'srid'  => 4326,
            'type'  => 'MULTILINESTRING',
            'value' => array(
                array(
                    array(0, 0),
                    array(10, 0),
                    array(10, 10),
                    array(0, 10),
                ),
                array(
                    array(5, 5),
                    array(7, 5),
                    array(7, 7),
                    array(5, 7),
                )
            )
        );

        $actual = $parser->parse();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException        \CrEOF\Geo\WKT\Exception\UnexpectedValueException
     * @expectedExceptionMessage [Syntax Error] line 0, col 37: Error: Expected CrEOF\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "(" in value "MULTILINESTRING((0 0,10 0,10 10,0 10)(5 5,7 5,7 7,5 7))"
     */
    public function testParsingMultiLineStringValueMissingComma()
    {
        $value  = 'MULTILINESTRING((0 0,10 0,10 10,0 10)(5 5,7 5,7 7,5 7))';
        $parser = new Parser($value);

        $parser->parse();
    }
}
