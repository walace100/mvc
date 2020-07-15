<?php

namespace Lib\Http;

final class Request
{

    private $method;

    public function __construct($method)
    {
        $this->method = \strtolower($method);
    }

    public function __call($nameMethod, $_ = null): ?object
    {
        if ($nameMethod === $this->method) {
            return eval('return $this->' . $this->method . '();');
        }
    }

    private function get(): ?object
    {
        $get = (object) $_GET;
        return $get;
    }

    private function post(): ?object
    {
        $post = (object) $_POST;
        return $post;
    }

    public function has(): object
    {
        return $this;
    }
}
