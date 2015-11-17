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
 * MULTIPOINT parser tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class MultiPointParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParsingMultiPointValue()
    {
        $value    = 'MULTIPOINT(0 0,10 0,10 10,0 10)';
        $parser   = new Parser($value);
        $expected = array(
            'srid'  => null,
            'type'  => 'MULTIPOINT',
            'value' => array(
                array(0, 0),
                array(10, 0),
                array(10, 10),
                array(0, 10)
            )
        );

        $actual = $parser->parse();

        $this->assertEquals($expected, $actual);
    }

    public function testParsingMultiPointValueWithSrid()
    {
        $value    = 'SRID=4326;MULTIPOINT(0 0,10 0,10 10,0 10)';
        $parser   = new Parser($value);
        $expected = array(
            'srid'  => 4326,
            'type'  => 'MULTIPOINT',
            'value' => array(
                array(0, 0),
                array(10, 0),
                array(10, 10),
                array(0, 10)
            )
        );

        $actual = $parser->parse();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException        \CrEOF\Geo\WKT\Exception\UnexpectedValueException
     * @expectedExceptionMessage [Syntax Error] line 0, col 11: Error: Expected CrEOF\Geo\WKT\Lexer::T_INTEGER, got "(" in value "MULTIPOINT((0 0,10 0,10 10,0 10))"
     */
    public function testParsingMultiPointValueWithExtraParenthesis()
    {
        $value  = 'MULTIPOINT((0 0,10 0,10 10,0 10))';
        $parser = new Parser($value);

        $parser->parse();
    }
}
