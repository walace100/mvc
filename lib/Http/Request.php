<?php

namespace Lib\Http;

use Lib\Http\Session;
use Lib\Exceptions\GeralException;

final class Request
{
    /**
     * Armazena o atributo a ser verificado se existe.
     * 
     * @var string
     */
    private $has;

    /**
     * Verifica o se o método existe e o chama.
     * 
     * @param  string  $nameMethod
     * @param  mixed  $_
     * @return object|null
     */
    public function __call(string $nameMethod, $_ = null): ?object
    {
        $methods = ['get', 'post', 'any'];

        if (in_array($nameMethod, $methods)) {
            return eval('return $this->$nameMethod();');
        } elseif ($nameMethod === 'session') {
            return $this->session();
        }
        throw new GeralException('Método não existe');
        return null;
    }

    /**
     * Pega os valores do $_GET e retorna o método verifyHas.
     * 
     * @return this
     */
    private function get(): ?object
    {
        $get = (object) $_GET;
        return $this->verifyHas($get);
    }

    /**
     * Pega os valores do $POST e retorna o método verifyHas.
     * 
     * @return this
     */
    private function post(): ?object
    {
        $post = (object) $_POST;
        return $this->verifyHas($post);
    }

    /**
     * Instancia a classe \Lib\Http\Session.
     * 
     * @return \Lib\Http\Session
     */
    private function session(): Session
    {
        $session = new Session();
        return $session;
    }

    /**
     * Define o atributo que será verificado se existe.
     * 
     * @param  string  $has
     * @return this
     */
    public function has(string $has): object
    {
        $this->has = $has;
        return $this;
    }

    /**
     * Verifica se o atributo existe no objeto.
     * 
     * @param  object  $object
     * @return object|null
     */
    private function verifyHas(object $object): ?object
    {
        if (!empty($this->has)) {
            if (\property_exists($object, $this->has)) {
                return $object;
            } else {
                eval('$object->' . $this->has . '= null;');
                return $object;
            }
        }
        return $object;
    }

    public function redirect(string $url): void
    {
        \header('location: ' . $url);
    }
}
