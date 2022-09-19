<?php
namespace App\Model;

/**
*
*/
class Pagamento
{
	use Ferramentas;

	private $valor;

	function __construct($conexao)
	{
		$this->conn = $conexao;
	}

	public function setIdUser($valor)
	{
		return $this->idUser = $valor;
	}

	public function where($valor)
	{
		return $this->where = $valor;
	}

	public function esqueletoFormaPagamento($forma_pagto,$fatura) {

		$empresa = $this->conn->query("SELECT * FROM empresa");
		$dados_empresa = $empresa->fetch_array(MYSQLI_ASSOC);

		$formas_pagamento = $this->conn->query("SELECT * FROM formas_pagamento where codigo = '".$forma_pagto."'");
		$dados_forma_pagamento = $formas_pagamento->fetch_array(MYSQLI_ASSOC);

		if($dados_forma_pagamento["tipo_pagamento"] == 'deposito') {
		return "
		Banco: $dados_forma_pagamento[banco]<br>
		Agência: $dados_forma_pagamento[agencia]<br>
		Conta: $dados_forma_pagamento[conta]<br>
		Tipo: $dados_forma_pagamento[tipo_conta]<br>
		Documento: $dados_forma_pagamento[cpf_cnpj]<br>
		Titular: $dados_forma_pagamento[cedente]<br>
		";
		} elseif($dados_forma_pagamento["tipo_pagamento"] == 'pagseguro') {

		// Codifica código da fatura
		$fatura = $this->encode_decode($fatura,"E");

		return "Clique no botão/link abaixo, você será direcionado para o site PagSeguro.com.br para completar seu pagamento em ambiente seguro.<br><br><a href=\"".$dados_empresa["url_sistema"]."/public/pagamentos/pagseguro.php?codigo=".$fatura."\" target=\"_blank\"><img src=\"https://pagseguro.uol.com.br/Security/Imagens/btnPagarBR.jpg\" border=\"0\" alt=\"Pague com PagSeguro - é rápido, grátis e seguro!\" /></a>";

		} elseif($dados_forma_pagamento["tipo_pagamento"] == 'sendep') {

		// Codifica código da fatura
		$fatura = $this->encode_decode($fatura,"E");

		return "Clique no botão abaixo, você será direcionado para o site Sendep.com.br para completar seu pagamento em ambiente seguro.<br><br><a href=\"".$dados_empresa["url_sistema"]."/public/pagamentos/sendep.php?codigo=".$fatura."\" target=\"_blank\"><img src=\"http://www.sendep.com.br/buttons/paynow1.gif\" border=\"0\" alt=\"Pagamento Eletrônico Facilitado\" /></a>";

		} elseif($dados_forma_pagamento["tipo_pagamento"] == 'f2b') {

		// Codifica código da fatura
		$fatura = $this->encode_decode($fatura,"E");

		return "Clique no botão abaixo, você será direcionado para o site F2B.com.br para completar seu pagamento em ambiente seguro.<br><br><a href=\"".$dados_empresa["url_sistema"]."/public/pagamentos/f2b.php?codigo=".$fatura."\" target=\"_blank\"><img src=\"".$dados_empresa["url_sistema"]."/img/botoes/Botao_F2B.jpg\" border=\"0\" alt=\"Pagamento Eletrônico Facilitado\" /></a>";

		} elseif($dados_forma_pagamento["tipo_pagamento"] == 'paypal') {

		// Codifica código da fatura
		$fatura = $this->encode_decode($fatura,"E");

		return "Clique no botão abaixo, você será direcionado para o site PayPal.com para completar seu pagamento em ambiente seguro.<br><br><a href=\"".$dados_empresa["url_sistema"]."/public/pagamentos/paypal.php?codigo=".$fatura."\" target=\"_blank\"><img src=\"".$dados_empresa["url_sistema"]."/img/botoes/Botao_PayPal.jpg\" border=\"0\" alt=\"Pagamento Eletrônico PayPal\" /></a>";

		} elseif($dados_forma_pagamento["tipo_pagamento"] == 'pagamentodigital') {

		// Codifica código da fatura
		$fatura = $this->encode_decode($fatura,"E");

		return "Clique no botão abaixo, você será direcionado para o site PagamentoDigital.com para completar seu pagamento em ambiente seguro.<br><br><a href=\"".$dados_empresa["url_sistema"]."/public/pagamentos/pagamentodigital.php?codigo=".$fatura."\" target=\"_blank\"><img src=\"".$dados_empresa["url_sistema"]."/img/botoes/Botao_PagamentoDigital.jpg\" border=\"0\" alt=\"Pagamento Digital\" /></a>";

		} elseif($dados_forma_pagamento["tipo_pagamento"] == 'moip') {

		// Codifica código da fatura
		$fatura = $this->encode_decode($fatura,"E");

		return "Clique no botão abaixo, você será direcionado para o site MoIP.com para completar seu pagamento em ambiente seguro.<br><br><a href=\"".$dados_empresa["url_sistema"]."/public/pagamentos/moip.php?codigo=".$fatura."\" target=\"_blank\"><img src=\"".$dados_empresa["url_sistema"]."/img/botoes/Botao_MoIP.png\" border=\"0\" alt=\"MoIP\" /></a>";

		} else {

		// Codifica código da fatura
		$fatura = $this->encode_decode($fatura,"E");

		$botao_boleto = $dados_empresa["url_sistema"]."/img/botoes/".str_replace("php", "jpg", $dados_forma_pagamento["tipo_pagamento"]);

		return "<a href=\"".$dados_empresa["url_sistema"]."/public/pagamentos/boleto.php?codigo=".$fatura."\" target=\"_blank\"><img src=\"".$botao_boleto."\" border=\"0\" alt=\"Visualizar Boleto\" /></a>";
		}

	}

	public function getFormaAndModelo()
	{
		if ($this->idUser != "") {
			   $sql = $this->conn->query("SELECT servicos_modelos.codigo as codigo_modelo, servicos_modelos.nome as nome_modelo,
								formas_pagamento.codigo as codigo_forma, formas_pagamento.nome as formas_nome,
								servicos_adicionais.data_pagto, servicos_adicionais.codigo_servico as codigo_servico,
								servicos_adicionais.valor as valor, servicos_adicionais.descricao as descricao
								FROM servicos_adicionais
								LEFT JOIN servicos_modelos ON servicos_adicionais.codigo_servico = servicos_modelos.codigo
								LEFT JOIN formas_pagamento ON servicos_adicionais.codigo_forma_pagto = formas_pagamento.codigo
								WHERE servicos_adicionais.codigo_cliente = '".$this->idUser."';");

				if ($sql->num_rows > 0) {
					$return = array();
					while($linha = $sql->fetch_array(MYSQLI_ASSOC)){
						array_push($return, $linha);
					}
					return $return;
				}

		}
		else { $erro = "1"; $msg = "Nenhum Dado Disponível"; }

	}

	public function getFormaAndModeloWhithWhere()
	{
		if ($this->idUser != "") {
			   $sql = $this->conn->query("SELECT servicos_modelos.codigo as codigo_modelo, servicos_modelos.nome as nome_modelo,
								formas_pagamento.codigo as codigo_forma, formas_pagamento.nome as formas_nome,
								servicos_adicionais.data_pagto, servicos_adicionais.codigo_servico as codigo_servico,
								servicos_adicionais.valor as valor, servicos_adicionais.descricao as descricao,
								servicos_adicionais.codigo as codigo
								FROM servicos_adicionais
								LEFT JOIN servicos_modelos ON servicos_adicionais.codigo_servico = servicos_modelos.codigo
								LEFT JOIN formas_pagamento ON servicos_adicionais.codigo_forma_pagto = formas_pagamento.codigo
								WHERE servicos_adicionais.codigo_cliente = '".$this->idUser."' AND ".$this->where."
								ORDER BY servicos_adicionais.codigo DESC LIMIT 1");

				if ($sql->num_rows > 0) {
					$return = array();
					while($linha = $sql->fetch_array(MYSQLI_ASSOC)){
						array_push($return, $linha);
					}
					return $return;
				}

		}
		else { $erro = "1"; $msg = "Nenhum Dado Disponível"; }

	}
}
