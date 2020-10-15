<?php

namespace Lib\Http;

final class Session
{
    /**
     * Inicia a sessão quando instanciado.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->start();
    }

    /**
     * Inicia uma nova sessão ou resume uma sessão existente.
     * 
     * @param  array|null  $options
     * @return bool
     */
    public function start(?array $options = null): bool
    {
        return $options ? @session_start($options): @session_start();
    }

    /**
     * Deleta uma variável de sessão.
     * 
     * @param  string  $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        if (isset($_SESSION[$id])) {
            unset($_SESSION[$id]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Destrói todos os dados registrados em uma sessão.
     * 
     * @return bool
     */
    public function destroy(): bool
    {
        return session_destroy();
    }

    /**
     * Descarta as alterações no array da sessão e encerra a sessão.
     * 
     * @return void
     */
    public function abort(): void
    {
        $this->start();
        session_abort();
    }

    /**
     * Codifica os dados atuais da sessão como uma sessão codificada em formato string.
     * 
     * @return string
     */
    public function encode(): string
    {
        $this->start();
        return session_encode();
    }

    /**
     * Decodifica dados de sessão de uma sessão codificada em formato string.
     * 
     * @return bool
     */
    public function decode(string $data): bool
    {
        $this->start();
        return session_decode($data);
    }

    /**
     * Obtém ou define o id de sessão atual.
     * 
     * @return string
     */
    public function id(string $id = null): string
    {
        $this->start();
        return session_id($id);
    }

    /**
     * Atualiza o id da sessão atual com um novo id gerado.
     * 
     * @param  bool  $delete_old_session
     * @return bool
     */
    public function regenerateID(bool $delete_old_session = false): bool
    {
        $this->start();
        return session_regenerate_id($delete_old_session);
    }

    /**
     * Reinicializa um array de sessão com os valores originais.
     * 
     * @return void
     */
    public function reset(): void
    {
        $this->start();
        session_reset();
    }

    /**
     * Obtém e/ou define o nome da sessão atual.
     * 
     * @param  string|null  $name
     * @return string
     */
    public function name(?string $name = null): string
    {
        $this->start();
        return session_name($name);
    }

    /**
     * Retorna o status atual da sessão.
     * 
     * @return int
     */
    public function status(): int
    {
        $this->start();
        return session_status();
    }

    /**
     * Guarda os dados de sessão e fecha a sessão.
     * 
     * @return void
     */
    public function close(): void
    {
        $this->start();
        session_write_close();
    }

    /**
     * Define os dados e os salva.
     * 
     * @return void
     */
    public function save(): void
    {
        $values = (array) get_object_vars($this);

        foreach ($values as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    /**
     * Retorna todos os dados da $_SESSION.
     * 
     * @return object
     */
    public function all(): object
    {
        return (object) $_SESSION;
    }

    /**
     * Retorna todos os dados da $_SESSION em um array associativo.
     * 
     * @return array
     */
    public function allAssoc(): array
    {
        return $_SESSION;
    }

    /**
     * Salva e fecha a sessão.
     * 
     * @return void
     */
    public function __destruct()
    {
        $this->save();
        $this->close();
    }
}
