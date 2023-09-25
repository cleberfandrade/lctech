<?php
namespace App\Controllers;

use Libraries\Util;
use Core\View;
use App\Models\Usuarios;
use Libraries\Sessao;

class admin extends View
{
    private $dados = [];
    public function __construct()
    {
        $this->dados['title'] = 'PAINEL | LC-TECH';
        Sessao::naoLogado();
    }
    public function index()
    {
        $Usuarios = new Usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/painel', $this->dados);
    }
}