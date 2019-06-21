<?php

namespace Legacy\Controllers;


interface LoginRegisterInterface
{
    public function getRegisterForm(): array;

    public function getLoginForm(): array;
}
