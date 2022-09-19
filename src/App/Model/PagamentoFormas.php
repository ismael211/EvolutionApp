<?php
namespace App\Model;
/**
*
*/
class PagamentoFormas
{
	private $valor;

	function __construct($conexao)
	{
		$this->conn = $conexao;
	}

	public function setTable($table)
	{
		$this->table = $table;
	}

	public function getAll()
	{
		$sql = $this->conn->query("SELECT * FROM ".$this->table."");
		if ($sql->num_rows > 0) {
			$return = array();
			while($linha = $sql->fetch_array(MYSQLI_ASSOC)){
				array_push($return, $linha);
			}
			return $return;
		}
	}
}
