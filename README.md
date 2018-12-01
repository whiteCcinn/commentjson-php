commentjson-php
===========

Similar to python commentjson，More powerful，use PHP implementation

Installation
============

    composer require ccinn/commentjson-php

Basic Usage
===========

```php
$json = <<<JSON
{\\n
     "name": "Vaidik Kapoor", # Person's name\\n
     "location": "Delhi, India", // Person's location\\n
\\n
     # Section contains info about\\n
     // person's appearance\\n
     "appearance": {\\n
         "hair_color": "black",\\n
         "eyes_color": "black",\\n
         "height": "6"\\n
     }\\n
 }\\n
JSON;

$result = \CommentJson\CommentJson::loads($json);

var_dump(json_decode($result, true));

/**
array(3) {
    ["name"]=>
        string(13) "Vaidik Kapoor"
    ["location"]=>
        string(12) "Delhi, India"
    ["appearance"]=>
    array(3) {
        ["hair_color"]=>
            string(5) "black"
        ["eyes_color"]=>
            string(5) "black"
        ["height"]=>
            string(1) "6"
    }
}
 */
 
 
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

```
