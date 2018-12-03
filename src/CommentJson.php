<?php

/*
 * (c) caiwenhui <471113744@qq.com>
 *     <https://github.com/whiteCcinn/commentjson-php>
 */

namespace CommentJson;

use CommentJson\Processor\ComplexCommentJson;
use CommentJson\Processor\MultiSingleLineCommentJson;

/**
 * Class CommentJson
 *
 * @package CommentJson
 */
class CommentJson implements CommentJsonInterface
{
    /**
     * @param string $text
     * @param bool   $filter
     *
     * @return string
     */
    public static function loads(string $text, bool $filter = true): string
    {
        $chain = (new MultiSingleLineCommentJson())
            ->setNext(new ComplexCommentJson());
        $result = $chain->handle($text, $filter);

        return $result;
    }

    /**
     * @param string $text
     *
     * @return array
     */
    public static function consult(string $text): array
    {
        $stripText = (new MultiSingleLineCommentJson())->handle($text, true, false);

        $result = [];
        $chunk = '';
        foreach (explode(PHP_EOL, $stripText) as $segment) {
            $chunk .= $segment;
            $decodeJson = json_decode($chunk, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $result[] = $decodeJson;
                $chunk = '';
            }
        }

        return $result;
    }

    /**
     * @param $json
     * @param $unescapeUnicode
     * @param $unescapeSlashes
     *
     * @return string
     */
    public static function format($json, $unescapeUnicode, $unescapeSlashes): string
    {
        $result = '';
        $pos = 0;
        $strLen = strlen($json);
        $indentStr = '    ';
        $newLine = "\n";
        $outOfQuotes = true;
        $buffer = '';
        $noescape = true;
        for ($i = 0; $i < $strLen; $i++) {
            // Grab the next character in the string
            $char = substr($json, $i, 1);
            // Are we inside a quoted string?
            if ('"' === $char && $noescape) {
                $outOfQuotes = !$outOfQuotes;
            }
            if (!$outOfQuotes) {
                $buffer .= $char;
                $noescape = '\\' === $char ? !$noescape : true;
                continue;
            } elseif ('' !== $buffer) {
                if ($unescapeSlashes) {
                    $buffer = str_replace('\\/', '/', $buffer);
                }
                if ($unescapeUnicode && function_exists('mb_convert_encoding')) {
                    // https://stackoverflow.com/questions/2934563/how-to-decode-unicode-escape-sequences-like-u00ed-to-proper-utf-8-encoded-cha
                    $buffer = preg_replace_callback('/(\\\\+)u([0-9a-f]{4})/i', function ($match) {
                        $l = strlen($match[1]);
                        if ($l % 2) {
                            $code = hexdec($match[2]);
                            // 0xD800..0xDFFF denotes UTF-16 surrogate pair which won't be unescaped
                            // see https://github.com/composer/composer/issues/7510
                            if (0xD800 <= $code && 0xDFFF >= $code) {
                                return $match[0];
                            }

                            return str_repeat('\\', $l - 1) . mb_convert_encoding(
                                    pack('H*', $match[2]),
                                    'UTF-8',
                                    'UCS-2BE'
                                );
                        }

                        return $match[0];
                    }, $buffer);
                }
                $result .= $buffer . $char;
                $buffer = '';
                continue;
            }
            if (':' === $char) {
                // Add a space after the : character
                $char .= ' ';
            } elseif ('}' === $char || ']' === $char) {
                $pos--;
                $prevChar = substr($json, $i - 1, 1);
                if ('{' !== $prevChar && '[' !== $prevChar) {
                    // If this character is the end of an element,
                    // output a new line and indent the next line
                    $result .= $newLine;
                    for ($j = 0; $j < $pos; $j++) {
                        $result .= $indentStr;
                    }
                } else {
                    // Collapse empty {} and []
                    $result = rtrim($result);
                }
            }
            $result .= $char;
            // If the last character was the beginning of an element,
            // output a new line and indent the next line
            if (',' === $char || '{' === $char || '[' === $char) {
                $result .= $newLine;
                if ('{' === $char || '[' === $char) {
                    $pos++;
                }
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }
        }

        return $result;
    }
}