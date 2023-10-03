<?php
namespace App\Controllers;

use App\Models\Empresas;
use Core\View;
use App\Models\Usuarios;
use App\Models\UsuariosEmpresa;
use Libraries\Sessao;

class admin extends View
{
    private $dados = [];
    public function __construct()
    {
        Sessao::naoLogado();
        $this->dados['title'] = 'PAINEL | LC-TECH';
        $Usuarios = new Usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        
        $UsuariosEmpresa = new UsuariosEmpresa;
        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuarios_empresa'] = $UsuariosEmpresa->checarUsuario();
        if ($this->dados['usuarios_empresa'][0]['UMP_COD']) {
            $Empresa = new Empresas;
            $_SESSION['EMP_COD'] = $this->dados['usuarios_empresa'][0]['EMP_COD'];
            $Empresa->setCodigo($_SESSION['EMP_COD']);
            $this->dados['empresa'] = $Empresa->listarEmpresas(0);
        }
    }
    public function index()
    {
       
        $this->render('admin/painel', $this->dados);

    }
    public function painel()
    {
        $this->render('admin/painel', $this->dados);
    }
}