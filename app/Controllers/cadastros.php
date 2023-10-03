<?php
namespace App\Controllers;

use Core\View;
use App\Models\Empresas;
use App\Models\Enderecos;
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
        $this->dados['title'] = 'MÓDULO | CADASTROS >>';
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
        $this->dados['title'] .= 'MEUS DADOS DE USUÁRIO';
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
        $Enderecos = new Enderecos;
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
            dump($dados);
            exit;
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
                    //Iniciar o cadastro da nova empresa

                    $id = 0;
                    $dados_endereco['END_LOGRADOURO'] = $Check->checarString($dados['END_LOGRADOURO']);
                    $dados_endereco['END_NUMERO'] = $Check->checarString($dados['END_NUMERO']);
                    $dados_endereco['END_BAIRRO'] = $Check->checarString($dados['END_BAIRRO']);
                    $dados_endereco['END_CIDADE'] = $Check->checarString($dados['END_CIDADE']);
                    $dados_endereco['END_ESTADO'] = $Check->checarString($dados['END_ESTADO']);
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
                        'END_STATUS' => 1
                    );
                }else {
                    Sessao::alert('ERRO',' 4- Empresa já cadastrada, qualquer dúvida, entre em contato conosco!','alert alert-danger');
                }
            }
        }else{
            Sessao::alert('ERRO',' 1- Dados inválido(s)!','alert alert-danger');
        }
        $this->render('admin/cadastros/empresas/cadastro', $this->dados);
    }
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