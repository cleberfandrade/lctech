<?php
namespace App\Controllers;

use Core\View;
use App\Models\Empresas;
use App\Models\Usuarios;
use App\Models\UsuariosEmpresa;
use Libraries\Check;
use Libraries\Url;
use Libraries\Sessao;

class cadastros extends View
{
    private $dados = [];
    public function __construct()
    {
        Sessao::naoLogado();
        $this->dados['title'] = 'MÓDULO | CADASTROS=>';
        $Usuarios = new Usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);

    }
    public function index()
    {
        $Usuarios = new Usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/cadastros/cadastros', $this->dados);
    }
    public function clientes()
    {
        $Usuarios = new Usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/cadastros/clientes', $this->dados);
    }
    public function fornecedores()
    {
        $Usuarios = new Usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/cadastros/fornecedores', $this->dados);
    }
    public function usuarios()
    {
        $Usuarios = new Usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/cadastros/usuarios', $this->dados);
    }
    public function meus_dados()
    {
        $Usuarios = new Usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/cadastros/usuarios/meus_dados', $this->dados);
    }
    public function empresas()
    {
        $Usuarios = new Usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/cadastros/empresas/empresa', $this->dados);
    }
    public function cadastro_empresas()
    {
        $this->dados['title'] .= 'EMPRESA/NEGÓCIO';
        $Usuarios = new Usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
        $this->render('admin/cadastros/empresas/cadastro', $this->dados);
    }
    public function cadastrar_empresas()
    {
        $this->dados['title'] .= 'EMPRESA/NEGÓCIO';
        $Usuarios = new Usuarios();
        $Check = new Check();
        $Url = new Url();
        $Empresa = new Empresas;
        $UsuariosEmpresa = new UsuariosEmpresa();
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuario'] = $Usuarios->listar(0);
       
        $UsuariosEmpresa->setCodUsuario($_SESSION['USU_COD']);
        $this->dados['usuarios_empresa'] = $UsuariosEmpresa->checarEmpresaUsuario();
        if ($this->dados['usuarios_empresa'][0]['UMP_COD']) {
            
            $_SESSION['EMP_COD'] = $this->dados['usuarios_empresa'][0]['EMP_COD'];
            $Empresa->setCodigo($_SESSION['EMP_COD']);
            $this->dados['empresa'] = $Empresa->listarEmpresas(0);
        }
        //Recupera os dados enviados
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
        if (isset($_POST) && isset($dados['CADASTRAR_EMPRESA'])) {
            //Verifica se os campos foram todos preenchidos
            unset($dados['CADASTRAR_EMPRESA']);
            // Remove todas as tags HTML Remove os espaços em branco do valor
            $ok = true;
            //$dados = array_map('trim',$dados);
            foreach ($dados as $key => $value) {
                //Verifica se tem algum valor em branco
               $value = $Check->checarString($value);
                if(empty($dados[$value])){
                    Sessao::alert('ERRO',' 2- Preencha todos os campos!','alert alert-danger');
                    $ok = false;
                    break;
                }
            }
            if ($ok) {

                $Empresa->setcodRegistro($dados['EMP_REGISTRO']);
                //Verificar se já existe cadastro da empresa pelo REGISTRO: CPF ou CNPJ informado
                if(!$Empresa->checarRegistroEmpresa()){

                
                }else {
                    Sessao::alert('ERRO',' 4- Empresa já cadastrada, qualquer dúvida, entre em contato conosco!','alert alert-danger');
                }
            }
        }else{
            Sessao::alert('ERRO',' 1- Dados inválido(s)!','alert alert-danger');
        }
        $this->render('admin/cadastros/empresas/cadastro', $this->dados);
    }
}