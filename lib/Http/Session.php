<?php

namespace Lib\Http;

final class Session
{
    public function __construct()
    {
        $this->start();
    }

    public function start(?array $options = null): bool
    {
        return $options ? @session_start($options): @session_start();
    }

    public function delete(string $id): bool
    {
        if (isset($_SESSION[$id])) {
            unset($_SESSION[$id]);
            return true;
        } else {
            return false;
        }
    }

    public function destroy(): bool
    {
        return session_destroy();
    }

    public function abort(): void
    {
        $this->start();
        session_abort();
    }

    public function encode(): string
    {
        $this->start();
        return session_encode();
    }

    public function decode($data): bool
    {
        $this->start();
        return session_decode($data);
    }

    public function id(string $id = null): string
    {
        $this->start();
        return session_id($id);
    }

    public function regenerateID(bool $delete_old_session = false): bool
    {
        $this->start();
        return session_regenerate_id($delete_old_session);
    }

    public function reset(): void
    {
        $this->start();
        session_reset();
    }

    public function name(?string $name = null): string
    {
        $this->start();
        return session_name($name);
    }

    public function status(): int
    {
        $this->start();
        return session_status();
    }

    public function close(): void
    {
        $this->start();
        session_write_close();
    }

    public function save()
    {
        $values = (array) get_object_vars($this);

        foreach ($values as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    public function all(): object
    {
        return (object) $_SESSION;
    }

    public function allAssoc(): array
    {
        return $_SESSION;
    }

    public function __destruct()
    {
        $this->save();
        $this->close();
    }
}
