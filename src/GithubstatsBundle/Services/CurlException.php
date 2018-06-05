<?php
namespace GithubstatsBundle\Services;

class CurlException extends \RuntimeException
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message, $code, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
