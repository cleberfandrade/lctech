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
        $this->render('admin/configuracoes/configuracoes', $this->dados);
    }
    public function empresa()
    {
        $Usuarios = new usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/configuracoes/empresa', $this->dados);
    }
    public function modulos()
    {
        $Usuarios = new usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/configuracoes/modulos', $this->dados);
    }
    public function sistema()
    {
        $Usuarios = new usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/configuracoes/sistema', $this->dados);
    }
    public function usuarios()
    {
        $Usuarios = new usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/configuracoes/usuarios', $this->dados);
    }
}