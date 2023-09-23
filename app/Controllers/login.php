<?php
namespace App\Controllers;


use Core\View;
use Libraries\Check;
use Libraries\Url;
use Libraries\Sessao;
use App\Models\usuarios;


class login extends View
{
    private $dados = [];
    public function __construct()
    {
       
        //$info = New informacoesModel;
       // $informacoes = $info->listar();
       // foreach ($informacoes as $key => $value) {
        //    $this->dados[$key] = $value;
        //}
        $this->dados['title'] = 'Login | Acesso Administrativo';
        Sessao::logado();
    }
    public function index()
    { 
        $this->render('site/login', $this->dados);
    }
    public function logar()
    {
        
        $Users = new usuarios();
        $Check = new Check();
        $Url = new Url();
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($_POST) && isset($dados['acesso'])) {
            
            if (!empty($dados['email_usuario']) && !empty($dados['senha_usuario'])) {
                //Validar Dados
                $dados['email_usuario'] = $Check->checarString($dados['email_usuario']);
                $dados['senha_usuario'] = $Check->checarString($dados['senha_usuario']);
                if($Check->checarEmail($dados['email_usuario'])){
                    $Users->setEmailUsuario($dados['email_usuario']);
                    //$senha = $Check->codificarSenha($dados['senha_usuario']);
                    $Users->setSenhaUsuario($dados['senha_usuario']);
                    $user = $Users->Acessar(0);
                    if(!empty($user) && $user != 0){
                        if(Sessao::criarSessao($user)){
                            Sessao::alert('OK',' Acesso efetuado com sucesso!','m-0 fs-4 alert alert-success');
                            $Url->redirecionar('admin/index');
                        }else{
                            Sessao::alert('ERRO',' 5- O sistema encontroiu um erro interno, contate o administrador','alert alert-danger');
                        }
                    }else{
                        Sessao::alert('ERRO',' 4- Usuário ou senha inválido(s)!','alert alert-danger');
                    }
                }else{
                    Sessao::alert('ERRO',' 3- Usuário ou senha inválido(s)!','alert alert-danger');
                }
            }else{
                Sessao::alert('ERRO',' 2- Usuário ou senha inválido(s)!','alert alert-danger');
            }           
        }else{
            Sessao::alert('ERRO',' 1- Dados inválido(s)!','alert alert-danger');
        }
        $this->render('site/login', $this->dados);
    }
    public function sair()
    {
        
        $Usuarios = new usuarios;
        $Usuarios->setCodUsuario($_SESSION['USU_COD']);
        $dados = array(
            'USU_DT_ULT_ACESSO' => date('Y-m-d H:i:s')
        );
        if($Usuarios->alterar($dados,0)){
            //Sessao::alert('OK','Acesso encerrado com sucesso!','fs-4 alert alert-success');
        }else{
            //Sessao::alert('OK','Acesso encerrado!','fs-4 alert alert-success');
        }
        unset($_SESSION['USU_COD']);
        session_destroy();

        Url::redirecionar('site/index');
    }
    public function lembrar()
    {
      
        //$info = New informacoesModel;
        //$informacoes = $info->listar();
        //foreach ($informacoes as $key => $value) {
          //  $this->dados[$key] = $value;
       // }
        $this->dados['title'] = 'Recuperar minha senha | Acesso Administrativo';
        Sessao::logado();
        
        session_destroy();
       
        $this->render('site/lembrar', $this->dados);
    }
    /*
    public function recover()
    {
        $this->dados['title'] = 'Solicitar nova senha | IPBSA';
        $Check = new Check();
       // $mail = new PHPMailer();
        //$Emails = new emailsModel();
        //$info = New informacoesModel;
        $Usuarios = new usuarios;
        //$Recuperacoes = New recuperacoesModel;
        //$informacoes = $info->listar();

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($_POST) && isset($dados['ENVIAR_EMAIL'])) {

            if($Check->checarEmail($dados['EML_EMAIL'])){
               
                $Usuarios->setEmailUsuario($dados['EML_EMAIL']);

                if(!$Usuarios->checarEmailUsuario()){
                   
                    $token = bin2hex(random_bytes(50));
    
                    $data = date('Y-m-d H:i:s', strtotime('+24 Hours'));
            
                    $dadosRecuperacao = [
                        'REC_EMAIL' => $dados['EML_EMAIL'],
                        'REC_TOKEN' => $token,
                        'REC_DT_CADASTRO' => $dados['lembrar'],
                        'REC_DT_EXPIRACAO' => $data
                    ];

                    $destinatario = $dados['EML_EMAIL'];
                    //$remetente = $informacoes['INF_EMAIL_2'];
                    
                    $mail->IsSMTP();
                    $mail->SMTPSecure = 'ssl';
                    $mail->Host = "smtp.hostinger.com"; 
                    $mail->Port = 465;
                    $mail->IsHTML(true); 
                    $mail->SMTPAuth = true; 
                    //$mail->Username = $informacoes['INF_EMAIL_2']; 
                    $mail->Password = 'IPBsa2020@';

                    //$mail->setFrom($remetente, "Igreja Presbiteriana do Brasil Em Santo Anastácio/SP");
                    $mail->FromName = 'CONTATO DO SITE IPB/Santo Anastácio'; 
                    $mail->Subject = "Solicitação de Nova Senha";
                    $link = DIRPAGE . 'login/token/' . $token;
                    $mensagem = "Olá, você solicitou o envio de geração de nova senha de usuário";
                    $mensagem .= "<p>Para trocar sua senha clique no link abaixo</p>";
                    $mensagem .= "<p><a href=" . $link . " target='_blanck' title='Clique aqui'>Clique aqui</a> para recuperar sua senha</p>";
                    $mensagem .= "<p><hr><img style='width:90px;' src='" . DIRIMG . "logo.png'></p>";
                    $mensagem .= "<p style='font-size:10px;'>Tel: (18) 3263-2826<br/> Rua Barão do Rio Branco,n° 374 
                    <br/>Vila Adorinda - Santo Anastácio/SP - CEP 19360-000</p>";
                    $mail->Body = $mensagem;
                    //$mail->AltBody = 'Use um visualizador de e-mail com suporte a HTML';
                    //$mail->addAttachment('storage/public/images/logo.png');
                    //$mail->addAddress($informacoes['INF_EMAIL_1'],'Contato do Site');
                    $mail->addAddress($destinatario,'Contato do Site');
                    
                    
                    $ver = 0;
                    $ok = false;
                    $excluirTokenAnterior = false;
                    $Recuperacoes->setEmailToken($destinatario);
                    $checagem = $Recuperacoes->checarSolicitacoesAnterioes();
                    if($checagem){
                        $Recuperacoes->setCodigo($checagem['REC_COD']);
                        $oke = $Recuperacoes->excluir(0);
                        if($oke){
                            $excluirTokenAnterior = true;
                        }
                    }else{
                        $excluirTokenAnterior = true;
                    }
                    
                    if($excluirTokenAnterior){
                        $ok = $Recuperacoes->cadastrar($dadosRecuperacao,0);
                       
                        if (!$ok) {
                            Sessao::alert('ERRO','ERRO 6: Encontramos um problema ao cadastrar sua solicitação, por favor tente mais tarde!','fs-4 alert alert-danger');
                        } else {
                           
                            if ($mail->Send()) {
                                
                                Sessao::alert('OK', 'Email Enviado com sucesso, aguarde o recebimento do link','fs-4 alert alert-success');
                            } else {
                                Sessao::alert('ERRO', 'ERRO 5: Erro ao enviar email' . $mail->ErrorInfo,'fs-4 alert alert-danger');
                            }
                        }
                    }else{
                        Sessao::alert('ERRO','ERRO 4: Houve um problema ao excluir sua solicitação anterior, por favor, entre em contato com o administrador','fs-4 alert alert-danger');
                    }
                }else{
                    Sessao::alert('ERRO',' 3- Email de usuário não encontrado, entre em contato com o administrador!','fs-4 alert alert-danger');
                }
            }else{
                Sessao::alert('ERRO',' 2- Informe um email válido!','fs-4 alert alert-danger');
            }
        }else{
            Sessao::alert('ERRO',' 1- Dados inválido(s)!','fs-4 alert alert-danger');
        }

        $this->render('site/lembrar',$this->dados);
    }
    public function cadastro()
    {
        //$info = New informacoesModel;
        //$informacoes = $info->listar();
        //foreach ($informacoes as $key => $value) {
          //  $this->dados[$key] = $value;
        //}
        $this->dados['title'] = 'Cadastre-se | IPB/Santo Anastácio-SP';
        Sessao::logado();
        $this->render('site/cadastro', $this->dados);
    }
    public function novo_cadasto()
    {
        $info = New informacoesModel;
        $informacoes = $info->listar();
        foreach ($informacoes as $key => $value) {
            $this->dados[$key] = $value;
        }
        $this->dados['title'] = 'Cadastre-se | IPB/Santo Anastácio-SP';
        Sessao::logado();
        $this->render('site/cadastro', $this->dados);
    }
    //LINK DO EMAIL PARA CHECAR SOLICITACAO DE MUDANCA DE SENHA
    public function token()
    {
        Sessao::logado();
        $pag = filter_input(INPUT_GET,'url', FILTER_DEFAULT);
        $pag = explode('/',  $pag);
        $token_url = isset($pag[2]) ? $pag[2] : 1;

        if ($token_url != '') {
            $Recuperacoes = new recuperacoesModel;
            $Recuperacoes->setToken($token_url);
            $this->dados['recuperacoes'] = array();
            $this->dados['recuperacoes'] = $Recuperacoes->checarToken();
           
            if (!empty($this->dados['recuperacoes'])) {
                if ($this->dados['recuperacoes']['REC_TOKEN'] == $token_url) {

                    $dataAtual = date('Y-m-d H:i:s');

                    if ($dataAtual <= $this->dados['recuperacoes']['REC_DT_EXPIRACAO']) {

                        $this->render('site/nova_senha', $this->dados);
                    } else {
                        Sessao::alert('ERRO','ERRO 3: Link expirado, faça uma nova solicitação','fs-4 alert alert-danger');
                        
                        $Recuperacoes->setToken($this->dados['recuperacoes']['REC_TOKEN']);
                        $token_usuario = checarToken(0);
                        $Recuperacoes->setCodigo($token_usuario['REC_COD']);
                        $Recuperacoes->excluir(0);
                        $this->render('site/lembrar', $this->dados);
                    }
                } else {
                    Sessao::alert('ERRO','ERRO 2: Token inválido, faça uma nova solicitação','fs-4 alert alert-danger');
                    $this->render('site/lembrar', $this->dados);
                }
            } else {
               
                Sessao::alert('ERRO','ERRO 1: Ocorreu um erro ao localizar o token cadastrado, faça uma nova solicitação','fs-4 alert alert-danger');
                
                $this->render('site/lembrar', $this->dados);
            }
        }else {
            $this->render('site/lembrar', $this->dados);
        }
    }
    public function nova_senha()
    {
        $info = New informacoesModel;
        $Recuperacoes = new recuperacoesModel;
        $informacoes = $info->listar();
        foreach ($informacoes as $key => $value) {
            $this->dados[$key] = $value;
        }
        $this->dados['title'] = 'Nova senha de acesso | IPB/Santo Anastácio-SP';
        Sessao::logado();
        $pag = filter_input(INPUT_GET,'url', FILTER_DEFAULT);
        $pag = explode('/',  $pag);
        $token_url = isset($pag[2]) ? $pag[2] : 1;
        if ($token_url != '') {
            $Recuperacoes->setToken($token_url);
            $token_usuario = checarToken(0);
            $Recuperacoes->setCodigo($token_usuario['REC_COD']);
            $Recuperacoes->excluir(0);
            
            $this->render('site/nova_senha', $this->dados);
        }else {
            $this->render('site/lembrar', $this->dados);
        }
    }
    */
}