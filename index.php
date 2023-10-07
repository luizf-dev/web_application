<?php

//*Carregamento automatico das classes
require_once("vendor/autoload.php");

//*Namespaces
use Slim\Slim;
use application\Page;


$app = new Slim();

$app->config('debug', true);

//* ====== Rota Index ======**/
$app->get('/', function(){

    $page = new Page(["navbar"=>false, "footer"=>false]);
    $page->renderPage("index");

});

$app->run();