<?php
declare(strict_types=1);

namespace Legacy\ValueObject;


final class Password
{
    private $password;

    public function __construct(string $password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
