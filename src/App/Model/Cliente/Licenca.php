<?php
namespace App\Model\Cliente;

/**
* 
*/
class Licenca
{
	use \App\Model\Ferramentas;
	private $conexao;
	private $valor;

	function __construct($conexao)
	{
		$this->conn = $conexao;
	}

	public function setCodUser($valor)
	{
		$this->CodUser = $valor;
	}

	public function setSubDominio($valor)
	{
		$this->subDominio = $valor;
	}

	public function setStatusSub($valor)
	{
		$this->statusSub = $valor;
	}

	public function setAction($valor)
	{
		$this->action = $valor;
	}

	public function setCodLicenca($valor)
	{
		$this->codLicenca = $valor;
	}

	public function check($validator)
	{
		$erro = "0";
		$erro_string = "";

		$this->validate = $validator;

		$this->validate->set('Sub Domínio', $this->subDominio)->is_required();
		//$this->validate->set('Status Sub Domínio', $this->statusSub)->is_required();

		$erro = "";
		$erro_string = "";
		
		$retorno_validate = $this->validate->get_errors();
		if (count($retorno_validate) > 0) {
			$erro = 1;
			foreach ($retorno_validate as $key => $value) {
				foreach ($value as $to_value) {
					$erro_string .= "$to_value \n";
				}
			}
		}
		
		return array("error" => $erro, "msg" => $erro_string);
    
    }

	public function getAll()
	{	
		if ($this->CodUser != "") {
			$sql = $this->conn->query("SELECT licenca.id, licenca.sub_dominio, licenca.status,
			licenca.key_licenca, clientes.nome FROM licenca
			LEFT JOIN clientes ON clientes.codigo = licenca.id_cliente
			WHERE licenca.id_cliente = '".addslashes($this->CodUser)."' ORDER BY licenca.id DESC");
			if ($sql->num_rows > 0) {
				$return = array();
				while($linha = $sql->fetch_array(MYSQLI_ASSOC)){ 
					array_push($return, $linha);
				}
				return $return;
			}
		}	
	}

	public function cadastrarLicenca()
	{
		if ($this->CodUser != "") {
			
			$keyRetorno = "";

			$finan = $this->newFinanceiro();
			$finan->setCodUser($this->CodUser);
			$retornoFinanceiro = $finan->checkStatusFinanceiro();

			if ($retornoFinanceiro == "1") {

				// Cria Fatura e Servico Adicional
				$novaFatura = new \App\Model\Faturas($this->conn);
				$novaFatura->idCliente($this->CodUser);
				$returnFatura = $novaFatura->novaFatura();

				$erro = $returnFatura["erro"];
				$msg = $returnFatura["msg"];
			    
				$key = $this->generateKey();	  

				if(!$this->conn->query("INSERT INTO licenca(sub_dominio,status,key_licenca,id_cliente,id_fatura)
					VALUES ('".$this->subDominio."','0','".$key."','".$this->CodUser."', '".$returnFatura["retorno_cadastra"]."')"))
				{ $erro = "1"; $msg = "Não foi possível cadastrar liçenca. #44"; }
				else{

					$erro = "0";
				    $msg = "";
				    $keyRetorno = $key;
					
				}		
			}
			elseif ($retornoFinanceiro == "2") {
				// Caso seja parceiro cria so a licenca
				$key = $this->generateKey();	  

				if(!$this->conn->query("INSERT INTO licenca(sub_dominio,status,key_licenca,id_cliente)
					VALUES ('".$this->subDominio."','0','".$key."','".$this->CodUser."')"))
				{ $erro = "1"; $msg = "Não foi possível cadastrar liçenca. #46"; }
				else{

					$erro = "0";
				    $msg = "";
				    $keyRetorno = $key;
					
				}		

			}
			else {
				$erro = "1";
				$msg = "Não foi possível cadastrar uma nova liçenca. Por favor entre em contato conosco!";
			}
		}
		else {
			$erro = "1";
		    $msg = "Não foi possível cadastrar liçenca. #ERR";
		}
		
		return array("error" => "$erro", "msg" => "$msg", "key" => "$keyRetorno");
	}

	private function newFinanceiro()
	{
		return new Financeiro($this->conn);
	}

	public function action()
	{
		if ($this->codLicenca != "") {
				
				if ($this->action == "ativar") {

					$financeiro = new Financeiro($this->conn);
					$financeiro->setCodUser($this->CodUser);
					$return_financreiro = $financeiro->checkStatusFinanceiro();
					if($return_financreiro == "1"){
						
						foreach($this->codLicenca as $codLicenca){	
							if(!$this->conn->query("UPDATE licenca SET status = '1' WHERE id = '$codLicenca'"))
							{ $erro = "1"; $msg = "Não foi possível alterar."; $sucess = "0";}
							else{
								$erro = "0";
							    $msg = "Liçenca(s) ativada(s) com sucesso!";
							    $sucess = "1";
							}
						}

					}
					elseif ($return_financreiro == "2") {
						// Caso seja parceiro cria so a licenca
						foreach($this->codLicenca as $codLicenca){	
							if(!$this->conn->query("UPDATE licenca SET status = '1' WHERE id = '$codLicenca'"))
							{ $erro = "1"; $msg = "Não foi possível alterar."; $sucess = "0";}
							else{
								$erro = "0";
							    $msg = "Liçenca(s) ativada(s) com sucesso!";
							    $sucess = "1";
							}
						}
				    }
					else {
						$erro = "1";
						$msg = "Não foi possível realizar está operação ##59";
						$sucess = "0";
					}
					

				}
				elseif ($this->action == "desativar") {
					
					foreach($this->codLicenca as $codLicenca){	
						if(!$this->conn->query("UPDATE licenca SET status = '0' WHERE id = '$codLicenca'"))
						{ $erro = "1"; $msg = "Não foi possível alterar."; $sucess = "0";}
						else{
							$erro = "0";
						    $msg = "Liçenca(s) desativada(s) com sucesso!";
						    $sucess = "1";
						}
					}

				}
				// elseif ($this->action == "remove") {
				// 	foreach($this->codLicenca as $codLicenca){	
				// 		if(!$this->conn->query("DELETE FROM licenca WHERE id = '$codLicenca'"))
				// 		{ $erro = "1"; $msg = "Não foi possível alterar."; $sucess = "0";}
				// 		else{
				// 			$erro = "0";
				// 		    $msg = "Liçenca(s) removida(s) com sucesso!";
				// 		    $sucess = "1";
				// 		}
				// 	}
				// }
				else {
					$erro = "1";
					$msg = "Não foi possível realizar a ação";
					$sucess = "0";
				}

		}
		else {
			$erro = "1";
			$msg = "Não foi possível realizar a ação";
			$sucess = "0";
		}

		return array("error" => "$erro", "msg" => "$msg", "sucess" => $sucess);
	}

}