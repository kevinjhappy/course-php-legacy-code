<?php
declare(strict_types=1);

namespace Legacy\Models;

use Legacy\Core\BaseSQL;
use Legacy\ValueObject\EmailAddress;
use Legacy\ValueObject\FirstName;
use Legacy\ValueObject\LastName;
use Legacy\ValueObject\Password;

class Users extends BaseSQL
{
    private const TABLE_NAME = 'Users';

    public $id = null;
    private $firstname;
    private $lastname;
    private $email;
    private $pwd;

    // role 1 = user classic / role 2 = admin
    private $role=1;

    // status O = inactive / status 1 = active
    private $status=0;

    public function __construct(
        FirstName $firstName,
        LastName $lastName,
        EmailAddress $emailAddress,
        Password $password,
        \PDO $pdo
    ) {
        parent::__construct($pdo, self::TABLE_NAME);

        $this->firstname = $firstName->getFirstName();
        $this->lastname = $lastName->getLastName();
        $this->email = $emailAddress->getEmailAddress();
        $this->pwd = $password->getPassword();
    }

    public function activate()
    {
        if ($this->status === 0) {
            $this->status = 1;
        }
    }

    public function inactivate()
    {
        if ($this->status === 1) {
            $this->status = 0;
        }
    }

    public function upgradeToAdmin()
    {
        $this->role = 2;
    }

    public function downgradeToClassicUser()
    {
        $this->role = 1;
    }

    public function checkIdExist()
    {
        return $this->id !== null;
    }

    public function createId(int $id)
    {
        if (!$this->checkIdExist()) {
            $this->id = $id;
        }
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPwd(): string
    {
        return $this->pwd;
    }

    public function getRole(): int
    {
        return $this->role;
    }

    public function getStatus(): int
    {
        return $this->status;
    }
}
