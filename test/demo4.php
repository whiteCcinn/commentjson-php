<?php

require_once dirname(__FILE__, 2) . '/vendor/autoload.php';

$json3 = file_get_contents(__DIR__ . '/consult/json');

$result = \CommentJson\CommentJson::consult($json3);

$result = \CommentJson\CommentJson::format(json_encode($result), false, false);

var_export($result);

/*
[
    {
        "name": "Vaidik Kapoor",
        "location": "Delhi, India",
        "appearance": {
            "hair_color": "black",
            "eyes_color": "black",
            "height": "6"
        }
    },
    {
        "name": "Vaidik Kapoor",
        "location": "Delhi, India",
        "appearance": {
            "hair_color": "black",
            "eyes_color": "black",
            "height": "6"
        }
    },
    [
        {
            "name": "Vaidik Kapoor",
            "location": "Delhi, India",
            "appearance": {
                "hair_color": "black",
                "eyes_color": "black",
                "height": "6"
            }
        }
    ]
]
*/