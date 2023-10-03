<?php 
namespace App\Controllers;

use App\Models\Usuarios;
use App\Models\UsuariosEmpresa;
use Core\View;
use Libraries\Sessao;

class financeiro extends View
{
    private $dados = [];
    public function __construct()
    {
        Sessao::naoLogado();
        $this->dados['title'] = 'MÓDULO | FINANCEIRO >>';   
    }
    public function index()
    {
        $Usuarios = new Usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);

        $UsuariosEmpresa = new UsuariosEmpresa;
        $UsuariosEmpresa->setCodUsuario($this->dados['usuario'][0]['USU_COD']);
        $this->dados['empresa'] = $UsuariosEmpresa->checarEmpresaUsuario();

        $this->render('admin/financeiro/financeiro', $this->dados);
    }
}
