<?php

namespace Legacy\ValueObject;

final class FirstName
{
    private $firstName;

    public function __construct(string $firstName)
    {
        $this->firstName = ucwords(strtolower(trim($firstName)));
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }
}
