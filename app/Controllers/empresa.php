<?php
namespace App\Controllers;

use Core\View;
use Libraries\Check;
use Libraries\Url;
use Libraries\Sessao;
use App\Models\Usuarios;
use App\Models\Enderecos;

class empresa extends View
{
    private $dados = [];
    public function __construct()
    {
        $this->dados['title'] = 'EMPRESA | LC-TECH';
        Sessao::naoLogado();
    }
    public function index()
    { 
        $this->render('admin/configuracoes/empresa', $this->dados);
    }
}