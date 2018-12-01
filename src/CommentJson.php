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
}