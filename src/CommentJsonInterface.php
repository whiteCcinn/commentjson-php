<?php
/*
 * (c) caiwenhui <471113744@qq.com>
 *     <https://github.com/whiteCcinn/commentjson-php>
 */

namespace CommentJson;


interface CommentJsonInterface
{
    public static function loads(string $text, bool $filter = true): string;

    public static function consult(string $text): array;

    public static function format($json, $unescapeUnicode, $unescapeSlashes): string;
}