<?php

require_once dirname(__FILE__, 2) . '/vendor/autoload.php';

$json3 = file_get_contents(__DIR__.'/consult/json');

$result = \CommentJson\CommentJson::consult($json3);

var_dump($result);

/*
array(3) {
    [0]=>
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
  [1]=>
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
  [2]=>
  array(1) {
        [0]=>
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
  }
}
*/