<?php
namespace App\Controllers;

use Core\View;
use App\Models\Empresas;
use App\Models\Enderecos;
use App\Models\Estoques;
use App\Models\Financas;
use App\Models\Fornecedores;
use App\Models\ModulosEmpresa;
use App\Models\Usuarios;
use App\Models\UsuariosEmpresa;
use App\Models\Vendedores;
use Libraries\Check;
use Libraries\Url;
use Libraries\Sessao;

class cadastros extends View
{
    private $dados = [];
    public function __construct()
    {
        Sessao::naoLogado();
        $this->dados['title'] = 'MÓDULO | CADASTROS >>';
        $Usuarios = new Usuarios;
        $Empresa = new Empresas;
        $UsuariosEmpresa = new UsuariosEmpresa;
        $ModulosEmpresa = new ModulosEmpresa;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuarios_empresa'] = $UsuariosEmpresa->checarUsuario();
        if (isset($this->dados['usuarios_empresa']['UMP_COD'])) {
            $_SESSION['EMP_COD'] = $this->dados['usuarios_empresa']['EMP_COD'];
            $Empresa->setCodigo($_SESSION['EMP_COD']);
            $this->dados['empresa'] = $Empresa->listar(0);
            $ModulosEmpresa->setCodEmpresa($_SESSION['EMP_COD']);
            $this->dados['modulos_empresa'] = $ModulosEmpresa->listar();
        }else {
            $this->dados['modulos_empresa'] = false;
            $this->dados['empresa'] = false;
        }
    }
    public function index()
    {
        $Usuarios = new Usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/cadastros/cadastros', $this->dados);
    }
    //CADASTRO - CLIENTES
    public function clientes()
    {
        $this->dados['title'] .= 'CLIENTES';
        $Usuarios = new Usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/cadastros/clientes/listar', $this->dados);
    }
    public function cadastro_clientes()
    {
        $this->dados['title'] .= 'CLIENTES';
        $Usuarios = new Usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/cadastros/clientes/cadastrar', $this->dados);
    }

    //CADASTRO - FORNECEDORES
    public function fornecedores()
    {
        $this->dados['title'] .= 'FORNECEDORES';
        $Usuarios = new Usuarios;
        $Fornecedores = new Fornecedores;
        $Fornecedores->setCodEmpresa($_SESSION['EMP_COD']);
        $this->dados['fornecedores'] = $Fornecedores->listarTodosEmpresa(0);
        $this->render('admin/cadastros/fornecedores/listar', $this->dados);
    }
    public function cadastro_fornecedores()
    {
        $this->dados['title'] .= 'FORNECEDORES';
        $Usuarios = new Usuarios;
        $Fornecedores = new Fornecedores;

        $Fornecedores->setCodEmpresa($_SESSION['EMP_COD']);
        $this->dados['fornecedores'] = $Fornecedores->listarTodosEmpresa(0);

        $this->render('admin/cadastros/fornecedores/cadastrar', $this->dados);
    }
    //CADASTRO - USUÁRIOS
    public function usuarios()
    {
        $this->dados['title'] .= 'USUÁRIOS';
        $Usuarios = new Usuarios;
        $Empresa = new Empresas;
        $UsuariosEmpresa = new UsuariosEmpresa;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);  
        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuarios_empresa'] = $UsuariosEmpresa->checarUsuario();
        if (isset($this->dados['usuarios_empresa']['UMP_COD'])) {
            $_SESSION['EMP_COD'] = $this->dados['usuarios_empresa']['EMP_COD'];
            $Empresa->setCodigo($_SESSION['EMP_COD']);
            $this->dados['empresa'] = $Empresa->listar(0);
            $UsuariosEmpresa->setCodEmpresa($_SESSION['EMP_COD']);
            $this->dados['usuarios'] = $UsuariosEmpresa->listarTodos(0);
        }
        $this->render('admin/cadastros/usuarios/listar', $this->dados);
    }
    public function cadastro_usuarios()
    {
        $this->dados['title'] .= 'USUÁRIOS';
        $this->render('admin/cadastros/usuarios/cadastrar', $this->dados);
    }
    public function alterar_usuarios()
    {
        $this->dados['title'] .= 'ALTERAR USUÁRIOS';
        $Usuarios = new Usuarios;
        $dados = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        $dados = explode("/",$dados['url']);
        $Usuarios->setCodigo($dados[3]);
        if (isset($dados[1]) && $dados[1] == 'alterar_usuarios') {
            $this->dados['usuario'] = $Usuarios->listar(0);
        }
        $this->render('admin/cadastros/usuarios/alterar', $this->dados);
    }
    public function meus_dados()
    {
        $this->dados['title'] .= 'MEUS DADOS DE USUÁRIO';
        $Usuarios = new Usuarios;
        $Empresa = new Empresas;
        $UsuariosEmpresa = new UsuariosEmpresa;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);

        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuarios_empresa'] = $UsuariosEmpresa->checarUsuario();
        if (isset($this->dados['usuarios_empresa']['UMP_COD'])) {
            $_SESSION['EMP_COD'] = $this->dados['usuarios_empresa']['EMP_COD'];
            $Empresa->setCodigo($_SESSION['EMP_COD']);
            $this->dados['empresa'] = $Empresa->listar(0);
        }
        $this->render('admin/cadastros/usuarios/meus_dados', $this->dados);
    }
    //CADASTRO - VENDEDORES
     public function vendedores()
    {
        $this->dados['title'] .= 'USUÁRIOS';
        $Usuarios = new Usuarios;
        $Empresa = new Empresas;
        $Vendedores = new Vendedores;
        $UsuariosEmpresa = new UsuariosEmpresa;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);  
        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuarios_empresa'] = $UsuariosEmpresa->checarUsuario();
        if (isset($this->dados['usuarios_empresa']['UMP_COD'])) {
            $_SESSION['EMP_COD'] = $this->dados['usuarios_empresa']['EMP_COD'];
            $Empresa->setCodigo($_SESSION['EMP_COD']);
            $this->dados['empresa'] = $Empresa->listar(0);
            $UsuariosEmpresa->setCodEmpresa($_SESSION['EMP_COD']);
            $this->dados['usuarios'] = $UsuariosEmpresa->listarTodos(0);
        }
        $this->render('admin/cadastros/vendedores/listar', $this->dados);
    }
    public function cadastro_vendedores()
    {
        $this->dados['title'] .= 'VENDEDORES';
        $Usuarios = new Usuarios;
        $Empresa = new Empresas;
        $Vendedores = new Vendedores;
        $UsuariosEmpresa = new UsuariosEmpresa;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);  
        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuarios_empresa'] = $UsuariosEmpresa->checarUsuario();
        if (isset($this->dados['usuarios_empresa']['UMP_COD'])) {
            $_SESSION['EMP_COD'] = $this->dados['usuarios_empresa']['EMP_COD'];
            $Empresa->setCodigo($_SESSION['EMP_COD']);
            $this->dados['empresa'] = $Empresa->listar(0);
            $UsuariosEmpresa->setCodEmpresa($_SESSION['EMP_COD']);
            $this->dados['usuarios'] = $UsuariosEmpresa->listarTodos(0);
        }
        $this->render('admin/cadastros/vendedores/cadastrar', $this->dados);
    }
    public function cadastrar_vendedores()
    {
        $this->dados['title'] .= 'VENDEDORES';
        $Usuarios = new Usuarios;
        $Empresa = new Empresas;
        $Vendedores = new Vendedores;
        $UsuariosEmpresa = new UsuariosEmpresa;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);  
        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuarios_empresa'] = $UsuariosEmpresa->checarUsuario();
        if (isset($this->dados['usuarios_empresa']['UMP_COD'])) {
            $_SESSION['EMP_COD'] = $this->dados['usuarios_empresa']['EMP_COD'];
            $Empresa->setCodigo($_SESSION['EMP_COD']);
            $this->dados['empresa'] = $Empresa->listar(0);
            $UsuariosEmpresa->setCodEmpresa($_SESSION['EMP_COD']);
            $this->dados['usuarios'] = $UsuariosEmpresa->listarTodos(0);
        }
         //Recupera os dados enviados
         $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                
         if (isset($_POST) && isset($dados['CADASTRAR_NOVO_VENDEDOR'])) {
             //Verifica se os campos foram todos preenchidos
             unset($dados['CADASTRAR_NOVO_VENDEDOR']);

             $dados += array(
                'VDD_DT_CADASTRO'=> date('Y-m-d H:i:s'),
                'VDD_DT_ATUALIZACAO'=> date('0000-00-00 00:00:00'),             
                'VDD_STATUS'=> 1
            );

            if($Vendedores->cadastrar($dados,0)){
                Sessao::alert('OK','Cadastro efetuado com sucesso!','fs-4 alert alert-success');
            }else{
                Sessao::alert('ERRO',' 3- Erro ao cadastrar novo vendedor, entre em contato com o suporte!','fs-4 alert alert-danger');
            }


         }


        $this->render('admin/cadastros/vendedores/cadastrar', $this->dados);
    }

    //CADASTRO - CATEGORIAS
    public function categorias()
    {
        $Usuarios = new Usuarios;
        $Empresa = new Empresas;
        $UsuariosEmpresa = new UsuariosEmpresa;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        
        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuarios_empresa'] = $UsuariosEmpresa->checarUsuario();
        if (isset($this->dados['usuarios_empresa']['UMP_COD'])) {
            $_SESSION['EMP_COD'] = $this->dados['usuarios_empresa']['EMP_COD'];
            $Empresa->setCodigo($_SESSION['EMP_COD']);
            $this->dados['empresa'] = $Empresa->listar(0);
        }
        $this->render('admin/cadastros/categorias/listar', $this->dados);
    }
    //CADASTRO - ESTOQUES
    public function estoques()
    {
        $this->dados['title'] .= 'ESTOQUES';
        $Usuarios = new Usuarios;
        $Empresa = new Empresas;
        $Estoques = new Estoques;
        $UsuariosEmpresa = new UsuariosEmpresa;
        //buscar dados do usuário
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        //verificar se o usuario tem uma empresa, e retornar os dados
        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuarios_empresa'] = $UsuariosEmpresa->checarUsuario();
        //checar se existe cadastro da empresa, estoque e retornar os dados
        if (isset($this->dados['usuarios_empresa']['UMP_COD'])) {
            $qtd = (is_array($this->dados['usuarios_empresa']['UMP_COD']) ? count($this->dados['usuarios_empresa']['UMP_COD']) : 0);
            if($qtd == 1) {
                $_SESSION['EMP_COD'] = $this->dados['usuarios_empresa']['EMP_COD'];
                $Empresa->setCodigo($_SESSION['EMP_COD']);
                $this->dados['empresa'] = $Empresa->listar(0);
            }
            $Estoques->setCodEmpresa($_SESSION['EMP_COD']);
            $this->dados['estoques'] = $Estoques->listarTodos(0);
        }

        $this->render('admin/cadastros/estoques/listar', $this->dados);
    }
    public function cadastro_estoques()
    {
        $this->dados['title'] .= 'ESTOQUES';
        $Usuarios = new Usuarios;
        $Empresa = new Empresas;
        $Enderecos = new Enderecos;
        $Estoques = new Estoques;
        $UsuariosEmpresa = new UsuariosEmpresa;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        //verificar se o usuario tem uma empresa, e retornar os dados
        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuarios_empresa'] = $UsuariosEmpresa->checarUsuario();
        //checar se existe cadastro da empresa, estoque e retornar os dados
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

        $this->render('admin/cadastros/estoques/cadastrar', $this->dados);
    }
    public function alterar_estoques()
    {
        $Usuarios = new Usuarios;
        $Empresa = new Empresas;
        $Enderecos = new Enderecos;
        $Estoques = new Estoques;
        $UsuariosEmpresa = new UsuariosEmpresa;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);

        $dados = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        $dados = explode("/",$dados['url']);

        if (isset($dados[1]) && $dados[1] == 'alterar_estoques') {
            //verificar se o usuario tem uma empresa, e retornar os dados
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
            $Estoques->setCodigo($dados[3]);
            $Estoques->setCodEmpresa($dados[2]);
            $this->dados['estoque'] = $Estoques->listar(0);
        }
      
        $this->render('admin/cadastros/estoques/alterar', $this->dados);
    }
    public function cadastrar_estoques()
    {
        $this->dados['title'] .= 'ESTOQUES';
        $Usuarios = new Usuarios;
        $Empresa = new Empresas;
        $Estoques = new Estoques;
        $UsuariosEmpresa = new UsuariosEmpresa;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        
        
        //Recupera os dados enviados
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                
        if (isset($_POST) && isset($dados['CRIAR_NOVO_ESTOQUE'])) {
            //Verifica se os campos foram todos preenchidos
            unset($dados['CRIAR_NOVO_ESTOQUE']);

            $dados += array(
                'EST_DT_CADASTRO'=> date('Y-m-d H:i:s'),
                'EST_DT_ATUALIZACAO'=> date('0000-00-00 00:00:00'),             
                'EST_STATUS'=> 1
            );

            if($Estoques->cadastrar($dados,0)){
                Sessao::alert('OK','Cadastro efetuado com sucesso!','fs-4 alert alert-success');
            }else{
                Sessao::alert('ERRO',' 3- Erro ao cadastrar novo estoque, entre em contato com o suporte!','fs-4 alert alert-danger');
            }

        }else{
            Sessao::alert('ERRO',' 1- Dados inválido(s)!','alert alert-danger');
        }
        //verificar se o usuario tem uma empresa, e retornar os dados
        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);  
        $this->dados['usuarios_empresa'] = $UsuariosEmpresa->checarUsuario();
        //checar se existe cadastro da empresa, estoque e retornar os dados
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

        $this->render('admin/cadastros/estoques/cadastrar', $this->dados);
    }
    public function alterar_estoques_empresa()
    {
        $Usuarios = new Usuarios;
        $Empresa = new Empresas;
        $Enderecos = new Enderecos;
        $Estoques = new Estoques;
        $UsuariosEmpresa = new UsuariosEmpresa;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        //verificar se o usuario tem uma empresa, e retornar os dados
        

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($_POST) && isset($dados['ALTERAR_ESTOQUE'])) {
            unset($dados['ALTERAR_ESTOQUE']);
           
            if($_SESSION['USU_COD'] == $dados['USU_COD']){

                $Estoques->setCodigo($dados['EST_COD']);
                $Estoques->setCodEmpresa($dados['EMP_COD']);
                unset($dados['EST_COD']);
                unset($dados['EMP_COD']);
                $dados += array(
                    'EST_DT_ATUALIZACAO'=> date('Y-m-d H:i:s')             
                );

                if($Estoques->alterar($dados,0)){
                    Sessao::alert('OK','Cadastro alterado com sucesso!','fs-4 alert alert-success');
                }else{
                    Sessao::alert('ERRO',' 3- Erro ao alterar o estoque da empresa, entre em contato com o suporte!','fs-4 alert alert-danger');
                }

            }else{
                Sessao::alert('ERRO',' 2- Acesso inválido!','fs-4 alert alert-danger');
            }

        }else {
            Sessao::alert('ERRO',' 1- Dados inválido(s)!','fs-4 alert alert-danger');
        }

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

        $Estoques->setCodigo($dados['EST_COD']);
        $Estoques->setCodEmpresa($dados['EMP_COD']);
        $this->dados['estoque'] = $Estoques->listar(0);

        $this->render('admin/cadastros/estoques/alterar', $this->dados);

    }
    //CADASTRO - EMPRESAS
    public function empresas()
    {
        $this->dados['title'] .= 'EMPRESA/NEGÓCIO';
        $Usuarios = new Usuarios;
        $Empresa = new Empresas;
        $Enderecos = new Enderecos;
        $UsuariosEmpresa = new UsuariosEmpresa();
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuarios_empresa'] = $UsuariosEmpresa->checarUsuario();
        if (isset($this->dados['usuarios_empresa']['UMP_COD'])) {
            $this->dados['empresas'] = $UsuariosEmpresa->listarTodasEmpresasUsuario();
        }
        $this->render('admin/cadastros/empresas/listar', $this->dados);
    }
    public function cadastro_empresas()
    {
        $this->dados['title'] .= 'EMPRESA/NEGÓCIO';
        $Usuarios = new Usuarios();
        $Check = new Check();
        $Url = new Url();
        $Empresa = new Empresas;
        $Enderecos = new Enderecos;
        $UsuariosEmpresa = new UsuariosEmpresa();
        $ModulosEmpresa = new ModulosEmpresa;
        $Financas = new Financas;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuarios_empresa'] = $UsuariosEmpresa->checarUsuario();
        if (isset($this->dados['usuarios_empresa']['UMP_COD'])) {
            $_SESSION['EMP_COD'] = $this->dados['usuarios_empresa']['EMP_COD'];
            $Empresa->setCodigo($_SESSION['EMP_COD']);
            $this->dados['empresa'] = $Empresa->listar(0);
            $ModulosEmpresa->setCodEmpresa($_SESSION['EMP_COD']);
            $this->dados['modulos_empresa'] = $ModulosEmpresa->listar();
        }else {
            $this->dados['modulos_empresa'] = false;
            $this->dados['empresa'] = false;
        }
        $this->render('admin/cadastros/empresas/cadastrar', $this->dados);
    }
    public function cadastrar_empresas()
    {
        $this->dados['title'] .= 'EMPRESA/NEGÓCIO';
        $Usuarios = new Usuarios();
        $Check = new Check();
        $Url = new Url();
        $Empresa = new Empresas;
        $Enderecos = new Enderecos;
        $UsuariosEmpresa = new UsuariosEmpresa();
        $ModulosEmpresa = new ModulosEmpresa;
        $Financas = new Financas;

        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);

        //Recupera os dados enviados
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
        if (isset($_POST) && isset($dados['CADASTRAR_EMPRESA'])) {
            //Verifica se os campos foram todos preenchidos
            unset($dados['CADASTRAR_EMPRESA']);
                
            $Empresa->setcodRegistro($dados['EMP_REGISTRO']);
            //Verificar se já existe cadastro da empresa pelo REGISTRO: CPF ou CNPJ informado
            $emp = $Empresa->checarRegistroEmpresa();
            if(!$emp){
                //Iniciar o cadastro da nova empresa
                $db_empresa = array(
                    'EMP_TIPO' => $dados['EMP_TIPO'],
                    'EMP_DT_CADASTRO'=> date('Y-m-d H:i:s'),
                    'EMP_DT_ATUALIZACAO'=> date('0000-00-00 00:00:00'),
                    'EMP_NOME_FANTASIA'   => $dados['EMP_NOME_FANTASIA'],
                    'EMP_RAZAO_SOCIAL'    => $dados['EMP_RAZAO_SOCIAL'],
                    'EMP_REGULAMENTACAO'  => $dados['EMP_REGULAMENTACAO'],
                    'EMP_PORTE' => $dados['EMP_PORTE'],
                    'EMP_REGISTRO' => $dados['EMP_REGISTRO'],
                    'EMP_INSCRICAO_ESTADUAL' => $dados['EMP_INSCRICAO_ESTADUAL'],
                    'EMP_DT_FUNDACAO'=> $dados['EMP_DT_FUNDACAO'],
                    'EMP_DESCRICAO'=> $dados['EMP_DESCRICAO'],
                    'EMP_TEL_1'=> $dados['EMP_TEL_1'],
                    'EMP_TEL_2'=> $dados['EMP_TEL_2'],
                    'EMP_CEL_1'=> $dados['EMP_CEL_1'],
                    'EMP_CEL_2'=> $dados['EMP_CEL_2'],
                    'EMP_EMAIL_1'=> $dados['EMP_EMAIL_1'],
                    'EMP_EMAIL_2'=> $dados['EMP_EMAIL_2'],
                    'EMP_DESCRICAO'=> $dados['EMP_DESCRICAO'],                        
                    'EMP_STATUS'=> 1
                );
                //CADASTRAR NOVA EMPRESA
                $id = $Empresa->cadastrar($db_empresa,0);
                if($id){
                    $ok = true;
                }else{
                    $ok = false;
                    Sessao::alert('ERRO',' 3- Erro ao cadastrar nova empresa, contate o suporte!','fs-4 alert alert-danger');
                }
            }else {
                $ok = true;
                $id = $emp['EMP_COD'];
            }
            $Enderecos->setCodEmpresa($id);
            $endr = $Enderecos->checarEnderecoEmpresa();
            if(!$endr){
                $dados_endereco['END_LOGRADOURO'] = $Check->checarString($dados['END_LOGRADOURO']);
                $dados_endereco['END_NUMERO'] = $Check->checarString($dados['END_NUMERO']);
                $dados_endereco['END_BAIRRO'] = $Check->checarString($dados['END_BAIRRO']);
                $dados_endereco['END_CIDADE'] = $Check->checarString($dados['END_CIDADE']);
                $dados_endereco['END_ESTADO'] = $Check->checarString($dados['END_ESTADO']);
                $dados_endereco['END_CEP'] = $Check->checarString($dados['END_CEP']);
                $db_endereco = array(
                    'USU_COD' => 0,
                    'EMP_COD' => $id,
                    'END_DT_CADASTRO' => date('Y-m-d H:i:s'),
                    'END_DT_ATUALIZACAO' => date('0000-00-00 00:00:00'),
                    'END_LOGRADOURO' =>  $dados_endereco['END_LOGRADOURO'],
                    'END_NUMERO' =>  $dados_endereco['END_NUMERO'],
                    'END_BAIRRO' =>  $dados_endereco['END_BAIRRO'],
                    'END_CIDADE' =>  $dados_endereco['END_CIDADE'],
                    'END_ESTADO' =>  $dados_endereco['END_ESTADO'],
                    'END_CEP'    =>  $dados_endereco['END_CEP'],
                    'END_STATUS' => 1
                );
                //CADASTRAR O ENDERECO DA EMPRESA
                if($Enderecos->cadastrar($db_endereco,0)){
                    $ok = true;
                }else {
                    $ok = true;
                }
            }
           
            if ($ok) {
               
                $ok2 = true;
                $UsuariosEmpresa->setCodUsuario($dados['USU_COD']);
                $UsuariosEmpresa->setCodEmpresa($id);
                $usu_emp = $UsuariosEmpresa->checarUsuarioEmpresa();
                //VERIFICAR SE O USUARIO ESTA VINCULADO A NOVA EMPRESA
                if(!$usu_emp){
                  
                    $db_usuario_empresa = array(
                        'EMP_COD' => $id,
                        'USU_COD' => $dados['USU_COD'],
                        'UMP_DT_CADASTRO' => date('Y-m-d H:i:s'),
                        'UMP_STATUS' => 1
                    );
                    //CADASTRAR O USUARIO NA EMPRESA
                    if($UsuariosEmpresa->cadastrar($db_usuario_empresa,0)){
                        $ok2 = true;
                    }else {
                        $ok2 = false;
                        Sessao::alert('ERRO',' 2- Erro ao vincular nova empresa ao usuário, contate o suporte!','fs-4 alert alert-danger');
                    }
                }else {
                   
                    $db_usuario_empresa = array(
                        'EMP_COD' => $id,
                        'USU_COD' => $dados['USU_COD'],
                        'UMP_DT_CADASTRO' => date('Y-m-d H:i:s'),
                        'UMP_STATUS' => 1
                    );
                    //$UsuariosEmpresa->setCodUsuario($dados['USU_COD']);
                    //$UsuariosEmpresa->setCodEmpresa($id);
                    //$UsuariosEmpresa->alterar($db_usuario_empresa,0);
                    $ok2 = true;
                }

                //VERIFICAR SE USUARIO FOI VINCULADO OU NAO HA NOVA EMPRESA
                if ($ok2) {
                    $db_modulos_empresa = [];
                    $m = 0;
                    //LIBERAR MODULOS PARA EMPRESA
                    for ($i = 1; $i <= 3; $i++) {           
                       
                        $ModulosEmpresa->setCodModulo($i);
                        $ModulosEmpresa->setCodEmpresa($id);
                        if(!$ModulosEmpresa->checarRegistroModuloEmpresa()){
                            $db_modulos_empresa = array(
                                'EMP_COD' => $id,
                                'MOD_COD' => $i,
                                'MLE_DT_CADASTRO' => date('Y-m-d H:i:s'),
                                'MLE_STATUS' => 1
                            );
                            if($ModulosEmpresa->cadastrar($db_modulos_empresa,0)){
                                $m++;
                                unset($db_modulos_empresa);
                            }
                        }else {
                            $m++;
                        }
                       
                    } 
                    //VERIFICAR SE FOI LIBERADO NO MINIMO 2 MÓDULOS A EMPRESA DO USUARIO
                    if ($m >= 2) {

                        $db_conta_empresa = array(
                            'EMP_COD'  => $id,
                            'CTA_TIPO' => 1,
                            'CTA_DT_CADASTRO'    => date('Y-m-d H:i:s'),
                            'CTA_DT_ATUALIZACAO' => date('0000-00-00 00:00:00'),
                            'CTA_NOME'     => 'PRINCIPAL',
                            'CTA_DESCRICAO'=> 'CONTA PRINCIPAL DA EMPRESA',   
                            'CTA_SALDO'  => 0,
                            'CTA_STATUS' => 1
                        );
                        
                        $Financas->setCodEmpresa($id);
                        if(!$Financas->checarRegistroContaEmpresa()){
                            if ($Financas->cadastrar($db_conta_empresa,0)) {
                                Sessao::alert('OK','Cadastro efetuado com sucesso!','fs-4 alert alert-success');

                            }else {
                                Sessao::alert('OK','Cadastro efetuado com sucesso, crie uma conta para gerenciar sua movimentação','fs-4 alert alert-success');
                            }
                        }else {
                            Sessao::alert('OK','Cadastro efetuado com sucesso!','fs-4 alert alert-success');
                        }
                        $db_usuario = array(
                            'EMP_COD'  => $id,
                            'USU_DT_ATUALIZACAO' => date('Y-m-d H:i:s')
                        );
                        $Usuarios->setCodigo($dados['USU_COD']);
                        $Usuarios->alterar($db_usuario,0);

                    }else {
                        Sessao::alert('ERRO',' 2- Módulos não foram liberados, contate o suporte!','alert alert-danger');
                    }
                    
                }
            }
        }else{
            Sessao::alert('ERRO',' 1- Dados inválido(s)!','alert alert-danger');
        }

        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuarios_empresa'] = $UsuariosEmpresa->checarUsuario();
        if (isset($this->dados['usuarios_empresa']['UMP_COD'])) {
            $_SESSION['EMP_COD'] = $this->dados['usuarios_empresa']['EMP_COD'];
            $Empresa->setCodigo($_SESSION['EMP_COD']);
            $this->dados['empresa'] = $Empresa->listar(0);
            $ModulosEmpresa->setCodEmpresa($_SESSION['EMP_COD']);
            $this->dados['modulos_empresa'] = $ModulosEmpresa->listar();
        }else {
            $this->dados['modulos_empresa'] = false;
            $this->dados['empresa'] = false;
        }

        $this->render('admin/cadastros/empresas/cadastrar', $this->dados);
    }
    public function alterar_empresas() : void 
    {
        $this->dados['title'] .= 'EMPRESA/NEGÓCIO';
        $Usuarios = new Usuarios;
        $Empresa = new Empresas;
        $Enderecos = new Enderecos;
        $UsuariosEmpresa = new UsuariosEmpresa();
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuarios_empresa'] = $UsuariosEmpresa->checarUsuario();
        $dados = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        $dados = explode("/",$dados['url']);
        if (isset($dados[1]) && $dados[1] == 'alterar_empresas') {
       
            if(isset($dados[3]) && isset($dados[2])){
            $UsuariosEmpresa->setCodigo($dados[3]);
            $UsuariosEmpresa->setCodEmpresa($dados[2]);
            $this->dados['empresa'] = $UsuariosEmpresa->listar(0);
            $this->render('admin/cadastros/empresas/alterar', $this->dados);
            }else{
                Sessao::alert('ERRO',' 1- Dados inválido(s)!','alert alert-danger');
                $this->render('admin/cadastros/empresas/listar', $this->dados);
            }
        }
        
    }
    public function alterar_dados_empresa()
    {
        $this->dados['title'] .= 'EMPRESA/NEGÓCIO';
        $Usuarios = new Usuarios;
        $Empresa = new Empresas;
        $Enderecos = new Enderecos;
        $Check = new Check();
        $UsuariosEmpresa = new UsuariosEmpresa();
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuarios_empresa'] = $UsuariosEmpresa->checarUsuario();

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($_POST) && isset($dados['ALTERAR_EMPRESA'])) {
            unset($dados['ALTERAR_EMPRESA']);
            
            if($_SESSION['USU_COD'] == $dados['USU_COD']){
                
                $UsuariosEmpresa->setCodigo($dados['USU_COD']);
                $UsuariosEmpresa->setCodEmpresa($dados['EMP_COD']);
                
                $dados += array(
                    'USU_DT_ATUALIZACAO'=> date('Y-m-d H:i:s'),
                );

                unset($dados['USU_COD']);
                
                $ok = true;
                foreach ($dados as $key => $value) {

                   
                    //Verifica se tem algum valor em branco
                    $value = $Check->checarString($value);
                    //if(empty($dados["$key"])){
                        //Sessao::alert('ERRO',' 2- Preencha todos os campos!','alert alert-danger');
                        //$ok = false;
                        //break;
                    //}
                }
                //VERIFICAR SE TODOS OS CAMPOS FORAM PREENCHIDOS
                if ($ok) {
                    $Empresa->setCodigo($dados['EMP_COD']);
                    $db_endereco = array(
                        'USU_COD' => 0,
                        'EMP_COD' => $dados['EMP_COD'],
                        'END_DT_ATUALIZACAO' => date('Y-m-d H:i:s'),
                        'END_LOGRADOURO' =>  $dados['END_LOGRADOURO'],
                        'END_NUMERO' =>  $dados['END_NUMERO'],
                        'END_BAIRRO' =>  $dados['END_BAIRRO'],
                        'END_CEP'    =>  $dados['END_CEP'],
                        'END_CIDADE' =>  $dados['END_CIDADE'],
                        'END_ESTADO' =>  $dados['END_ESTADO']
                    );

                    $ends = array_keys($dados, "END_");
                    unset($dados[$ends]);

                    if($Empresa->alterar($dados,0)){
                        $Enderecos->setCodEmpresa($dados['EMP_COD']);
                        $id = $Enderecos->checarEnderecoEmpresa();
                       
                        unset($dados['EMP_COD']);

                        $Enderecos->setCodigo($id);
                        
                        if($Enderecos->alterarEmpresa($db_endereco,0)){
                           
                            Sessao::alert('OK','Cadastro alterado com sucesso!','fs-4 alert alert-success');
                        }else {
                            Sessao::alert('OK','Cadastro alterado, houve um erro ao alterar o endereco!','fs-4 alert alert-success');
                        }
                    }else{
                        Sessao::alert('ERRO',' 3- Erro ao alterar sua empresa, entre em contato com o suporte!','fs-4 alert alert-danger');
                    }
                }
            }else{
                Sessao::alert('ERRO',' 2- Acesso inválido!','fs-4 alert alert-danger');
            }
        }else{
            Sessao::alert('ERRO',' 1- Dados inválido(s)!','fs-4 alert alert-danger');
        }

        $this->dados['empresa'] = $UsuariosEmpresa->listar(0);

        $this->render('admin/cadastros/empresas/alterar', $this->dados);
    }
    //Controller - ALTERAR DADOS DO USUARIO NO DB
    public function alterar_dados_usuarios()
    {
        $this->dados['title'] .= 'MEUS DADOS DE USUÁRIO';
        $Usuarios = new Usuarios;
        $Enderecos = new Enderecos;
        $Check = new Check();
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($_POST) && isset($dados['ALTERAR_USUARIO'])) {
            unset($dados['ALTERAR_USUARIO']);

            $codUsuario = $dados['USU_COD'];
            if($_SESSION['USU_COD'] == $codUsuario){

                $ok = true;
                foreach ($dados as $key => $value) {
                //Verifica se tem algum valor em branco
                $value = $Check->checarString($value);
                    if(empty($dados["$key"])){
                        Sessao::alert('ERRO',' 2- Preencha todos os campos!','alert alert-danger');
                        $ok = false;
                        break;
                    }
                }
                //VERIFICAR SE TODOS OS CAMPOS FORAM PREENCHIDOS
                if ($ok) {
                    $Usuarios->setCodUsuario($codUsuario);
                    $dadosUsuario = $Usuarios->listar(0);
                   
                    //VERIFICAR SE O USUARIO  INFORMOU A SENHA PARA ALTERAR
                    if(!empty($dados['USU_SENHA'])){
                        //CONFERIR SE A SENHA É IGUAL A CONFIRMACAO DE SENHA
                        if($dados['USU_SENHA'] == $dados['USU_CONF_SENHA']){
                            unset($dados['USU_CONF_SENHA']);
                            $dados['USU_SENHA']= $Check->codificarSenha($dados['USU_SENHA']);
                            //Checar email válido
                            if($Check->checarEmail($dados['USU_EMAIL'])){
                                $Usuarios->setEmailUsuario($dados['USU_EMAIL']);
                                //Checar se o email é do usuario ou se é um email que não esta cadastrado para outro usuário
                                $db_usuario = $Usuarios->checarEmailUsuario();
                                if(!isset($db_usuario) OR $db_usuario['USU_EMAIL'] == $dados['USU_EMAIL']){
                                    $db = array(
                                        'USU_DT_ATUALIZACAO'=> date('Y-m-d H:i:s'),
                                        'USU_NOME'      => $dados['USU_NOME'],
                                        'USU_SOBRENOME' => $dados['USU_SOBRENOME'],
                                        'USU_SEXO'  => $dados['USU_SEXO'],
                                        'USU_EMAIL' => $dados['USU_EMAIL'],
                                        'USU_SENHA' => $dados['USU_SENHA'],
                                        'USU_NIVEL' => $_SESSION['USU_NIVEL'],
                                        'USU_STATUS'=> 1
                                    );
    
                                    if($Usuarios->alterar($db,0)){
                                        
                                        //Alterando o endereco do usuario
                                        $Enderecos->setCodUsuario($codUsuario);
                                        //$db_end = $Enderecos->checarEnderecoUsuario();
                                        $Enderecos->setCodigo($dadosUsuario['END_COD']);
                                        $dados_endereco['END_LOGRADOURO'] = $Check->checarString($dados['END_LOGRADOURO']);
                                        $dados_endereco['END_NUMERO'] = $Check->checarString($dados['END_NUMERO']);
                                        $dados_endereco['END_BAIRRO'] = $Check->checarString($dados['END_BAIRRO']);
                                        $dados_endereco['END_CIDADE'] = $Check->checarString($dados['END_CIDADE']);
                                        $dados_endereco['END_ESTADO'] = $Check->checarString($dados['END_ESTADO']);
                                        $db_endereco = array(
                                            'END_DT_ATUALIZACAO' => date('Y-m-d H:i:s'),
                                            'END_LOGRADOURO' =>  $dados_endereco['END_LOGRADOURO'],
                                            'END_NUMERO' =>  $dados_endereco['END_NUMERO'],
                                            'END_BAIRRO' =>  $dados_endereco['END_BAIRRO'],
                                            'END_CIDADE' =>  $dados_endereco['END_CIDADE'],
                                            'END_ESTADO' =>  $dados_endereco['END_ESTADO'],
                                            'END_STATUS' => 1
                                        );
                                        //dump($db_endereco);
                                        //exit;
                                        if($Enderecos->alterar($db_endereco,0)){
                                            $nv = array_merge($db,$db_endereco);
                                            Sessao::criarSessao($nv);
                                            Sessao::alert('OK','Usuário alterado com sucesso!','fs-4 alert alert-success');
                                        }else {
                                            Sessao::alert('OK','Usuário alterado, endereço não alterado!','fs-4 alert alert-success');
                                        }
                                    }else{
                                        Sessao::alert('ERRO',' 7- Erro ao alterar usuário, contate o suporte!','fs-4 alert alert-danger');
                                    }
                                }else{
                                    Sessao::alert('ERRO',' 6- Email já utilizado por outro usuário no sistema, digite outro email!','fs-4 alert alert-danger');
                                }
                            }else{
                                Sessao::alert('ERRO',' 5- Email inválido, digite outro email!','fs-4 alert alert-danger');
                            }
                        }else{
                            Sessao::alert('ERRO',' 4- Senha não confere com a digitada!','fs-4 alert alert-danger');
                        }
                    }else{
                        Sessao::alert('ERRO',' 3- Senhas não pode estar vázias, informe sua senha para alterar!','fs-4 alert alert-danger');
                    }
                }else {
                    //Sessao::alert('ERRO',' 2- Preencha todos os campos!','alert alert-danger');
                }
            }else{
                Sessao::alert('ERRO',' 2- Acesso inválido!','fs-4 alert alert-danger');
            }
        }else{
            Sessao::alert('ERRO',' 1- Dados inválido(s)!','fs-4 alert alert-danger');
        }
        $this->render('admin/cadastros/usuarios/meus_dados', $this->dados);
    }
}