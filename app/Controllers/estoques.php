<?php
namespace App\Controllers;

use App\Models\Empresas;
use App\Models\Estoques as ModelsEstoques;
use Libraries\Util;
use Core\View;
use App\Models\usuarios;
use App\Models\UsuariosEmpresa;
use Libraries\Sessao;

class estoques extends View
{
    private $dados = [];
    public function __construct()
    {
        Sessao::naoLogado();
        $this->dados['title'] = 'MÓDULO | ESTOQUES >>';
    }
    public function index()
    {
        $this->dados['title'] .= 'ACESSAR';
        $Usuarios = new usuarios;
        $Empresa = new Empresas;
        $Estoques = new ModelsEstoques;
        $UsuariosEmpresa = new UsuariosEmpresa;

        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuarios_empresa'] = $UsuariosEmpresa->checarUsuario();
        if (isset($this->dados['usuarios_empresa']['UMP_COD'])) {
            $qtd = (is_array($this->dados['usuarios_empresa']['UMP_COD']) ? count($this->dados['usuarios_empresa']['UMP_COD']) : 0);
            if($qtd == 1) {
                $_SESSION['EMP_COD'] = $this->dados['usuarios_empresa']['EMP_COD'];
                $Empresa->setCodigo($_SESSION['EMP_COD']);
                $this->dados['empresas'] = $Empresa->listar(0);
            }else {
                $Empresa->setCodigo($_SESSION['EMP_COD']);
                $Empresa->setCodUsuario($_SESSION['USU_COD']);
                $this->dados['empresas'] = $Empresa->listarEmpresaUsuario(0);
            }
            $Estoques->setCodEmpresa($_SESSION['EMP_COD']);
            $this->dados['estoques'] = $Estoques->listarTodos(0);
        }

        $this->render('admin/estoques/estoques', $this->dados);
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