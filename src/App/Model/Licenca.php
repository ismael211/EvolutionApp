<?php
namespace App\Model;

class Licenca
{
	private $valor;

	function __construct($conexao)
	{
		$this->conn = $conexao;
	}

	public function setTable($table)
	{
		return $this->table = $table;
	}

	public function setAction($valor)
	{
		return $this->action = $valor;
	}

	public function setIdLicenca($valor)
	{
		return $this->idLicenca = $valor;
	}

	public function setIdFatura($valor)
	{
		return $this->idFatura = $valor;
	}

	public function idCliente($valor)
	{
		return $this->idCliente = $valor;
	}

	public function setStatusLicenca($valor)
	{
		return $this->statusLicenca = $valor;
	}

	public function getAll()
	{
		$sql = $this->conn->query("SELECT ".$this->table.".id, ".$this->table.".sub_dominio, ".$this->table.".status,
			".$this->table.".key_licenca, clientes.nome FROM ".$this->table."
			LEFT JOIN clientes ON clientes.codigo = ".$this->table.".id_cliente ");
		if ($sql->num_rows > 0) {
			$return = array();
			while($linha = $sql->fetch_array(MYSQLI_ASSOC)){
				array_push($return, $linha);
			}
			return $return;
		}
	}

	public function getById()
	{
		if ($this->idLicenca != "") {
			$sql = $this->conn->query("SELECT licenca.id, licenca.sub_dominio, licenca.status,
				licenca.key_licenca, clientes.nome as nome_cliente, clientes.codigo as codigo_cliente FROM licenca
				LEFT JOIN clientes ON clientes.codigo = licenca.id_cliente WHERE licenca.id = '".$this->idLicenca."'");
			if ($sql->num_rows > 0) {
				$return = array();
				while($linha = $sql->fetch_array(MYSQLI_ASSOC)){
					array_push($return, $linha);
				}
				return $return;
			}
		}
	}

	public function getCountAllActive()
	{
		$sql = $this->conn->query("SELECT id FROM ".$this->table." WHERE status = '1'");
		return $sql->num_rows;
	}

	public function getCountForIdCliente($id)
	{
		$sql = $this->conn->query("SELECT id FROM ".$this->table." WHERE id_cliente = '$id'");
		return $sql->num_rows;
	}

	public function actions()
	{
		$erro = "0";
		$msg = "";
		$idLicenca = "";
		$page = "";

		if ($this->action == "ativar") {
			$ativar = $this->mudaStatus("1");
			$erro = $ativar["erro"];
			$msg = $ativar["msg"];
		}
		if ($this->action == "desativar") {
			$desativar = $this->mudaStatus("0");
			$erro = $desativar["erro"];
			$msg = $desativar["msg"];
		}
		if ($this->action == "editar") {
			$page = "editar";
			$idLicenca = implode(",", $this->idLicenca);
		}
		if ($this->action == "remove") {
			$remove = $this->remove();
			$erro = $remove["erro"];
			$msg = $remove["msg"];
		}
		return array("erro" => $erro , "msg" => $msg , "idLicenca" => $idLicenca, "page" => $page);
	}

	public function remove()
	{
		$erro = "0";
		$msg = "";

		if ($this->idLicenca != "" and  count($this->idLicenca) > 0) {

			if (is_array($this->idLicenca)) {
				foreach ($this->idLicenca as $value) {
					$this->conn->query("DELETE FROM licenca WHERE id = '".addslashes($value)."'")
					or $erro = "1";
			 		$msg .= "Erro: $value \n";
				}
			}
			else {
				$this->conn->query("DELETE FROM licenca WHERE id = '".addslashes($this->idLicenca)."'")
					or $erro = "1";
			 		$msg .= "Erro: $value \n";
			}

		}
		else {
			$erro = "1";
			$msg = "Escolha uma opção";
		}

		if ($erro == "0") {
			$msg = "Excluido com sucesso!";
		}

		return array("erro" => $erro , "msg" => $msg);
	}

	public function removeByCliente()
	{
		$erro = "0";
		$msg = "";

		if ($this->idCliente != "" and  count($this->idCliente) > 0) {

			if (is_array($this->idCliente)) {
				foreach ($this->idCliente as $value) {
					$this->conn->query("DELETE FROM licenca WHERE id_cliente = '".addslashes($value)."'")
					or $erro = "1";
			 		$msg .= "Erro: $value \n";
				}
			}
			else {
				$this->conn->query("DELETE FROM licenca WHERE id_cliente = '".addslashes($this->idCliente)."'")
					or $erro = "1";
			 		$msg .= "Erro: $value \n";
			}

		}
		else {
			$erro = "1";
			$msg = "Escolha uma opção";
		}

		if ($erro == "0") {
			$msg = "Excluido com sucesso!";
		}

		return array("erro" => $erro , "msg" => $msg);
	}

	public function mudaStatus($status)
	{
		$erro = "0";
		$msg = "";

		if ($this->idLicenca != "" and  count($this->idLicenca) > 0 and $status != "") {
				foreach ($this->idLicenca as $value) {
					$this->conn->query("UPDATE licenca SET status = '".$status."' WHERE id = '".addslashes($value)."'")
					or $erro = "1";
			 		$msg .= "Erro: $value \n";

			 		if ($erro == "0" and $status == "1") {
			 			$licencaEnvios = new LicencaEnvios($this->conn);
			 			$licencaEnvios->idLicenca($value);
			 			$licencaEnvios->boasVindas();
			 		}
				}
		}
		else {
			$erro = "1";
			$msg = "Escolha uma opção";
		}

		if ($erro == "0") {
			$msg = "Operação realizada com sucesso!";
		}

		return array("erro" => $erro , "msg" => $msg);
	}

	public function desativaByIdCliente($status)
	{
		$erro = "0";
		$msg = "";

		if ($this->idCliente != "" and  count($this->idCliente) > 0 and $status != "") {

			$this->conn->query("UPDATE licenca SET status = '$status'
				WHERE id_cliente = '".addslashes($this->idCliente)."'")
			or $erro = "1";
	 		$msg .= "Erro: $value \n";

		}
		else {
			$erro = "1";
			$msg = "Escolha uma opção";
		}

		if ($erro == "0") {
			$msg = "Operação realizada com sucesso!";
		}

		return array("erro" => $erro , "msg" => $msg);
	}

	// Alterar apartir do id da fatura
	public function mudaByidFatura()
	{

		$erro = "0";
		$msg = "";

		if ($this->idFatura != "" and $this->statusLicenca != "" ) {

			$sql_find = $this->conn->query("UPDATE licenca SET status = '".$this->statusLicenca."' WHERE id_fatura = '".addslashes($this->idFatura)."'")
			or $erro = "1";

			if ($erro == "1") {
				$msg = "Não foi possivel Ativar Licenca";
			}
		}

		return array("erro" => $erro , "msg" => $msg);
	}


}
