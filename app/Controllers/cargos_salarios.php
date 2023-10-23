<?php
namespace App\Controllers;

use App\Models\Empresas;
use App\Models\Usuarios;
use App\Models\UsuariosEmpresa;
use Libraries\Check;
use Libraries\Sessao;

trait cargos_salarios
{
    private $dados = [];
    public $link,$Enderecos,$Usuarios,$Empresa,$UsuariosEmpresa,$Check;
    public function __construct()
    {
        Sessao::naoLogado();
        $this->dados['title'] = 'MÓDULO | CADASTROS >>';
        $this->Usuarios = new Usuarios;
        $this->Empresa = new Empresas;
        $this->UsuariosEmpresa = new UsuariosEmpresa;
        $this->Check = new Check;
        $this->link[0] = ['link'=> 'admin','nome' => 'PAINEL'];
        $this->link[1] = ['link'=> 'cadastros','nome' => 'MÓDULO DE CADASTROS'];
    }
    public function index()
    {
        $this->Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $this->Usuarios->listar(0);
        $this->Check->setLink($this->link);
        $this->dados['breadcrumb'] = $this->Check->breadcrumb();
        $this->render('admin/cadastros/cargos_salarios/listar', $this->dados);
    }
    public function listar()
    {

    }
} 