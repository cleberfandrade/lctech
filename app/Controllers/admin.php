<?php
namespace App\Controllers;

use Core\View;
use App\Models\Usuarios;
use Libraries\Sessao;

class admin extends View
{
    private $dados = [];
    public function __construct()
    {
        Sessao::naoLogado();
        $this->dados['title'] = 'PAINEL | LC-TECH';
        
    }
    public function index()
    {
        $Usuarios = new Usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/painel', $this->dados);
    }
    public function painel()
    {
        $Usuarios = new Usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/painel', $this->dados);
    }
}