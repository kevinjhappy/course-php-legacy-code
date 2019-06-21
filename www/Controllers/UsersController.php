<?php
declare(strict_types=1);

namespace Legacy\Controllers;

use Legacy\Core\Validator;
use Legacy\Core\View;
use Legacy\Models\FormCreation;
use Legacy\Models\Users;
use Legacy\ValueObject\EmailAddress;
use Legacy\ValueObject\FirstName;
use Legacy\ValueObject\LastName;
use Legacy\ValueObject\Password;

class UsersController
{
    private $formCreation;
    private $pdo;

    public function __construct(
        LoginRegisterInterface $formCreation,
        \PDO $pdo
    ) {
        $this->formCreation = $formCreation;
        $this->pdo = $pdo;
    }

    public function defaultAction()
    {
        echo "users default";
    }
    
    public function addAction()
    {
        $form = $this->formCreation->getRegisterForm();

        $v = new View("addUser", "front");
        $v->assign("form", $form);
    }

    public function saveAction()
    {
        $formCreation = new FormCreation();
        $form = $formCreation->getRegisterForm();
        $method = strtoupper($form["config"]["method"]);
        $data = $GLOBALS["_".$method];


        if ($_SERVER['REQUEST_METHOD']==$method && !empty($data)) {
            $validator = new Validator($form, $data);
            $form["errors"] = $validator->errors;

            if (empty($errors)) {
                $user = new Users(
                    new FirstName($data["firstname"]),
                    new LastName($data["lastname"]),
                    new EmailAddress($data["email"]),
                    new Password($data["pwd"]),
                    $this->pdo
                );
                $user->save();
            }
        }

        $v = new View("addUser", "front");
        $v->assign("form", $form);
    }


    public function loginAction()
    {
        $formCreation = new FormCreation();
        $form = $formCreation->getLoginForm();

        $method = strtoupper($form["config"]["method"]);
        $data = $GLOBALS["_".$method];
        if ($_SERVER['REQUEST_METHOD']==$method && !empty($data)) {
            $validator = new Validator($form, $data);
            $form["errors"] = $validator->errors;

            if (empty($errors)) {
                $token = md5(substr(uniqid().time(), 4, 10)."mxu(4il");
                // TODO: connexion
            }
        }
    
        $v = new View("loginUser", "front");
        $v->assign("form", $form);
    }


    public function forgetPasswordAction()
    {
        $v = new View("forgetPasswordUser", "front");
    }
}
