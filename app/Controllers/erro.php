<?php
namespace App\Controllers;

use Core\View;

class erro extends View
{
    private $dados = [];
    public function __construct()
    {
       
    }
    public function index()
    { 
        $this->dados["erro 404"];
        $this->render('site/erro', $this->dados);
    }
}