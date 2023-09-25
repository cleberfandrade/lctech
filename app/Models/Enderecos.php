<?php 
namespace App\Models;

use Core\Model;

class Enderecos extends Model
{
    private $tabela = 'tb_enderecos';
    private $Model = '';
    private $codigo,$codEmpresa,$codUsuario;
    public function __construct()
    {
        $this->Model = new Model();
        $this->Model->setTabela($this->tabela);
    }
    public function setCodigo($cod)
    {
        $this->codigo = $cod;
        return $this;
    }
    public function setCodUsuario($cod)
    {
        $this->codUsuario = $cod;
        return $this;
    }
    public function setCodEmpresa($cod)
    {
        $this->codEmpresa = $cod;
        return $this;
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
        $parametros = " WHERE USU_COD='{$this->codUsuario}' AND END_COD=";
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
    public function alterarEmpresa(array $dados, $ver = 0)
    {
        $parametros = " WHERE EMP_COD='{$this->codEmpresa}' AND END_COD=";
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
}