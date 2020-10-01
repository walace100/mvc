<?php

namespace Lib\Http;

final class Request
{
    private $method;

    private $has;

    public function __construct($method)
    {
        $this->method = \strtolower($method);
    }

    public function __call(string $nameMethod, $_ = null): ?object
    {
        if ($nameMethod === $this->method || $this->method === 'any') {
            return eval('return $this->$nameMethod();');
        }
    }

    private function get(): ?object
    {
        $get = (object) $_GET;
        return $this->verifyHas($get);
    }

    private function post(): ?object
    {
        $post = (object) $_POST;
        return $this->verifyHas($post);
    }

    public function has(string $has): object
    {
        $this->has = $has;
        return $this;
    }

    private function verifyHas(object $class): ?object
    {
        if (!empty($this->has)) {
            if (\property_exists($class, $this->has)) {
                return $class;
            } else {
                eval('$class->' . $this->has . '= null;');
                return $class;
            }
        }
        return $class;
    }

    public function redirect(string $url): void
    {
        \header('location: ' . $url);
    }
}
