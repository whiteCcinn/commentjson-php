<?php

namespace CommentJson\Processor;

/**
 * Class AbstractProcessor
 *
 * @package CommentJson\Processor
 */
abstract class AbstractProcessor
{
    /** @var AbstractProcessor */
    protected $next;

    /**
     * @param AbstractProcessor $l
     *
     * @return $this
     */
    public function setNext(AbstractProcessor $l)
    {
        $this->next = $l;

        return $this;
    }

    /**
     * @param string $text
     * @param bool   $filter
     * @param bool   $check
     *
     * @return mixed
     */
    abstract public function handle(string $text ,bool $filter = true, bool $check = true);
}