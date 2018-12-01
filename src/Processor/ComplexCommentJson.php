<?php

/*
 * (c) caiwenhui <471113744@qq.com>
 *     <https://github.com/whiteCcinn/commentjson-php>
 */

namespace CommentJson\Processor;

class ComplexCommentJson extends AbstractProcessor
{
    /** @var int The current index being scanned */
    private $index = -1;
    /** @var bool If current char is within a string */
    private $inStr = false;
    /** @var int Lines of comments 0 = no comment, 1 = single line, 2 = multi lines */
    private $comment = 0;

    /**
     * Strip comments from JSON string.
     *
     * @param string $json
     * @param bool   $filter
     *
     * @return mixed|string
     */
    public function handle(string $json, bool $filter = true): string
    {
        if (!\preg_match('%\/(\/|\*)%', $json)) {
            return $json;
        }
        $this->reset();

        return $this->doStrip($json);
    }

    /**
     * reset()
     */
    private function reset(): void
    {
        $this->index = -1;
        $this->inStr = false;
        $this->comment = 0;
    }

    /**
     * @param string $json
     *
     * @return string
     */
    private function doStrip(string $json): string
    {
        $return = '';
        while (isset($json[++$this->index])) {
            list($prev, $char, $next) = $this->getSegments($json);
            if ($this->inStringOrCommentEnd($prev, $char, $char . $next)) {
                $return .= $char;
                continue;
            }
            $wasSingle = 1 === $this->comment;
            if ($this->hasCommentEnded($char, $char . $next) && $wasSingle) {
                $return = \rtrim($return) . $char;
            }
            $this->index += $char . $next === '*/' ? 1 : 0;
        }

        if (is_null(json_decode($return, true)) && json_last_error() !== JSON_ERROR_NONE) {
            if ($this->next instanceof AbstractProcessor) {
                return $this->next->handle($json);
            } else {
                return '';
            }
        }

        return $return;
    }

    /**
     * @param string $json
     *
     * @return array
     */
    private function getSegments(string $json): array
    {
        return [
            isset($json[$this->index - 1]) ? $json[$this->index - 1] : '',
            $json[$this->index],
            isset($json[$this->index + 1]) ? $json[$this->index + 1] : '',
        ];
    }

    /**
     * @param $prev
     * @param $char
     * @param $charnext
     *
     * @return bool
     */
    private function inStringOrCommentEnd($prev, $char, $charnext)
    {
        return $this->inString($char, $prev) || $this->inCommentEnd($charnext);
    }

    /**
     * @param $char
     * @param $prev
     *
     * @return bool
     */
    private function inString($char, $prev)
    {
        if (0 === $this->comment && $char === '"' && $prev !== '\\') {
            $this->inStr = !$this->inStr;
        }

        return $this->inStr;
    }

    /**
     * @param $charnext
     *
     * @return bool
     */
    private function inCommentEnd($charnext)
    {
        if (!$this->inStr && 0 === $this->comment) {
            $this->comment = $charnext === '//' ? 1 : ($charnext === '/*' ? 2 : 0);
        }

        return 0 === $this->comment;
    }

    /**
     * @param $char
     * @param $charnext
     *
     * @return bool
     */
    private function hasCommentEnded($char, $charnext)
    {
        $singleEnded = $this->comment === 1 && $char == "\n";
        $multiEnded = $this->comment === 2 && $charnext == '*/';
        if ($singleEnded || $multiEnded) {
            $this->comment = 0;

            return true;
        }

        return false;
    }
}