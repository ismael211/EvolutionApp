<?php
namespace App\Model;

/**
*
*/
class Clientes
{
	use Ferramentas;

	private $valor;
	protected $tipos_actions;

	function __construct($conexao)
	{
		$this->conn = $conexao;
	}

	public function setTable($table)
	{
		return $this->table = $table;
	}

	public function setId($valor)
	{
		return $this->id = $valor;
	}

	public function setStatus($valor)
	{
		return $this->status = $valor;
	}

	public function setAction($valor)
	{
		return $this->action = $valor;
	}

	public function getAll()
	{
		$sql = $this->conn->query("SELECT codigo, nome, email1, email2, fone, celular, data_cadastro, status,
			tipo_cliente FROM ".$this->table."");
		if ($sql->num_rows > 0) {
			$return = array();
			while($linha = $sql->fetch_array(MYSQLI_ASSOC)){

				$linha['data_cadastro'] = $this->convertDataView($linha['data_cadastro']);
				array_push($return, $linha);
			}
			return $return;
		}
	}

	public function getAllNovos()
	{
		$sql = $this->conn->query("SELECT codigo, nome, email1, email2, fone, celular, data_cadastro, status,
			tipo_cliente FROM ".$this->table." WHERE status = 'p'
			AND data_cadastro BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()");
		if ($sql->num_rows > 0) {
			$return = array();
			while($linha = $sql->fetch_array(MYSQLI_ASSOC)){
				$linha['data_cadastro'] = $this->convertDataView($linha['data_cadastro']);
				array_push($return, $linha);
			}
			return $return;
		}
	}

	public function getCountAllActive()
	{
		$sql = $this->conn->query("SELECT codigo FROM ".$this->table." WHERE status = 'a'");
		return $sql->num_rows;
	}

	public function getCountAllProc()
	{
		$sql = $this->conn->query("SELECT codigo FROM ".$this->table." WHERE status = 'p'
			AND data_cadastro BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()");
		return $sql->num_rows;
	}

	public function getAllForStatus($type = null)
	{
		if ($type) {
			$sql = $this->conn->query("SELECT codigo, nome, email1, email2, fone, celular, data_cadastro, status,
			tipo_cliente FROM ".$this->table." WHERE status = '$type'");
		}
		else {
			$sql = $this->conn->query("SELECT codigo, nome, email1, email2, fone, celular, data_cadastro, status,
			tipo_cliente FROM ".$this->table."");
		}
		if ($sql->num_rows > 0) {
			$return = array();
			while($linha = $sql->fetch_array(MYSQLI_ASSOC)){
				$linha['data_cadastro'] = $this->convertDataView($linha['data_cadastro']);
				array_push($return, $linha);
			}
			return $return;
		}
	}

	public function getForId($id)
	{
		$sql = $this->conn->query("SELECT codigo, nome, email1, email2, fone, celular, data_cadastro, status, tipo_cliente
			FROM ".$this->table." WHERE codigo = '$id'");
		if ($sql->num_rows > 0) {
			return $sql->fetch_array(MYSQLI_ASSOC);
		}
	}

	public function mudaStatus()
	{
		$erro = "0";
		$msg_erro = "";
		$id = "0";
		$novoStatus = "0";

		if ($this->id != "") {


			if ($this->status == "p") {
				$novoStatus = "a";
				$desativa = "1";
			}
			if ($this->status == "a") {
				$novoStatus = "p";
				$desativa = "0";
			}

			try{
				$licenca = new Licenca($this->conn);
				$planos = new Planos($this->conn);

				if (is_array($this->id)) {
					foreach ($this->id as $value) {
					 	$this->conn->query("UPDATE ".$this->table." SET status = '".$novoStatus."'
					 		WHERE codigo = '".$value."' ")
					 	or $erro = "1";
					 	$msg_erro .= "Erro: $value \n";


					 		$licenca->idCliente($value);
					    	$licenca->desativaByIdCliente($desativa);

					    	$planos->codigoCliente($value);
					    	$planos->changeStatusByCliente($desativa);

					}
				}
				else {
					$this->conn->query("UPDATE ".$this->table." SET status = '".$novoStatus."'
						WHERE codigo = '".$this->id."' ")
				 	or $erro = "1";
				 	$msg_erro .= "Erro: $this->id \n";


					    $licenca->idCliente($this->id);
					    $licenca->desativaByIdCliente($desativa);

					    $planos->codigoCliente($this->id);
					    $planos->changeStatusByCliente($desativa);

				}

			} catch(Exception $e){
			    $erro = "1";
			    $msg_erro = "Ocorreu um erro: $e \n";
			}

			if(is_array($this->id)){
				$id = "";
			}
			else {
				$id = $this->id;
			}
		}

		$return = array("erro" => $erro, "msg_erro" => "$msg_erro", "id_valor" => "$id", "novo_status" => "$novoStatus");
		return $return;

	}

	public function remove()
	{
		$erro = "0";
		$msg_erro = "";

		if ($this->id != "") {
			try{

				if (is_array($this->id)) {
					foreach ($this->id as $value) {

						// Remove servico
						$planos = new Planos($this->conn);
						$planos->codigoCliente($value);
						$planos->removeByCliente();

						// Remove Faturas
						$faturas = new Faturas($this->conn);
						$faturas->idCliente($value);
						$faturas->removeFaturaByCliente();

						// Remove licenca
						$licenca = new Licenca($this->conn);
						$licenca->idCliente($value);
						$licenca->removeByCliente();

						$this->conn->query("DELETE FROM clientes WHERE codigo = '".$value."'");

						$msg_erro = "Excluído com sucesso!";
					}
				}
				else
				{
					$erro = "1";
			 	    $msg_erro .= "Erro: $this->id \n";
				}

			} catch(Exception $e){

			   	$erro = "1";
			    $msg_erro = "Ocorreu um erro: $e";

			}
		}
		else {
			$erro = "1";
			$msg_erro = "Não foi possível Remover!";
		}

		$return = array("erro" => "$erro", "msg_erro" => "$msg_erro");
		return $return;
	}

	public function editOne()
	{
		if ($this->id != "" and count($this->id) == "1") {
			$codigo_cliente = implode(",", $this->id);
			$erro = "0";
			$msg = "";
		}
		else {
			$erro = "1";
			$msg = "Não foi possivel Editar";
			$codigo_cliente = "0";
		}

		$return = array("erro" => "$erro", "msg" => "$msg", "codigo_cliente" => $codigo_cliente );
		return $return;
	}


	public function change()
	{

		if($this->action == "ativar"){
			$this->setStatus("p"); // Esta ao inverso
		    $retorno_status = $this->mudaStatus();
		    if ($retorno_status["erro"] == "0") {
		    	$erro = "0";
		    	$msg = "Alterações realizadas com sucesso!";
		    	$page = "0";
		    	$codigo_cliente = "0";
		    }
		    else{
		    	$erro = "1";
		    	$msg = "Não foi possível realizar esta alteração!";
		    }
		}
		if($this->action == "desativar"){
			$this->setStatus("a"); // Esta ao inverso
		    $retorno_status = $this->mudaStatus();
		    if ($retorno_status["erro"] == "0") {
		    	$erro = "0";
		    	$msg = "Alterações realizadas com sucesso!";
		    	$page = "0";
		    	$codigo_cliente = "0";
		    }
		    else{
		    	$erro = "1";
		    	$msg2 = $retorno_status["msg_erro"];
		    	$msg = "Não foi possível realizar esta alteração! - $msg2";
		    }
		}
		if($this->action == "editar"){
			$edicao = $this->editOne();
			$erro = $edicao["erro"];
			$msg = $edicao["msg"];
			$codigo_cliente = $edicao["codigo_cliente"];
			$page = "1";
		}
		if($this->action == "remover"){
		    $remove =  $this->remove();
		    $erro = $remove["erro"];
			$msg = $remove["msg_erro"];
			$codigo_cliente = "0";
			$page = "0";
		}
		if($this->action == "0"){
			$erro = "1";
			$codigo_cliente = "0";
			$msg = "Escolha uma opção!!";
			$page = "0";
		}

		$return = array("erro" => "$erro", "msg" => "$msg", "page" => "$page", "codigo_cliente" => $codigo_cliente);
		return $return;
	}

	public function getForEdit()
	{
		if ($this->id != "") {
			$sql = $this->conn->query("SELECT codigo, nome, email1, email2, fone, celular, data_cadastro, status, tipo_pessoa, cpf, rg,
				razao_social, cnpj, data_nac, tipo_cliente, obs
				FROM clientes WHERE codigo = '".$this->id."'");
			if ($sql->num_rows > 0) {
				$return = array();
				while($linha = $sql->fetch_array(MYSQLI_ASSOC)){
					if ($linha["data_nac"]) {
					 	$linha["data_nac"] = $this->convertDataView($linha["data_nac"]);
					}
					array_push($return, $linha);
				}
				return $return;
			}
		}
	}

	public function ClienteParceiro()
	{
		if ($this->id != "") {
			$return = 0;
			$sql = $this->conn->query("SELECT parceiro FROM clientes WHERE codigo = '".$this->id."' AND parceiro = '1'");
			if ($sql->num_rows > 0) {
				$return = 1;
			}
			return $return;
		}
	}

}
