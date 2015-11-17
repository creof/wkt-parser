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
 * Basic parser tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException        \CrEOF\Geo\WKT\Exception\UnexpectedValueException
     * @expectedExceptionMessage [Syntax Error] line 0, col 0: Error: Expected CrEOF\Geo\WKT\Lexer::T_TYPE, got "@" in value "@#_$%"
     */
    public function testParsingGarbage()
    {
        $value  = '@#_$%';
        $parser = new Parser($value);

        $parser->parse();
    }

    /**
     * @expectedException        \CrEOF\Geo\WKT\Exception\UnexpectedValueException
     * @expectedExceptionMessage [Syntax Error] line 0, col 0: Error: Expected CrEOF\Geo\WKT\Lexer::T_TYPE, got "PNT" in value "PNT(10 10)"
     */
    public function testParsingBadType()
    {
        $value  = 'PNT(10 10)';
        $parser = new Parser($value);

        $parser->parse();
    }
}
