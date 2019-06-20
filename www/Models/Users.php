<?php
declare(strict_types=1);

namespace Legacy\Models;

use Legacy\Core\BaseSQL;
use Legacy\Core\Routing;
use Legacy\ValueObject\EmailAddress;
use Legacy\ValueObject\FirstName;
use Legacy\ValueObject\LastName;
use Legacy\ValueObject\Password;

class Users extends BaseSQL
{
    public $id = null;
    public $firstname;
    public $lastname;
    public $email;
    public $pwd;

    // role 1 = user classic / role 2 = admin
    public $role=1;

    // status O = inactive / status 1 = active
    public $status=0;

    public function __construct(
        FirstName $firstName,
        LastName $lastName,
        Password $password,
        EmailAddress $emailAddress
    ) {
        parent::__construct();

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

    public function getRegisterForm()
    {
        return [
                    "config"=>[
                        "method"=>"POST",
                        "action"=>Routing::getSlug("Users", "save"),
                        "class"=>"",
                        "id"=>"",
                        "submit"=>"S'inscrire",
                        "reset"=>"Annuler" ],

                    "data"=>[

                            "firstname"=>[
                                "type"=>"text",
                                "placeholder"=>"Votre Prénom",
                                "required"=>true,
                                "class"=>"form-control",
                                "id"=>"firstname",
                                "minlength"=>2,
                                "maxlength"=>50,
                                "error"=>"Le prénom doit faire entre 2 et 50 caractères"
                            ],

                            "lastname"=>["type"=>"text","placeholder"=>"Votre nom", "required"=>true, "class"=>"form-control", "id"=>"lastname","minlength"=>2,"maxlength"=>100,
                                "error"=>"Le nom doit faire entre 2 et 100 caractères"],

                            "email"=>["type"=>"email","placeholder"=>"Votre email", "required"=>true, "class"=>"form-control", "id"=>"email","maxlength"=>250,
                                "error"=>"L'email n'est pas valide ou il dépasse les 250 caractères"],

                            "pwd"=>["type"=>"password","placeholder"=>"Votre mot de passe", "required"=>true, "class"=>"form-control", "id"=>"pwd","minlength"=>6,
                                "error"=>"Le mot de passe doit faire au minimum 6 caractères avec des minuscules, majuscules et chiffres"],

                            "pwdConfirm"=>["type"=>"password","placeholder"=>"Confirmation", "required"=>true, "class"=>"form-control", "id"=>"pwdConfirm", "confirm"=>"pwd", "error"=>"Les mots de passe ne correspondent pas"]

                    ]

                ];
    }

    public function getLoginForm()
    {
        return [
                    "config"=>[
                        "method"=>"POST",
                        "action"=>"",
                        "class"=>"",
                        "id"=>"",
                        "submit"=>"Se connecter",
                        "reset"=>"Annuler" ],


                    "data"=>[

                            "email"=>["type"=>"email","placeholder"=>"Votre email", "required"=>true, "class"=>"form-control", "id"=>"email",
                                "error"=>"L'email n'est pas valide"],

                            "pwd"=>["type"=>"password","placeholder"=>"Votre mot de passe", "required"=>true, "class"=>"form-control", "id"=>"pwd",
                                "error"=>"Veuillez préciser un mot de passe"]


                    ]

                ];
    }
}
