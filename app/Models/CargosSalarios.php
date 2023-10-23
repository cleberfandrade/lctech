<?php
namespace App\Models;

use Core\Model;

class CargosSalarios extends Model
{
    private $tabela = 'tb_cargos_salarios';
    private $Model = '';
    private $codigo,$codEmpresa;

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
    public function setCodEmpresa($codEmpresa)
    {
        $this->codEmpresa = $codEmpresa;
        return $this;   
    }
}
