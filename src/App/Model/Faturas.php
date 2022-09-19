<?php
namespace App\Model;
/**
*
*/
class Faturas
{
	use Ferramentas;

	private $valor;

	function __construct($conexao)
	{
		$this->conn = $conexao;
	}

	public function setDataFind($valor)
	{
		$this->dataFind = $valor;
	}

	public function setCodigoCliente($valor)
	{
		$this->codigoCliente = $valor;
	}

	public function idCliente($valor)
	{
		$this->idCliente = $valor;
	}

	public function setCodigoServico($valor)
	{
		$this->codigoServico = $valor;
	}

	public function setDataVencimento($valor)
	{
		$this->dataVencimento = $valor;
	}

	public function setValor($valor)
	{
		$this->valor = $valor;
	}

	public function setDescricao($valor)
	{
		$this->descricao = $valor;
	}

	public function setAction($valor)
	{
		return $this->action = $valor;
	}

	public function setStatus($valor)
	{
		return $this->status = $valor;
	}

	public function setIdFatura($valor)
	{
		return $this->idFatura = $valor;
	}

	public function cadastra()
	{
		$erro = 0;
		$msg = "";

			try {
				$this->conn->query("INSERT INTO faturas (codigo_cliente,codigo_servico,data_vencimento,valor,descricao,tipo)
					VALUES ('".$this->codigoCliente."','".$this->codigoServico."','".$this->convertDataBD($this->dataVencimento)."',
					'".$this->moneyFormatBD($this->valor)."','".$this->descricao."','s')") or $erro = 1;
				$ultimo_gravado = $this->conn->insert_id;
			} catch(Exception $e){
				$erro = 1;
				$msg = "Não foi possivel inserir dados de faturas - $e";
			}

		return array("erro" => $erro , "msg" => $msg, "ultimoId" => $ultimo_gravado);
	}

	public function edita()
	{
		$erro = 0;
		$msg = "Fatura alterada com sucesso!";
		if ($this->idFatura != "") {
			try {

				$this->dataVencimento = $this->convertDataBD($this->dataVencimento);
				$this->conn->query("UPDATE faturas SET status = '".$this->status."', data_vencimento = '".$this->dataVencimento."'
					, valor = '".$this->valor."', descricao = '".$this->descricao."'
					WHERE codigo = '".addslashes($this->idFatura)."'") or $erro = 1;
			} catch(Exception $e){
				$erro = 1;
				$msg = "Não foi possivel inserir dados de faturas - $e";
			}
		}

		return array("erro" => $erro , "msg" => $msg);
	}

	public function vencendoPorData()
	{
		$sql = $this->conn->query("SELECT clientes.nome, clientes.tipo_cliente, faturas.valor, faturas.data_vencimento, faturas.codigo FROM faturas
			LEFT JOIN clientes ON clientes.codigo = faturas.codigo_cliente
			LEFT JOIN servicos_adicionais ON servicos_adicionais.codigo = faturas.codigo_servico
			WHERE faturas.data_vencimento = '".$this->dataFind."' AND faturas.status = 'off'");
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

	public function vencidas()
	{
		$sql = $this->conn->query("SELECT clientes.nome, clientes.tipo_cliente, faturas.valor, faturas.data_vencimento, faturas.codigo FROM faturas
			LEFT JOIN clientes ON clientes.codigo = faturas.codigo_cliente
			LEFT JOIN servicos_adicionais ON servicos_adicionais.codigo = faturas.codigo_servico
			WHERE faturas.data_vencimento < NOW() AND faturas.status = 'off'");
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

	public function getCountFaturasAberta()
	{
		$sql = $this->conn->query("SELECT codigo FROM faturas WHERE status = 'on'");
		return $sql->num_rows;
	}

	public function getAllFaturas()
	{

		$sql = $this->conn->query("SELECT faturas.codigo, faturas.data_vencimento, faturas.valor, clientes.nome as nome_cliente,
		 clientes.tipo_cliente, faturas.status FROM faturas LEFT JOIN clientes ON faturas.codigo_cliente = clientes.codigo
		 ORDER BY faturas.data_vencimento DESC");
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

	public function getAllFaturasOpen()
	{

		$sql = $this->conn->query("SELECT faturas.codigo, faturas.data_vencimento, faturas.valor, clientes.nome as nome_cliente,
		 clientes.tipo_cliente, faturas.status FROM faturas LEFT JOIN clientes ON faturas.codigo_cliente = clientes.codigo
		 WHERE faturas.status = 'off' ORDER BY faturas.data_vencimento DESC");
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

	public function getAllFaturasClose()
	{

		$sql = $this->conn->query("SELECT faturas.codigo, faturas.data_vencimento, faturas.valor, clientes.nome as nome_cliente,
		 clientes.tipo_cliente, faturas.status FROM faturas LEFT JOIN clientes ON faturas.codigo_cliente = clientes.codigo
		 WHERE faturas.status = 'on' ORDER BY faturas.data_vencimento DESC");
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

	public function actions()
	{
		if($this->action == "0"){

			$erro = "1";
			$msg = "Selecione uma opção e fatura";
			$idFatura = "";
			$page = "";

		}
		elseif($this->idFatura == ""){

			$erro = "1";
			$msg = "Selecione uma opção e fatura";
			$idFatura = "";
			$page = "";

		}
		else {
			if ($this->action == "visualizar") {
				if ($this->idFatura != "" and  count($this->idFatura) == 1) {
					$idFatura = implode(",", $this->idFatura);
					$erro = "0";
					$msg = "";
					$page  = "visualizar";
				}
				else {
					$erro = "1";
					$msg = "Selecionar no maxímo uma opção para visualizar!";
					$page  = "0";
					$idFatura = "";
				}

			}
			if ($this->action == "remover") {
				$retorno_remove = $this->removeFatura();
				$erro = $retorno_remove["erro"];
				$msg = $retorno_remove["msg"];
				$page  = "0";
				$idFatura = "";
			}
			if ($this->action == "editar") {
				if ($this->idFatura != "" and  count($this->idFatura) == 1) {
					$idFatura = implode(",", $this->idFatura);
					$erro = "0";
					$msg = "";
					$page  = "editar";
				}
				else {
					$erro = "1";
					$msg = "Selecionar no maxímo uma opção para editar!";
					$page  = "0";
					$idFatura = "";
				}
			}

			if ($this->action == "quitar") {
					$return_quita = $this->quita();
					$erro = $return_quita["erro"];
					$msg = $return_quita["msg"];
					$page  = "0";
					$idFatura = "";
			}
		}

		return array("erro" => $erro , "msg" => $msg , "idFatura" => $idFatura, "page" => $page);
	}


	public function getFaturaById()
	{

		if ($this->idFatura != "" and  count($this->idFatura) == 1) {
			$sql = $this->conn->query("SELECT faturas.codigo, faturas.data_vencimento, faturas.valor, faturas.descricao, clientes.nome as nome_cliente,
			 clientes.tipo_cliente, faturas.status, faturas.codigo_cliente as codigo_cli FROM faturas LEFT JOIN clientes ON faturas.codigo_cliente = clientes.codigo
			 WHERE faturas.codigo = '".addslashes($this->idFatura)."'");
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

	public function removeFatura()
	{
		$erro = "0";
		$msg = "";

		if ($this->idFatura != "" and  count($this->idFatura) > 0) {
				foreach ($this->idFatura as $value) {
					$this->conn->query("DELETE FROM faturas WHERE codigo = '".addslashes($value)."'")
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

	public function removeFaturaByCliente()
	{
		$erro = "0";
		$msg = "";

		if ($this->idCliente != "" and count($this->idCliente) > 0) {

			if (is_array($this->idCliente)) {
				foreach ($this->idCliente as $value) {
					$this->conn->query("DELETE FROM faturas WHERE codigo_cliente = '".addslashes($value)."'");
				}
			}
			else {
				$this->conn->query("DELETE FROM faturas WHERE codigo_cliente = '".addslashes($this->idCliente)."'");
			}

		}
		else {
			$erro = "1";
			$msg = "Nenhum fatura";
		}

		if ($erro == "0") {
			$msg = "Excluido com sucesso!";
		}

		return array("erro" => $erro , "msg" => $msg);
	}

	public function quita()
	{
		$erro = "0";
		$msg = "";

		if ($this->idFatura != "" and  count($this->idFatura) > 0) {
				$fat_quitar = new FaturasQuitar($this->conn);
				$fat_quitar->idFatura($this->idFatura);
				$return = $fat_quitar->quita();
				$erro = $return["erro"];
				$msg = $return["msg"];
		}
		else {
			$erro = "1";
			$msg = "Escolha uma opção";
		}

		return array("erro" => $erro , "msg" => $msg);
	}


	public function novaFatura()
	{
		$erro = "0";
		$msg = "";
		$retorno_cadastra = "";

		// Quantidade de licenca do cliente
		$licenca = new Licenca($this->conn);
		$licenca->setTable("licenca");
		$quantidade = $licenca->getCountForIdCliente($this->idCliente);

		// Tipo de Cliente
		$cliente = new Clientes($this->conn);
		$cliente->setTable("clientes");
		$cliente = $cliente->getForId($this->idCliente);
		$tipo_cliente = $cliente["tipo_cliente"];

		if ($tipo_cliente == "r") {

			$sql = $this->conn->query("SELECT codigo, valor, codigo_servico FROM faturas
				WHERE codigo_cliente = '".$this->idCliente."' ORDER BY codigo DESC LIMIT 1");
			$linha = $sql->fetch_array(MYSQLI_ASSOC);

			if ($quantidade > 10) {

				$pagamento = new Pagamento($this->conn);
				$pagamento->setIdUser($this->idCliente);
				$retorno_pagamento = $pagamento->getFormaAndModelo();

				$novo_valor = $this->calculoLicencas($quantidade, $linha["valor"], $retorno_pagamento[0]["valor"]);
				$this->conn->query("UPDATE faturas SET valor = '".$novo_valor."' WHERE codigo = '".$linha["codigo"]."' LIMIT 1");

			}
			if ($linha["codigo_servico"] != "4" AND $quantidade == "1") {

				// Cadastra Fatura
				$pagamento = new Pagamento($this->conn);
				$pagamento->setIdUser($this->idCliente);
				$pagamento->where("servicos_modelos.codigo <> '4'");
				$return_pagamento = $pagamento->getFormaAndModeloWhithWhere();
				// Setup
				$data_vencimento = $this->dataAumentaDia(1);

				$this->setCodigoCliente($this->idCliente);
				$this->setCodigoServico($return_pagamento[0]["codigo"]);
				$this->setDataVencimento($data_vencimento);
				$this->setValor($this->moneyFormatView($return_pagamento[0]["valor"]));
				$this->setDescricao($return_pagamento[0]["descricao"]);
				$retorno_cadastra = $this->cadastra();

			}
		}

		// Caso o Cliente seja USUARIO (Uma Licenca e fatura por cadastro)
		if ($tipo_cliente == "u") {

			// Busca Dados
			$pagamento = new Pagamento($this->conn);
			$pagamento->setIdUser($this->idCliente);
			$pagamento->where("servicos_modelos.codigo <> '4'");
			$return_pagamento = $pagamento->getFormaAndModeloWhithWhere();

			$data_vencimento = $this->dataAumentaDia(1);

			if($quantidade != "1"){
				// Cadastra Servico Adicional
				$plano = new Planos($this->conn);
				$plano->codigoPlano($return_pagamento[0]["codigo_servico"]);
				$plano->codigoCliente($this->idCliente);
				$plano->codigoFormaPagamento($return_pagamento[0]["codigo_forma"]);
				$plano->dataPagamento($data_vencimento);
				$plano->valorPlano($return_pagamento[0]["valor"]);
				$plano->repetir("sim");
				$plano->periodo("1");
				$plano->totalParcelas("0");
				$plano->descricao($return_pagamento[0]["descricao"]);
				$plano->save();
			}

			$return_pagamento_atu = $pagamento->getFormaAndModeloWhithWhere();
     		// Cadastra nova fatura
			$this->setCodigoCliente($this->idCliente);
			$this->setCodigoServico($return_pagamento_atu[0]["codigo"]);
			$this->setDataVencimento($data_vencimento);
			$this->setValor($this->moneyFormatView($return_pagamento_atu[0]["valor"]));
			$this->setDescricao($return_pagamento_atu[0]["descricao"]);
			$retorno_cadastra = $this->cadastra();

		}


		$erro = "0";
		$msg = "";

		return array("erro" => $erro, "msg" => $msg, "retorno_cadastra" => $retorno_cadastra["ultimoId"]);
	}

}
