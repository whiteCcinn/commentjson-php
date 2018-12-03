<?php

/*
 * (c) caiwenhui <471113744@qq.com>
 *     <https://github.com/whiteCcinn/commentjson-php>
 */

namespace CommentJson\Processor;

/**
 * Class MultiSingleLineCommentJson
 *
 * @package CommentJson
 */
class MultiSingleLineCommentJson extends AbstractProcessor
{
    /**
     * @param string $text
     * @param bool   $filter
     * @param bool   $check
     *
     * @return string
     */
    public function handle(string $text, bool $filter = true, bool $check = true): string
    {
        $regex = '/\s*(#|\/{2}).*$/';
        $regex_inline = '/(:?(?:\s)*([A-Za-z\d\.{}]*)|((?<=\").*\"),?)(?:\s)*(((#|(\/{2})).*)|)$/';

        $lines = explode(PHP_EOL, $text);

        foreach ($lines as $index => $line) {
            if (preg_match($regex, $line)) {
                if (preg_match(substr_replace($regex, '^', 1, 0) . 'i', $line)) {
                    $lines[$index] = '';
                } elseif (preg_match($regex_inline, $line)) {
                    $lines[$index] = preg_replace($regex_inline, '\1', $line);
                }
            }
        }

        if ($filter) {
            $lines = array_filter($lines, function ($item) {
                if (empty(trim($item))) {
                    return false;
                }

                return true;
            });
        }

        $json = implode(PHP_EOL, $lines);

        if ($check && is_null(json_decode($json, true)) && json_last_error() !== JSON_ERROR_NONE) {
            if ($this->next instanceof AbstractProcessor) {
                return $this->next->handle($text);
            } else {
                return '';
            }
        }

        return $json;
    }
}