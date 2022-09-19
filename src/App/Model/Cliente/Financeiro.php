<?php
namespace App\Model\Cliente;

/**
* 
*/
class Financeiro
{
	use \App\Model\Ferramentas;

	function __construct($conexao)
	{
		$this->conn = $conexao;
	}

	public function setCodUser($cod)
	{
		return $this->CodUser = $cod;
	}

	public function getAllFaturas()
	{

		if ($this->CodUser != "") {
			$sql = $this->conn->query("SELECT codigo, data_vencimento, data_pagamento, valor, status, descricao FROM faturas
						WHERE codigo_cliente = '".$this->CodUser."' ORDER BY data_vencimento DESC ");
			if ($sql->num_rows > 0) {
				$return = array();
				while($linha = $sql->fetch_array(MYSQLI_ASSOC)){ 
					if ($linha["data_vencimento"]) {
						$linha["data_vencimento"] = $this->convertDataView($linha["data_vencimento"]);
					}
					if ($linha["valor"]) {
						$linha["valor"] = $this->moneyFormatView($linha["valor"]);
					}
					array_push($return, $linha);
				}
				return $return;
			}
		}
	
	}

	public function getAllFaturasOpen()
	{

		if ($this->CodUser != "") {
			$sql = $this->conn->query("SELECT codigo, data_vencimento, data_pagamento, valor, status, descricao FROM faturas
						WHERE codigo_cliente = '".$this->CodUser."' AND status = 'off' ORDER BY data_vencimento DESC ");
			if ($sql->num_rows > 0) {
				$return = array();
				while($linha = $sql->fetch_array(MYSQLI_ASSOC)){ 
					if ($linha["data_vencimento"]) {
						$linha["data_vencimento"] = $this->convertDataView($linha["data_vencimento"]);
					}
					if ($linha["valor"]) {
						$linha["valor"] = $this->moneyFormatView($linha["valor"]);
					}
					array_push($return, $linha);
				}
				return $return;
			}
		}
	
	}

	public function getFaturaById($id)
	{
		if ($id != "") {
			$sql = $this->conn->query("SELECT codigo, data_vencimento, data_pagamento, valor, status, descricao FROM faturas
						WHERE codigo = '".addslashes($id)."' ");
			if ($sql->num_rows > 0) {
				$return = array();
				while($linha = $sql->fetch_array(MYSQLI_ASSOC)){ 
					if ($linha["data_vencimento"]) {
						$linha["data_vencimento"] = $this->convertDataView($linha["data_vencimento"]);
					}
					if ($linha["valor"]) {
						$linha["valor"] = $this->moneyFormatView($linha["valor"]);
					}
					array_push($return, $linha);
				}
				return $return;
			}
		}
	}

	public function formaDePagamento($fatura,$codigo_cliente)
	{
		if ($codigo_cliente != "") {
			$sql = $this->conn->query("SELECT codigo_forma_pagto FROM servicos_adicionais
						WHERE codigo_cliente = '".addslashes($codigo_cliente)."' ");
			if ($sql->num_rows > 0) {
				$linha = $sql->fetch_array(MYSQLI_ASSOC);
				$np = $this->newPagamento();
				$pagamento = $np->esqueletoFormaPagamento($linha["codigo_forma_pagto"],$fatura);

				return $pagamento;
		    }
		}

	}

	private function newPagamento(){
		return new \App\Model\Pagamento($this->conn);
	}

	/**
	 * [checkStatusFinanceiro retorna o status do cliente perante o financeiro]
	 * @return [int] [1 para ok, 0 para inadiplente]
	 */
	public function checkStatusFinanceiro()
	{	
		$cli = new Cliente($this->conn);
		$cli->setCodUser(addslashes($this->CodUser));
		$return_parc = $cli->isParceiro();

		$dataAtual = date("Y-m-d H:i:s");
		$sql = $this->conn->query("SELECT status FROM faturas
						WHERE codigo_cliente = '".addslashes($this->CodUser)."' AND (status = 'off' AND data_vencimento < '$dataAtual')");
		$cnt = $sql->num_rows;
		
		if($return_parc == "1"){
			$return = 2; // É um parceiro
		}
		else {
			if ($cnt > 0) {
				$return = 0;
			}
			else{
				$return = 1;
			}
		}
		

		return $return;
	}

}