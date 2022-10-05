<?php

namespace App\Model;

/**
 *
 */
class Planos
{
	use Ferramentas;
	private $valor;
	private $erro;
	private $ruturnUltimoId;

	function __construct($conexao)
	{
		$this->conn = $conexao;
	}

	public function setTable($table)
	{
		$this->table = $table;
	}

	public function codigo($table)
	{
		$this->codigo = $table;
	}

	public function codigoPlano($valor)
	{
		return $this->codigoPlano = $valor;
	}

	public function codigoCliente($valor)
	{
		return $this->codigoCliente = $valor;
	}

	public function codigoFormaPagamento($valor)
	{
		return $this->codigoFormaPagamento = $valor;
	}

	public function dataPagamento($valor)
	{
		return $this->dataPagamento = $valor;
	}

	public function valorPlano($valor)
	{
		return $this->valorPlano = $valor;
	}

	public function repetir($valor)
	{
		return $this->repetir = $valor;
	}

	public function periodo($valor)
	{
		return $this->periodo = $valor;
	}

	public function parcelaAtual($valor)
	{
		return $this->parcelaAtual = $valor;
	}

	public function totalParcelas($valor)
	{
		return $this->totalParcelas = $valor;
	}

	public function descricao($valor)
	{
		return $this->descricao = $valor;
	}

	public function save()
	{
		$erro = "";

		$query = $this->conn->query("INSERT INTO servicos_adicionais(codigo_servico,codigo_cliente,data_pagto,codigo_forma_pagto,valor,
				repetir,periodo,total_parcelas,descricao)
				VALUES ('" . $this->codigoPlano . "', '" . $this->codigoCliente . "', '" . $this->dataPagamento . "',
				'" . $this->codigoFormaPagamento . "', '" . $this->valorPlano . "',
				'" . $this->repetir . "', '" . $this->periodo . "', '" . $this->totalParcelas . "', '" . $this->descricao . "')")
			or $erro = $this->conn->error;

		$this->ruturnUltimoId = $this->conn->insert_id;

		if ($erro != "") {
			return 0;
		} else {
			return 1;
		}
	}

	public function ultimoId()
	{
		if ($this->ruturnUltimoId != "") {
			return $this->ruturnUltimoId;
		}
	}

	public function getAllPlano()
	{
		$sql = $this->conn->query("SELECT * FROM " . $this->table . "");
		if ($sql->num_rows > 0) {
			$return = array();
			while ($linha = $sql->fetch_array(MYSQLI_ASSOC)) {
				array_push($return, $linha);
			}
			return $return;
		}
	}

	public function getAll()
	{
		$sql = $this->conn->query("SELECT sa.codigo as codigo_servico, sa.valor, sa.repetir, sa.periodo,
			sa.parcela_atual, sa.total_parcelas, sa.descricao,
			sm.codigo as codigo_modelo, sm.nome as nome_modelo, sm.descricao as descricao_modelo,
			cli.codigo as codigo_cliente, cli.nome as nome_cliente, cli.email1, cli.email2,
			cli.tipo_cliente, sa.status
			FROM servicos_adicionais AS sa
			LEFT JOIN servicos_modelos AS sm ON sa.codigo_servico = sm.codigo
			LEFT JOIN clientes as cli ON sa.codigo_cliente = cli.codigo");

		if ($sql->num_rows > 0) {
			$return = array();
			while ($linha = $sql->fetch_array(MYSQLI_ASSOC)) {
				if ($linha["valor"]) {
					$linha["valor"] = $this->moneyFormatView($linha["valor"]);
				}
				array_push($return, $linha);
			}
			return $return;
		}
	}

	public function changeStatusByCliente($status)
	{

		if (empty($this->codigoCliente) or $status == "") {
			return 0;
		}

		$sql = $this->conn->query("UPDATE servicos_adicionais SET status = '$status'
			WHERE codigo_cliente = '" . $this->codigoCliente . "'") or $erro = $this->conn->error;

		if ($erro == "") {
			return 1;
		}


		return 0;
	}

	public function removeByCliente()
	{
		$erro = "0";
		$msg = "";

		if ($this->codigoCliente != "" and count($this->codigoCliente) > 0) {

			if (is_array($this->codigoCliente)) {
				foreach ($this->codigoCliente as $value) {
					$this->conn->query("DELETE FROM servicos_adicionais WHERE codigo_cliente = '" . addslashes($value) . "'")
						or $erro = "1";
					$msg .= "Erro: $value \n";
				}
			} else {
				$this->conn->query("DELETE FROM servicos_adicionais
					WHERE codigo_cliente = '" . addslashes($this->codigoCliente) . "'")
					or $erro = "1";
				$msg .= "Erro: $value \n";
			}
		} else {
			$erro = "1";
			$msg = "Escolha uma opção";
		}

		if ($erro == "0") {
			$msg = "Excluido com sucesso!";
		}

		return array("erro" => $erro, "msg" => $msg);
	}
}
