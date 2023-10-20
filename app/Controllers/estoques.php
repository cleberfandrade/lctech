<?php
namespace App\Controllers;

use App\Models\Empresas;
use App\Models\Estoques as ModelsEstoques;
use App\Models\Fornecedores;
use App\Models\Produtos;
use Libraries\Util;
use Core\View;
use App\Models\Usuarios;
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
        $Usuarios = new Usuarios;
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
    public function gerenciar()
    {
        $this->dados['title'] .= 'GERENCIAR ESTOQUE';
        $Usuarios = new Usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $Estoques = new ModelsEstoques;
        $UsuariosEmpresa = new UsuariosEmpresa;
        $Empresa = new Empresas;
        $Produtos = new Produtos;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);

        $dados = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        $dados = explode("/",$dados['url']);

        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
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
        }
        if (isset($dados[0]) && $dados[0] == 'estoques' && isset($dados[1]) && $dados[1] == 'gerenciar') {
            
            if (isset($dados[2]) && $dados[2] != '' && isset($dados[3]) && $dados[3] != '') {

                if (isset($_SESSION['EMP_COD']) && $_SESSION['EMP_COD'] == $dados[2]){
                    $Estoques->setCodEmpresa($dados[2]);
                    $Estoques->setCodigo($dados[3]);
                    $this->dados['estoque'] = $Estoques->listar(0);

                    $Produtos->setCodEstoque($dados[3]);
                    $Produtos->setCodEmpresa($dados[2]);
                    $this->dados['produtos'] = $Estoques->listarProdutosEstoque(0);
                    $this->render('admin/estoques/gerenciar', $this->dados);

                }else {
                    
                    Sessao::alert('ERRO',' 2- Acesso inválido!','fs-4 alert alert-danger');
                    $Estoques->setCodEmpresa($_SESSION['EMP_COD']);
                    $this->dados['estoques'] = $Estoques->listarTodos(0);

                    $Produtos->setCodEstoque($dados[3]);
                    $Produtos->setCodEmpresa($_SESSION['EMP_COD']);
                    $this->dados['produtos'] = $Estoques->listarProdutosEstoque(0);

                    $this->render('admin/estoques/estoques', $this->dados);
                }
            }else {
                Sessao::alert('ERRO',' 1- Dados inválido(s)!','fs-4 alert alert-danger');

                $Estoques->setCodEmpresa($_SESSION['EMP_COD']);
                $this->dados['estoques'] = $Estoques->listarTodos(0);
                $this->dados['produtos'] = 0;
                $this->render('admin/estoques/estoques', $this->dados);
            }
        }else {
            Sessao::alert('ERRO',' 1- Dados inválido(s)!','fs-4 alert alert-danger');
            $Estoques->setCodEmpresa($_SESSION['EMP_COD']);
            $this->dados['estoques'] = $Estoques->listarTodos(0);
            $this->dados['produtos'] = 0;
            $this->render('admin/estoques/estoques', $this->dados);
        }
    }
    //PRODUTOS
    public function produtos()
    {
        $this->dados['title'] .= 'GERENCIAR PRODUTOS';
        $Usuarios = new Usuarios;
        $UsuariosEmpresa = new UsuariosEmpresa;
        $Empresa = new Empresas;
        $Estoques = new ModelsEstoques;
        $Produtos = new Produtos;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
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
        }
        $dados = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        $dados = explode("/",$dados['url']);
        
        if (isset($dados[2]) && $dados[2] != '' && isset($dados[3]) && $dados[3] != '') {
           
            
            if (isset($_SESSION['EMP_COD']) && $_SESSION['EMP_COD'] == $dados[2]){
                
                //Verificando a existencia do estoque informado
                $Estoques->setCodEmpresa($dados[2]);
                $Estoques->setCodigo($dados[3]);
                $this->dados['estoque'] = $Estoques->listar(0);
                //Buscando produtos deste estoque
                $Produtos->setCodEstoque($this->dados['estoque']['EST_COD']);
                $Produtos->setCodEmpresa($this->dados['estoque']['EMP_COD']);
                $this->dados['produtos'] = $Produtos->listarTodos(0);
               
                $this->render('admin/estoques/produtos/listar', $this->dados);

            }else {
                Sessao::alert('ERRO',' 2- Acesso inválido!','fs-4 alert alert-danger');
                $Estoques->setCodEmpresa($_SESSION['EMP_COD']);
                $this->dados['estoques'] = $Estoques->listarTodos(0);

                $this->render('admin/estoques/estoques', $this->dados);
            }
        }else {
            Sessao::alert('ERRO',' 1- Acesso inválido(s)!','fs-4 alert alert-danger');
            $Estoques->setCodEmpresa($_SESSION['EMP_COD']);
            $this->dados['estoques'] = $Estoques->listarTodos(0);
            $this->render('admin/estoques/estoques', $this->dados);
        }
    }
    public function cadastro_produtos()
    {
        $this->dados['title'] .= 'CADASTRAR PRODUTOS';
        $Usuarios = new Usuarios;
        $Empresa = new Empresas;
        $Estoques = new ModelsEstoques;
        $UsuariosEmpresa = new UsuariosEmpresa;
        $Fornecedores = new Fornecedores;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
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
        }

        $dados = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        $dados = explode("/",$dados['url']);
        
        if (isset($dados[2]) && $dados[2] != '' && isset($dados[3]) && $dados[3] != '') {
            
            if (isset($_SESSION['EMP_COD']) && $_SESSION['EMP_COD'] == $dados[2]){
                //Verificando a existencia do estoque informado
                $Estoques->setCodEmpresa($dados[2]);
                $Estoques->setCodigo($dados[3]);
                $this->dados['estoque'] = $Estoques->listar(0);
                $Fornecedores->setCodEmpresa($dados[2]);
                $this->$dados['fornecedores'] = $Fornecedores->listarTodos(0);
                $this->render('admin/estoques/produtos/cadastrar', $this->dados);
            }else {
                Sessao::alert('ERRO',' 2- Dados inválido(s)!','fs-4 alert alert-danger');
                $Estoques->setCodEmpresa($_SESSION['EMP_COD']);
                $this->dados['estoques'] = $Estoques->listarTodos(0);
                $this->render('admin/estoques/estoques', $this->dados);
            }
        }else {
            Sessao::alert('ERRO',' 1- Acesso inválido(s)!','fs-4 alert alert-danger');
            $Estoques->setCodEmpresa($_SESSION['EMP_COD']);
            $this->dados['estoques'] = $Estoques->listarTodos(0);
            $this->render('admin/estoques/estoques', $this->dados);
        }
    }
    public function servicos()
    {
        $Usuarios = new usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/estoques/servicos', $this->dados);
    }
}