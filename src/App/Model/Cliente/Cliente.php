<?php
namespace App\Model\Cliente;

/**
*
*/
class Cliente
{
	private $valor;

	function __construct($conexao)
	{
		$this->conn = $conexao;
	}

	public function setCodUser($valor)
	{
		$this->codUser = $valor;
	}

	public function viewDados()
	{
		if ($this->codUser != "") {
			$sql = $this->conn->query("SELECT nome,email1,email2,fone,celular,status,data_cadastro,tipo_cliente FROM
			 clientes WHERE codigo = '".addslashes($this->codUser)."' LIMIT 1");
			if ($sql->num_rows > 0) {
				$return = array();
				while($linha = $sql->fetch_array(MYSQLI_ASSOC)){
					array_push($return, $linha);
				}
				return $return;
			}
		}
	}

	public function isParceiro()
	{
		if ($this->codUser != "") {
			$return = 0;
			$sql = $this->conn->query("SELECT * FROM
			 clientes WHERE codigo = '".addslashes($this->codUser)."' AND parceiro = '1' LIMIT 1");
			if ($sql->num_rows > 0) {
				$return = 1;
			}
			return $return;
		}
	}

}
