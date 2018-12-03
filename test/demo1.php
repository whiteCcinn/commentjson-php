<?php

require_once dirname(__FILE__, 2) . '/vendor/autoload.php';

$json = <<<JSON
{\n
     "name": "Vaidik Kapoor", # Person's name\n
     "location": "Delhi, India", // Person's location\n
\n
     # Section contains info about\n
     // person's appearance\n
     "appearance": {\n
         "hair_color": "black",\n
         "eyes_color": "black",\n
         "height": "6"\n
     }\n
 }\n
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