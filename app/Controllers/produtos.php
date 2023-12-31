<?php
namespace App\Controllers;

use App\Models\Clientes;
use Core\View;
use App\Models\Empresas;
use App\Models\Enderecos;
use App\Models\Estoques;
use App\Models\Financas;
use App\Models\Fornecedores;
use App\Models\ModulosEmpresa;
use App\Models\Usuarios;
use App\Models\UsuariosEmpresa;
use App\Models\FuncionariosVendedores;
use Libraries\Check;
use Libraries\Url;
use Libraries\Sessao;
use App\Models\Produtos as ModelsProdutos;

class produtos extends View
{
    private $dados = [];
    private $link,$Enderecos,$Clientes,$Usuarios,$Empresa,$UsuariosEmpresa,$Check,$ModulosEmpresa,$Financas,$Estoques,$Produtos;
    
    public function __construct()
    {
        Sessao::naoLogado();
        $this->dados['title'] = 'MÓDULO | ESTOQUES >>';
        $this->Clientes= new Clientes;
        $this->Usuarios = new Usuarios;
        $this->Empresa = new Empresas;
        $this->UsuariosEmpresa = new UsuariosEmpresa;
        $this->Enderecos = new Enderecos;
        $this->Check = new Check;
        $this->ModulosEmpresa = new ModulosEmpresa;
        $this->Estoques = new Estoques;
        $this->Financas = new Financas;
        $this->Produtos = new ModelsProdutos;

        $this->dados['empresa'] = $this->UsuariosEmpresa->setCodEmpresa($_SESSION['EMP_COD'])->setCodUsuario($_SESSION['USU_COD'])->listar(0);
        $this->dados['empresas'] = $this->UsuariosEmpresa->listarTodasEmpresasUsuario(0);
        $this->dados['usuario'] = $this->Usuarios->setCodUsuario($_SESSION['USU_COD'])->listar(0);

        $this->link[0] = ['link'=> 'admin','nome' => 'PAINEL ADMINISTRATIVO'];
        $this->link[1] = ['link'=> 'estoques','nome' => 'ESTOQUES'];
        $this->link[2] = ['link'=> 'estoques/gerenciar/'.$dados[2].'/'.$dados[3],'nome' => 'GERENCIAR ESTOQUE'];
    }
    public function index()
    {
        $this->dados['title'] .= 'GERENCIAR PRODUTOS';
        $this->dados['usuario'] = $this->Usuarios->setCodUsuario($_SESSION['USU_COD'])->listar(0);
        $this->dados['usuarios_empresa'] = $this->UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD'])->checarUsuario();
        if (isset($this->dados['usuarios_empresa']['UMP_COD'])) {
            $_SESSION['EMP_COD'] = $this->dados['usuarios_empresa']['EMP_COD'];
            $this->dados['empresa'] = $this->Empresa->setCodigo($_SESSION['EMP_COD'])->listar(0);
            $this->dados['usuarios'] = $this->UsuariosEmpresa->setCodEmpresa($_SESSION['EMP_COD'])->listarTodos(0);
        }

        $dados = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        $dados = explode("/",$dados['url']);
        
        if (isset($dados[2]) && $dados[2] != '' && isset($dados[3]) && $dados[3] != '') {
                       
            if (isset($_SESSION['EMP_COD']) && $_SESSION['EMP_COD'] == $dados[2]){
                
                //Verificando a existencia do estoque informado
                $this->dados['estoque'] = $this->Estoques->setCodEmpresa($dados[2])->setCodigo($dados[3])->listar(0);
                //Buscando produtos deste estoque
                $this->dados['produtos'] = $this->Produtos->setCodEmpresa($this->dados['estoque']['EMP_COD'])->setCodEstoque($this->dados['estoque']['EST_COD'])->listarTodos(0);
                $this->link[3] = ['link'=> 'estoques/produtos/'.$dados[2].'/'.$dados[3],'nome' => 'GERENCIAR PRODUTOS'];
                $this->dados['breadcrumb'] = $this->Check->setLink($this->link)->breadcrumb();
                $this->render('admin/estoques/produtos/listar', $this->dados);

            }else {
                Sessao::alert('ERRO',' 2- Acesso inválido!','fs-4 alert alert-danger');
                $this->dados['estoques'] = $this->Estoques->setCodEmpresa($_SESSION['EMP_COD'])->listarTodos(0);
                $this->dados['breadcrumb'] = $this->Check->setLink($this->link)->breadcrumb();
                $this->render('admin/estoques/estoques', $this->dados);
            }
        }else {
            Sessao::alert('ERRO',' 1- Acesso inválido(s)!','fs-4 alert alert-danger');
            $this->dados['estoques'] = $this->Estoques->setCodEmpresa($_SESSION['EMP_COD'])->listarTodos(0);
            $this->dados['breadcrumb'] = $this->Check->setLink($this->link)->breadcrumb();
            $this->render('admin/estoques/estoques', $this->dados);
        }
    }
    public function cadastro()
    {
        $this->dados['title'] .= 'CADASTRAR PRODUTOS';
        $Usuarios = new Usuarios;
        $Empresa = new Empresas;
        $Estoques = new ModelsEstoques;
        $UsuariosEmpresa = new UsuariosEmpresa;
        $Fornecedores = new Fornecedores;
        $Produtos = new Produtos;
        $Check = new Check;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
        if (isset($this->dados['usuarios_empresa']['UMP_COD'])) {
            $_SESSION['EMP_COD'] = $this->dados['usuarios_empresa']['EMP_COD'];
            $Empresa->setCodigo($_SESSION['EMP_COD']);
            $this->dados['empresa'] = $Empresa->listar(0);
            $UsuariosEmpresa->setCodEmpresa($_SESSION['EMP_COD']);
            $this->dados['usuarios'] = $UsuariosEmpresa->listarTodos(0);
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
                $this->dados['fornecedores'] = $Fornecedores->listarTodos(0);
                
                $this->link[2] = ['link'=> 'estoques/gerenciar/'.$dados[2].'/'.$dados[3],'nome' => 'GERENCIAR ESTOQUE'];
                $this->link[3] = ['link'=> 'estoques/produtos/'.$dados[2].'/'.$dados[3],'nome' => 'GERENCIAR PRODUTOS'];
                $this->link[4] = ['link'=> 'estoques/cadastro_produtos'.$dados[2].'/'.$dados[3],'nome' => 'CADASTRAR PRODUTOS'];
                $Check->setLink($this->link);
                $this->dados['breadcrumb'] = $Check->breadcrumb();
                $this->render('admin/estoques/produtos/cadastrar', $this->dados);
            }else {
                Sessao::alert('ERRO',' 2- Dados inválido(s)!','fs-4 alert alert-danger');
                $Estoques->setCodEmpresa($_SESSION['EMP_COD']);
                $this->dados['estoques'] = $Estoques->listarTodos(0);
                $Check->setLink($this->link);
                $this->dados['breadcrumb'] = $Check->breadcrumb();
                $this->render('admin/estoques/estoques', $this->dados);
            }
        }else {
            Sessao::alert('ERRO',' 1- Acesso inválido(s)!','fs-4 alert alert-danger');
            $Estoques->setCodEmpresa($_SESSION['EMP_COD']);
            $this->dados['estoques'] = $Estoques->listarTodos(0);
            $Check->setLink($this->link);
            $this->dados['breadcrumb'] = $Check->breadcrumb();
            $this->render('admin/estoques/estoques', $this->dados);
        }
    }
    public function cadastrar()
    {
        $this->dados['title'] .= 'CADASTRAR PRODUTOS';
        $Usuarios = new Usuarios;
        $Empresa = new Empresas;
        $Estoques = new ModelsEstoques;
        $UsuariosEmpresa = new UsuariosEmpresa;
        $Fornecedores = new Fornecedores;
        $Produtos = new Produtos;
        $Check = new Check;
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
        if (isset($_POST) && isset($dados['CADASTRAR_NOVO_PRODUTO'])) {

            unset($dados['CADASTRAR_NOVO_PRODUTO']);
            if($_SESSION['USU_COD'] == $dados['USU_COD'] && isset($_SESSION['EMP_COD']) && $_SESSION['EMP_COD'] == $dados['EMP_COD']){
                    
                $Estoques->setCodEmpresa($dados['EMP_COD']);
                $Estoques->setCodigo($dados['EST_COD']);
                $this->dados['estoque'] = $Estoques->listar(0);
                $Fornecedores->setCodEmpresa($dados['EMP_COD']);
                $this->$dados['fornecedores'] = $Fornecedores->listarTodos(0);
                //Checar duplicidade de cadastro
                $Produtos->setCodEmpresa($dados['EMP_COD']);
                $Produtos->setCodEstoque($dados['EST_COD']);
                if (!$Produtos->checarNomeProduto()) {

                    $dados += array(
                        'PRO_DT_CADASTRO'=> date('Y-m-d H:i:s'),
                        'PRO_DT_ATUALIZACAO'=> date('0000-00-00 00:00:00'),          
                        'PRO_STATUS'=> 1
                    );
                
                    if ($Produtos->cadastrar($dados,0)) {
                        Sessao::alert('OK','Cadastro efetuado com sucesso!','fs-4 alert alert-success');
                    }else {
                        Sessao::alert('ERRO',' 4- Erro ao cadastrar novo vendedor, entre em contato com o suporte!','fs-4 alert alert-danger');
                    }
                }else {
                    Sessao::alert('ERRO',' 3- Nome já utilizado por outro produto no sistema, digite outro nome!','fs-4 alert alert-danger');
                }
            }else {
                Sessao::alert('ERRO',' 2- Dados inválido(s)!','fs-4 alert alert-danger');
                $Estoques->setCodEmpresa($_SESSION['EMP_COD']);
                $this->dados['estoques'] = $Estoques->listarTodos(0);
                $Check->setLink($this->link);
                $this->dados['breadcrumb'] = $Check->breadcrumb();
                $this->render('admin/estoques/estoques', $this->dados);
            }
        }else{
            Sessao::alert('ERRO',' 1- Acesso inválido(s)!','fs-4 alert alert-danger');
            $Estoques->setCodEmpresa($_SESSION['EMP_COD']);
            $this->dados['estoques'] = $Estoques->listarTodos(0);
            $Check->setLink($this->link);
            $this->dados['breadcrumb'] = $Check->breadcrumb();
            $this->render('admin/estoques/estoques', $this->dados);
        }
        $this->link[2] = ['link'=> 'estoques/gerenciar/'.$dados['EMP_COD'].'/'.$dados['EST_COD'],'nome' => 'GERENCIAR ESTOQUE'];
        $this->link[3] = ['link'=> 'estoques/produtos/'.$dados['EMP_COD'].'/'.$dados['EST_COD'],'nome' => 'GERENCIAR PRODUTOS'];
        $this->link[4] = ['link'=> 'estoques/cadastro_produtos/'.$dados['EMP_COD'].'/'.$dados['EST_COD'],'nome' => 'CADASTRAR PRODUTOS'];
        $Check->setLink($this->link);
        $this->dados['breadcrumb'] = $Check->breadcrumb();
        $this->render('admin/estoques/produtos/cadastrar', $this->dados);
    }
}