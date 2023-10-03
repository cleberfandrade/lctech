<?php 
namespace App\Models;

use Core\Model;

class UsuariosEmpresa extends Model
{
    private $tabela = 'tb_usuarios_empresa';
    private $Model = '';
    private $email, $codigo,$codUsuario,$codEmpresa;
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
    public function setCodUsuario($codUsuario)
    {
        $this->codUsuario = $codUsuario;
        return $this;
    }
    public function setCodEmpresa($empresa)
    {
        $this->codEmpresa = $empresa;
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
        $parametros = " WHERE EMP_COD='{$this->codEmpresa}' AND USU_COD=";
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
    public function listarTodos($ver = 0)
    {
        $parametros = " WHERE EMP_COD='{$this->codEmpresa}' ORDER BY USU_NOME ASC";
        $campos = "*";
        $resultado = $this->Model->exibir($parametros, $campos, $ver, $id = false);
        if ($resultado) {
            return $resultado;
        } else {
            return false;
        }
    }
    public function listar($ver = 0)
    {
        $parametros = " WHERE EMP_COD='{$this->codEmpresa}' AND USU_COD='{$this->codigo}' ORDER BY USU_NOME ASC";
        $campos = "*";
        $resultado = $this->Model->exibir($parametros, $campos, $ver = 0, $id = false);
        if ($resultado) {
            return $resultado[0];
        } else {
            return false;
        }
    }
    public function checarUsuarioEmpresa()
    {
        $parametros = "WHERE USU_COD='{$this->codigo}' AND EMP_COD='{$this->codEmpresa}'";
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
    public function checarUsuario()
    {
        $parametros = "WHERE USU_COD='{$this->codigo}'";
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
}