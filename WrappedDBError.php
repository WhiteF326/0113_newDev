<?php
class WrappedDBError extends RuntimeException
{
    function __construct($message)
    {
        $this->message = $message;
    }
}
