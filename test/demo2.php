<?php

require_once dirname(__FILE__, 2) . '/vendor/autoload.php';

$json2 = <<<JSON
{"a":1,
"b":2,// comment
"c":3 /* inline comment */,
// comment
"d":/* also a comment */"d",
/* creepy comment*/"e":2.3,
/* multi line
comment */
"f":"f1"}
JSON;

$result = \CommentJson\CommentJson::loads($json2);

var_dump(json_decode($result, true));

/**
array(6) {
["a"]=>
int(1)
["b"]=>
int(2)
["c"]=>
int(3)
["d"]=>
string(1) "d"
["e"]=>
float(2.3)
["f"]=>
string(2) "f1"
}
 */
