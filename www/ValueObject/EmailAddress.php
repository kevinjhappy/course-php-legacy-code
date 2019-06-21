<?php

namespace Legacy\ValueObject;


final class EmailAddress
{
    private $emailAddress;

    public function __construct(string $email)
    {
        $this->emailAddress = strtolower(trim($email));
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }
}
