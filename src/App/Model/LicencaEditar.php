<?php
namespace App\Model;
/**
* 
*/
class LicencaEditar
{
	private $valor;

	function __construct($conexao)
	{
		$this->conn = $conexao;
	}

	public function idLicenca($valor)
	{
		return $this->idLicenca = $valor;
	}

	public function cliente($valor)
	{
		return $this->cliente = $valor;
	}

	public function subDominio($valor)
	{
		return $this->subDominio = $valor;
	}

	public function key($valor)
	{
		return $this->key = $valor;
	}

	public function status($valor)
	{
		return $this->status = $valor;
	}

	public function edita()
	{	
		if ($this->idLicenca != "") {
			$erro = "0";
			$msg = "";
			try{

				$query = $this->conn->query("UPDATE licenca SET sub_dominio = '".$this->subDominio."', status = '".$this->status."',
					key_licenca = '".$this->key."', id_cliente = '".$this->cliente."' WHERE id = '".$this->idLicenca."'")
				or $erro = $this->conn->error();


			} catch (Exception $e){
			 $erro = "1";
			 $msg = "Não foi possivel Editar. #$e";
			}
		  
		}
		else {
			$erro = "1";
			$msg = "Não foi possível Editar 0";
		}

		if ($erro == "0") {
			$msg = "Edição Concluída";
		}

		return array("erro" => $erro, "msg" => $msg);
	}

}