<?php
namespace App\Controllers;

use App\Models\CargosSalarios;
use App\Models\Empresas;
use App\Models\Usuarios;
use App\Models\UsuariosEmpresa;
use Core\View;
use Libraries\Check;
use Libraries\Sessao;

class cargos_salarios extends View
{
    private $dados = [];
    public $link,$Enderecos,$Usuarios,$Empresa,$UsuariosEmpresa,$Check,$CargosSalarios;
    public function __construct()
    {
        Sessao::naoLogado();
        $this->dados['title'] = 'MÓDULO | CADASTROS >>';
        $this->Usuarios = new Usuarios;
        $this->Empresa = new Empresas;
        $this->UsuariosEmpresa = new UsuariosEmpresa;
        $this->CargosSalarios= new CargosSalarios;
        $this->Check = new Check;
        $this->link[0] = ['link'=> 'admin','nome' => 'PAINEL'];
        $this->link[1] = ['link'=> 'cadastros','nome' => 'MÓDULO DE CADASTROS'];
        $this->link[2] = ['link'=> 'cargos_salarios','nome' => 'GERENCIAR CARGOS E SALÁRIOS'];
    }
    public function index()
    {
        $this->dados['title'] .= ' CARGOS E SALÁRIOS';
        $this->dados['usuario'] = $this->Usuarios->setCodUsuario($_SESSION['USU_COD'])->listar(0);
        $this->dados['cargos_salarios'] = $this->CargosSalarios->setCodEmpresa($_SESSION['EMP_COD'])->listarTodos(0);
       
        $this->dados['breadcrumb'] = $this->Check->setLink($this->link)->breadcrumb();
        $this->render('admin/cadastros/cargos_salarios/listar', $this->dados);
    }
    public function cadastro()
    {
        $this->dados['title'] .= ' CADASTRAR CARGOS E SALÁRIOS';
        $this->link[3] = ['link'=> 'cargos_salarios/cadastrar','nome' => 'CADASTRO DE CARGOS E SALÁRIOS'];

        $this->dados['breadcrumb'] = $this->Check->setLink($this->link)->breadcrumb();
        $this->render('admin/cadastros/cargos_salarios/cadastrar', $this->dados);
    }
    public function cadastrar()
    {
        $this->dados['title'] .= ' CADASTRAR CARGOS E SALÁRIOS';
        $this->link[3] = ['link'=> 'cargos_salarios/cadastrar','nome' => 'CADASTRO DE CARGOS E SALÁRIOS'];
        $this->dados['empresa'] = $this->UsuariosEmpresa->setCodigo($_SESSION['USU_COD'])->setCodEmpresa($_SESSION['EMP_COD'])->listar(0);
        
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                
        if (isset($_POST) && isset($dados['CADASTRAR_NOVO_CARGO_SALARIO'])) {
            
            if( $this->dados['empresa']['USU_COD'] == $dados['USU_COD'] && $this->dados['empresa']['EMP_COD'] == $dados['EMP_COD']){
                //Verifica se os campos foram todos preenchidos
                unset($dados['CADASTRAR_NOVO_CARGO_SALARIO']);

                    $dados += array(
                        'CGS_DT_CADASTRO'=> date('Y-m-d H:i:s'),
                        'CGS_DT_ATUALIZACAO'=> date('0000-00-00 00:00:00'),             
                        'CGS_STATUS'=> 1
                    );

                    if($this->CargosSalarios->cadastrar($dados,0)){
                        Sessao::alert('OK','Cadastro efetuado com sucesso!','fs-4 alert alert-success');
                    }else{
                        Sessao::alert('ERRO',' CGS3 - Erro ao cadastrar novo cargo e salário, entre em contato com o suporte!','fs-4 alert alert-danger');
                    }
            }else{
                Sessao::alert('ERRO',' CGS2 - Dados inválido(s)!','alert alert-danger');
            }
        }else{
            Sessao::alert('ERRO',' CGS1 - Acesso inválido(s)!','alert alert-danger');
        }

        $this->dados['breadcrumb'] = $this->Check->setLink($this->link)->breadcrumb();
        $this->render('admin/cadastros/cargos_salarios/cadastrar', $this->dados);
    }
} 