# creof/wkt-parser

[![Code Climate](https://codeclimate.com/github/creof/wkt-parser/badges/gpa.svg)](https://codeclimate.com/github/creof/wkt-parser)
[![Test Coverage](https://codeclimate.com/github/creof/wkt-parser/badges/coverage.svg)](https://codeclimate.com/github/creof/wkt-parser/coverage)
[![Build Status](https://travis-ci.org/creof/wkt-parser.svg?branch=master)](https://travis-ci.org/creof/wkt-parser)

Lexer and parser library for WKT/EWKT spatial object strings.

## Usage

Pass value to be parsed in the constructor, then call parse() on the created object.

```php
$input = 'POLYGON((0 0,10 0,10 10,0 10,0 0))';
$parser = new Parser($input);
$value  = $parser->parse();
```

## Return

The parser will return an array with the keys ```srid```, ```type```, and ```value```.
- ```srid``` is the SRID if EWKT was passed in the constructor, null otherwise.
- ```type``` is the spatial object type.
- ```value``` will contain an array with 2 numeric values, or nested arrays containing these depending on the spatial object type.
