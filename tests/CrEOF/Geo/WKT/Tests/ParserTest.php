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

use CrEOF\Geo\WKT\Exception\UnexpectedValueException;
use CrEOF\Geo\WKT\Parser;

/**
 * Basic parser tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function parserTestData()
    {
        return array(
            'testParsingGarbage' => array(
                'value' => '@#_$%',
                'expected' => new UnexpectedValueException('[Syntax Error] line 0, col 0: Error: Expected CrEOF\Geo\WKT\Lexer::T_TYPE, got "@" in value "@#_$%"')
            ),
            'testParsingBadType' => array(
                'value' => 'PNT(10 10)',
                'expected' => new UnexpectedValueException('[Syntax Error] line 0, col 0: Error: Expected CrEOF\Geo\WKT\Lexer::T_TYPE, got "PNT" in value "PNT(10 10)"')
            ),
            'testParsingPointValue' => array(
                'value' => 'POINT(34.23 -87)',
                'expected' => array(
                    'srid'  => null,
                    'type'  => 'POINT',
                    'value' => array(34.23, -87)
                )
            ),
            'testParsingPointValueWithSrid' => array(
                'value' => 'SRID=4326;POINT(34.23 -87)',
                'expected' => array(
                    'srid'  => 4326,
                    'type'  => 'POINT',
                    'value' => array(34.23, -87)
                )
            ),
            'testParsingPointValueScientificWithSrid' => array(
                'value' => 'SRID=4326;POINT(4.23e-005 -8E-003)',
                'expected' => array(
                    'srid'  => 4326,
                    'type'  => 'POINT',
                    'value' => array(0.0000423, -0.008)
                )
            ),
            'testParsingPointValueWithBadSrid' => array(
                'value' => 'SRID=432.6;POINT(34.23 -87)',
                'expected' => new UnexpectedValueException('[Syntax Error] line 0, col 5: Error: Expected CrEOF\Geo\WKT\Lexer::T_INTEGER, got "432.6" in value "SRID=432.6;POINT(34.23 -87)"')
            ),
            'testParsingPointValueMissingCoordinate' => array(
                'value' => 'POINT(34.23)',
                'expected' => new UnexpectedValueException('[Syntax Error] line 0, col 11: Error: Expected CrEOF\Geo\WKT\Lexer::T_INTEGER, got ")" in value "POINT(34.23)"')
            ),
            'testParsingPointValueShortString' => array(
                'value' => 'POINT(34.23',
                'expected' => new UnexpectedValueException('[Syntax Error] line 0, col -1: Error: Expected CrEOF\Geo\WKT\Lexer::T_INTEGER, got end of string. in value "POINT(34.23"')
            ),
            'testParsingPointValueWrongScientificWithSrid' => array(
                'value' => 'SRID=4326;POINT(4.23test-005 -8e-003)',
                'expected' => new UnexpectedValueException('[Syntax Error] line 0, col 20: Error: Expected CrEOF\Geo\WKT\Lexer::T_INTEGER, got "test" in value "SRID=4326;POINT(4.23test-005 -8e-003)"')
            ),
            'testParsingPointValueWithComma' => array(
                'value' => 'POINT(10, 10)',
                'expected' => new UnexpectedValueException('[Syntax Error] line 0, col 8: Error: Expected CrEOF\Geo\WKT\Lexer::T_INTEGER, got "," in value "POINT(10, 10)"')
            ),
            'testParsingLineStringValue' => array(
                'value' => 'LINESTRING(34.23 -87, 45.3 -92)',
                'expected' => array(
                    'srid'  => null,
                    'type'  => 'LINESTRING',
                    'value' => array(
                        array(34.23, -87),
                        array(45.3, -92)
                    )
                )
            ),
            'testParsingLineStringValueWithSrid' => array(
                'value' => 'SRID=4326;LINESTRING(34.23 -87, 45.3 -92)',
                'expected' => array(
                    'srid'  => 4326,
                    'type'  => 'LINESTRING',
                    'value' => array(
                        array(34.23, -87),
                        array(45.3, -92)
                    )
                )
            ),
            'testParsingLineStringValueMissingComma' => array(
                'value' => 'LINESTRING(34.23 -87 45.3 -92)',
                'expected' => new UnexpectedValueException('[Syntax Error] line 0, col 21: Error: Expected CrEOF\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "45.3" in value "LINESTRING(34.23 -87 45.3 -92)"')
            ),
            'testParsingLineStringValueMissingCoordinate' => array(
                'value' => 'LINESTRING(34.23 -87, 45.3)',
                'expected' => new UnexpectedValueException('[Syntax Error] line 0, col 26: Error: Expected CrEOF\Geo\WKT\Lexer::T_INTEGER, got ")" in value "LINESTRING(34.23 -87, 45.3)"')
            ),
            'testParsingPolygonValue' => array(
                'value' => 'POLYGON((0 0,10 0,10 10,0 10,0 0))',
                'expected' => array(
                    'srid'  => null,
                    'type'  => 'POLYGON',
                    'value' => array(
                        array(
                            array(0, 0),
                            array(10, 0),
                            array(10, 10),
                            array(0, 10),
                            array(0, 0)
                        )
                    )
                )
            ),
            'testParsingPolygonValueWithSrid' => array(
                'value' => 'SRID=4326;POLYGON((0 0,10 0,10 10,0 10,0 0))',
                'expected' => array(
                    'srid'  => 4326,
                    'type'  => 'POLYGON',
                    'value' => array(
                        array(
                            array(0, 0),
                            array(10, 0),
                            array(10, 10),
                            array(0, 10),
                            array(0, 0)
                        )
                    )
                )
            ),
            'testParsingPolygonValueMultiRing' => array(
                'value' => 'POLYGON((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5))',
                'expected' => array(
                    'srid'  => null,
                    'type'  => 'POLYGON',
                    'value' => array(
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
                    )
                )
            ),
            'testParsingPolygonValueMultiRingWithSrid' => array(
                'value' => 'SRID=4326;POLYGON((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5))',
                'expected' => array(
                    'srid'  => 4326,
                    'type'  => 'POLYGON',
                    'value' => array(
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
                    )
                )
            ),
            'testParsingPolygonValueMissingParenthesis' => array(
                'value' => 'POLYGON(0 0,10 0,10 10,0 10,0 0)',
                'expected' => new UnexpectedValueException('[Syntax Error] line 0, col 8: Error: Expected CrEOF\Geo\WKT\Lexer::T_OPEN_PARENTHESIS, got "0" in value "POLYGON(0 0,10 0,10 10,0 10,0 0)"')
            ),
            'testParsingPolygonValueMultiRingMissingComma' => array(
                'value' => 'POLYGON((0 0,10 0,10 10,0 10,0 0)(5 5,7 5,7 7,5 7,5 5))',
                'expected' => new UnexpectedValueException('[Syntax Error] line 0, col 33: Error: Expected CrEOF\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "(" in value "POLYGON((0 0,10 0,10 10,0 10,0 0)(5 5,7 5,7 7,5 7,5 5))"')
            ),
            'testParsingMultiPointValue' => array(
                'value' => 'MULTIPOINT(0 0,10 0,10 10,0 10)',
                'expected' => array(
                    'srid'  => null,
                    'type'  => 'MULTIPOINT',
                    'value' => array(
                        array(0, 0),
                        array(10, 0),
                        array(10, 10),
                        array(0, 10)
                    )
                )
            ),
            'testParsingMultiPointValueWithSrid' => array(
                'value' => 'SRID=4326;MULTIPOINT(0 0,10 0,10 10,0 10)',
                'expected' => array(
                    'srid'  => 4326,
                    'type'  => 'MULTIPOINT',
                    'value' => array(
                        array(0, 0),
                        array(10, 0),
                        array(10, 10),
                        array(0, 10)
                    )
                )
            ),
            'testParsingMultiPointValueWithExtraParenthesis' => array(
                'value' => 'MULTIPOINT((0 0,10 0,10 10,0 10))',
                'expected' => new UnexpectedValueException('[Syntax Error] line 0, col 11: Error: Expected CrEOF\Geo\WKT\Lexer::T_INTEGER, got "(" in value "MULTIPOINT((0 0,10 0,10 10,0 10))"')
            ),
            'testParsingMultiLineStringValue' => array(
                'value' => 'MULTILINESTRING((0 0,10 0,10 10,0 10),(5 5,7 5,7 7,5 7))',
                'expected' => array(
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
                )
            ),
            'testParsingMultiLineStringValueWithSrid' => array(
                'value' => 'SRID=4326;MULTILINESTRING((0 0,10 0,10 10,0 10),(5 5,7 5,7 7,5 7))',
                'expected' => array(
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
                )
            ),
            'testParsingMultiLineStringValueMissingComma' => array(
                'value' => 'MULTILINESTRING((0 0,10 0,10 10,0 10)(5 5,7 5,7 7,5 7))',
                'expected' => new UnexpectedValueException('[Syntax Error] line 0, col 37: Error: Expected CrEOF\Geo\WKT\Lexer::T_CLOSE_PARENTHESIS, got "(" in value "MULTILINESTRING((0 0,10 0,10 10,0 10)(5 5,7 5,7 7,5 7))"')
            ),
            'testParsingMultiPolygonValue' => array(
                'value' => 'MULTIPOLYGON(((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5)),((1 1, 3 1, 3 3, 1 3, 1 1)))',
                'expected' => array(
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
                )
            ),
            'testParsingMultiPolygonValueWithSrid' => array(
                'value' => 'SRID=4326;MULTIPOLYGON(((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5)),((1 1, 3 1, 3 3, 1 3, 1 1)))',
                'expected' => array(
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
                )
            ),
            'testParsingMultiPolygonValueMissingParenthesis' => array(
                'value' => 'MULTIPOLYGON(((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5)),(1 1, 3 1, 3 3, 1 3, 1 1))',
                'expected' => new UnexpectedValueException('[Syntax Error] line 0, col 64: Error: Expected CrEOF\Geo\WKT\Lexer::T_OPEN_PARENTHESIS, got "1" in value "MULTIPOLYGON(((0 0,10 0,10 10,0 10,0 0),(5 5,7 5,7 7,5 7,5 5)),(1 1, 3 1, 3 3, 1 3, 1 1))"')
            ),
            'testParsingGeometryCollectionValue' => array(
                'value' => 'GEOMETRYCOLLECTION(POINT(10 10), POINT(30 30), LINESTRING(15 15, 20 20))',
                'expected' => array(
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
                )
            ),
            'testParsingGeometryCollectionValueWithSrid' => array(
                'value' => 'SRID=4326;GEOMETRYCOLLECTION(POINT(10 10), POINT(30 30), LINESTRING(15 15, 20 20))',
                'expected' => array(
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
                )
            ),
            'testParsingGeometryCollectionValueWithBadType' => array(
                'value' => 'GEOMETRYCOLLECTION(PNT(10 10), POINT(30 30), LINESTRING(15 15, 20 20))',
                'expected' => new UnexpectedValueException('[Syntax Error] line 0, col 19: Error: Expected CrEOF\Geo\WKT\Lexer::T_TYPE, got "PNT" in value "GEOMETRYCOLLECTION(PNT(10 10), POINT(30 30), LINESTRING(15 15, 20 20))"')
            )
        );
    }

    /**
     * @param       $value
     * @param array $expected
     *
     * @dataProvider parserTestData
     */
    public function testParser($value, $expected)
    {
        $parser = new Parser($value);

        try {
            $actual = $parser->parse();
        } catch (\Exception $e) {
            $actual = $e;
        }

        $this->assertEquals($expected, $actual);
    }

    /**
     */
    public function testReusedParser()
    {
        $parser = new Parser();

        foreach ($this->parserTestData() as $name => $testData) {
            try {
                $actual = $parser->parse($testData['value']);
            } catch (\Exception $e) {
                $actual = $e;
            }

            $this->assertEquals($testData['expected'], $actual, 'Failed dataset "'. $name . '"');
        }
    }

}
