<?php
namespace App\Model;
/**
* 
*/
class ModeloPlanos
{
	private $valor;

	function __construct($conexao)
	{
		$this->conn = $conexao;
	}

	public function setCodigo($valor)
	{
		$this->setCodigo = $valor;
	}

	public function setIdCliente($valor)
	{
		$this->idCliente = $valor;
	}

	public function getModelos()
	{
		if ($this->setCodigo != "") {
			$where = " WHERE codigo = '".$this->setCodigo."'";
		}
		$sql = $this->conn->query("SELECT * FROM servicos_modelos ".$where."");
		if ($sql->num_rows > 0) {
			return $sql->fetch_array(MYSQLI_ASSOC);
		}
	}

}