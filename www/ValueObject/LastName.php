<?php

namespace Legacy\ValueObject;


class LastName
{

    private $lastName;

    public function __construct(string $lastName)
    {
        $this->lastName = strtoupper(trim($lastName));
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
}
