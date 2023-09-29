<?php
namespace App\Controllers;

use Libraries\Util;
use Core\View;
use App\Models\usuarios;
use Libraries\Sessao;

class configuracoes extends View
{
    private $dados = [];
    public function __construct()
    {
        Sessao::naoLogado();
    }
    public function index()
    {
        $Usuarios = new usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/estoque/estoque', $this->dados);
    }
    public function produtos()
    {
        $Usuarios = new usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/estoque/produtos', $this->dados);
    }
    public function servicos()
    {
        $Usuarios = new usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/estoque/servicos', $this->dados);
    }
}