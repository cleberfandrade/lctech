<?php
namespace App\Controllers;

use Libraries\Sessao;
use Libraries\Url;
use App\Models\Usuarios;
use Core\View;

class site extends View
{
    private $dados = [];
    public function __construct()
    {
        $this->dados['mensagem'] = "LC-TECH";
    }
    public function index()
    { 
        $this->render('site/login', $this->dados);
    }
    public function quem_somos()
    {
        //$this->dados['title'] = 'Quem Somos | IPB de Santo Anastácio-SP';
        //$Documentos = new documentosModel();  
        //$Documentos->setDiretorio('docs/ipb');
        //$this->dados['diretorio'] = 'ipb/';
        //$this->dados['documentos'] = $Documentos->lerDiretorio();
        $this->render('site/quem_somos', $this->dados);
    }
    public function termos()
    {
        //$this->dados['title'] = 'Termos e Privacidade | IPB de Santo Anastácio-SP';
        //$Termos = new termosModel;
        //$this->dados['termos'] = $Termos->listar(0);
        $this->render('site/termos', $this->dados);
    }
    public function sair()
    {
        unset($_SESSION['USU_COD']);
        session_destroy($_SESSION);
        Url::redirecionar('site/index');
    }
    public function descadastramento()
    {
        /**$this->dados['title'] = 'Termos e Privacidade | IPB de Santo Anastácio-SP';
        //$Assinantes = new assinantesModel;
        $Check = new Check();
        $get = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        if($Check::checarEmail($get['ASS_EMAIL'])){
            foreach ($get as $key => $value) {
                $dados[$key] = $value;
            }
            $dados += array(
                'ASS_STATUS' => 'DESATIVADO'
            );
            $Assinantes->setCodigo($dados['ASS_COD']);
            if($Assinantes->alterar($dados,0)){
                $this->render('site/descadastramento', $this->dados);
            }else{
                $this->render('site/home', $this->dados);
            }
        }else{
            $this->render('site/home', $this->dados);
        }
        **/
    }
    public function error()
    { 
        echo "error";
    }
}