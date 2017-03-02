<?php

namespace App\Contracts;

/**
 * Interface CommentableItemDeleteEvent
 * @package App\Contracts
 */
interface CommentableItemDeleteEvent
{

    /**
     * set commentable item properties for comments delete listener
     * call from __construct function
     *
     * public int    commentable_id
     * public string commentable_type
     *
     * @return mixed
     */
    public function setCommentableProperties();
}