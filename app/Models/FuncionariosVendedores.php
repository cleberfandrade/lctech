<?php
namespace App\Models;

use Core\Model;

class FuncionariosVendedores extends Model
{ 
    private $tabela = 'tb_colaboradores';
    private $Model = '';
    private $codigo,$email,$senha, $codEmpresa,$codVenda, $codProduto;

    public function __construct()
    {
        $this->Model = new Model();
        $this->Model->setTabela($this->tabela);
    }
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    public function setSenha($senha)
    {
        $this->senha = $senha;
        return $this;
    }
    public function setCodEmpresa($codEmpresa)
    {
        $this->codEmpresa = $codEmpresa;
        return $this;
    }
    public function setCodProduto($codProduto)
    {
        $this->codProduto = $codProduto;
        return $this;
    }
    public function setCodVenda($codVenda)
    {
        $this->codVenda = $codVenda;
        return $this;
    }
    public function listar($ver = 0)
    {
        $parametros = "VD INNER JOIN tb_empresas E ON E.EMP_COD=VD.EMP_COD WHERE VD.EMP_COD={$this->codEmpresa} AND VD.VDD_COD={$this->codigo}";
        $campos = "*";
        $resultado = $this->Model->exibir($parametros, $campos, $ver = 0, $id = false);
        if ($resultado) {
            return $resultado[0];
        } else {
            return false;
        }
    }
    public function listarTodos($ver = 0)
    {
        $parametros = "VD INNER JOIN tb_empresas E ON E.EMP_COD=VD.EMP_COD WHERE VD.EMP_COD={$this->codEmpresa} ORDER BY VD.VDD_COD";
        $campos = "*";
        $resultado = $this->Model->exibir($parametros, $campos, $ver = 0, $id = false);
        if ($resultado) {
            return $resultado;
        } else {
            return false;
        }
    }
    public function cadastrar(array $dados, $ver = 0)
    {
        $ok = $this->Model->cadastrar($dados, $ver);
        if ($ok) {
            return $ok;
        } else {
            return false;
        }
    }
    public function alterar(array $dados, $ver = 0)
    {
        $parametros = "WHERE EMP_COD={$this->codEmpresa} AND VDD_COD=";
        $this->Model->setParametros($parametros);
        $this->Model->setCodigo($this->codigo);
        $ok = false;
        $ok = $this->Model->alterar($dados, $ver);
        if ($ok) {
            return true;
        } else {
            return false;
        }
    }
    public function excluir($ver = 0)
    {
        $parametros = "WHERE EMP_COD='{$this->codEmpresa}' AND VDD_COD=";
        $this->Model->setParametros($parametros);
        $this->Model->setCodigo($this->codigo);
        $ok = false;
        $ok = $this->Model->deletar($ver);
        if ($ok) {
            return true;
        } else {
            return false;
        }
    }
    public function checarVendedorEmpresa()
    {
        $parametros = "WHERE EMP_COD={$this->codEmpresa} AND VDD_EMAIL='{$this->email}'";
        $campos = "*";
        $resultado = $this->Model->exibir($parametros, $campos, $ver = 0, $id = false);
        if ($resultado) {
            //Já existe
            return $resultado[0];
        } else {
            //Nao existe
            return false;
        }
    }
    public function checarEmailVendedor()
    {
        $parametros = "WHERE VDD_EMAIL='{$this->email}'";
        $campos = "*";
        $resultado = $this->Model->exibir($parametros, $campos, $ver = 0, $id = false);
        if ($resultado) {
            //Já existe
            return $resultado[0];
        } else {
            //Nao existe
            return false;
        }
    }
    public function acessarPDV($ver = 0)
    {
        $parametros = " V INNER JOIN tb_empresas E ON E.EMP_COD=V.EMP_COD WHERE V.VDD_EMAIL='{$this->email}'";
        $campos = "*";
        $resultado = $this->Model->exibir($parametros, $campos, $ver, $id = false);
        if ($resultado) {
            if(self::checarSenhaAcesso($this->senha, $resultado[0]['VDD_SENHA'])){
                return $resultado[0];
            }else{
                return false;
            }
        } else {
            return false;
        }
    }
    private static function checarSenhaAcesso($senhaUsuario, $senhaDB)
    {
        if (password_verify($senhaUsuario, $senhaDB)) {
            return true;
        } else {
            return false;
        }
    }
}