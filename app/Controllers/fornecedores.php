<?php
namespace App\Controllers;

use App\Models\Clientes as ModelsCliente;
use App\Models\Empresas;
use App\Models\Enderecos;
use App\Models\Fornecedores as ModelsFornecedores;
use App\Models\Usuarios;
use App\Models\UsuariosEmpresa;
use Core\View;
use Libraries\Check;
use Libraries\Sessao;

class fornecedores extends View
{
    private $dados = [];
    public $link,$Enderecos,$Usuarios,$Empresa,$UsuariosEmpresa,$Check,$Clientes, $Fornecedores;
    public function __construct()
    {
        Sessao::naoLogado();
        $this->dados['title'] = 'MÓDULO | CADASTROS >>';
        $this->Clientes= new ModelsCliente;
        $this->Empresa = new Empresas;
        $this->Enderecos = new Enderecos;
        $this->Usuarios = new Usuarios;
        $this->UsuariosEmpresa = new UsuariosEmpresa;
        $this->Fornecedores = new ModelsFornecedores;
        $this->Check = new Check;
        $this->dados['empresa'] = $this->UsuariosEmpresa->setCodEmpresa($_SESSION['EMP_COD'])->setCodUsuario($_SESSION['USU_COD'])->listar(0);
        $this->dados['usuario'] = $this->Usuarios->setCodUsuario($_SESSION['USU_COD'])->listar(0);
        $this->dados['fornecedores'] = $this->Fornecedores->setCodEmpresa($_SESSION['EMP_COD'])->listarTodos(0);
        $this->link[0] = ['link'=> 'admin','nome' => 'PAINEL ADMINISTRATIVO'];
        $this->link[1] = ['link'=> 'cadastros','nome' => 'MÓDULO DE CADASTROS'];
        $this->link[2] = ['link'=> 'fornecedores','nome' => 'GERENCIAR SEUS FORNECEDORES'];
    }
    public function index()
    {
        $this->dados['title'] .= ' FORNECEDORES';
        $this->dados['breadcrumb'] = $this->Check->setLink($this->link)->breadcrumb();
        $this->render('admin/cadastros/fornecedores/listar', $this->dados);
    }
    public function cadastro():void
    {
        $this->dados['title'] .= ' CADASTRAR SEUS FORNECEDORES';
        $this->link[3] = ['link'=> 'fornecedores/cadastrar','nome' => 'CADASTRAR SEUS FORNECEDORES'];
        $this->dados['breadcrumb'] = $this->Check->setLink($this->link)->breadcrumb();
        $this->render('admin/cadastros/fornecedores/cadastrar', $this->dados);
    }
}