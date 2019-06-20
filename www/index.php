<?php
namespace Legacy;

use Legacy\Controllers\PagesController;
use Legacy\Core\Routing;

require __DIR__.'/vendor/autoload.php';

require "conf.inc.php";

/*
function myAutoloader($class){
    $classPath = "Core/".$class.".class.php";
    $classModel = "Models/".$class.".class.php";
    if(file_exists($classPath)){
        include $classPath;
    }else if(file_exists($classModel)){
        include $classModel;
    }
}

// La fonction myAutoloader est lancé sur la classe appelée n'est pas trouvée
spl_autoload_register("Legacy\myAutoloader");
*/

// Récupération des paramètres dans l'url - Routing
$slug = explode("?", $_SERVER["REQUEST_URI"])[0];
$routes = Routing::getRoute($slug);
var_dump($routes);
extract($routes);


$pageController = new PagesController();
$pageController->defaultAction();

// Vérifie l'existence du fichier et de la classe pour charger le controlleur
if (file_exists($cPath)) {
    include $cPath;
    if (class_exists($c, true)) {
        //instancier dynamiquement le controller
        $cObject = new $c();
        //vérifier que la méthode (l'action) existe
        if (method_exists($cObject, $a)) {
            //appel dynamique de la méthode
            $cObject->$a();
        } else {
            die("La methode ".$a." n'existe pas");
        }
    } else {
        die("La class controller ".$c." n'existe pas");
    }
} else {
    die("Le fichier controller ".$c." n'existe pas");
}
