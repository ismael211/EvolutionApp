<?php
namespace App\Model;
/**
* 
*/
class LicencaCadastro
{
	use Ferramentas;

	private $valor;

	function __construct($conexao)
	{
		$this->conn = $conexao;
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

	public function setup($valor)
	{
		return $this->setup = $valor;
	}

	public function check($validator)
	{
		$this->validate = $validator;

		$this->validate->set('Cliente', $this->cliente)->is_required();
		$this->validate->set('Sub-Domínio', $this->subDominio)->is_required();
		$this->validate->set('Key', $this->key)->is_required();
		
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

	public function save()
	{
		$erro = "0";
		$msg = "";
		try{

			$cliente = new Clientes($this->conn);
			$cliente->setId($this->cliente);
			$returnCliente = $cliente->ClienteParceiro();
			
			if ($returnCliente == 0) {
				$novaFatura = new Faturas($this->conn);
				$novaFatura->idCliente($this->cliente);
				$returnFatura = $novaFatura->novaFatura();

				$erro = $returnFatura["erro"];
				$msg = $returnFatura["msg"];
				$id_fatura = $returnFatura["retorno_cadastra"];
			}
			else {
				$id_fatura = 0;
			}
			
			

			$query = $this->conn->query("INSERT INTO licenca(sub_dominio,status,key_licenca,id_cliente,id_fatura,data_cadastro)
				VALUES('".$this->subDominio."', '".$this->status."','".strtoupper($this->key)."','".$this->cliente."',
				'".$id_fatura."', NOW())")
			or $erro = $this->conn->error();

			// Caso selecionado o Setup
			if ($this->setup != "") {

				$data_vencimento = $this->dataAumentaDia("1");

				$modelo_setup = new ModeloPlanos($this->conn);
				$modelo_setup->setCodigo('4');
				$return_modelo = $modelo_setup->getModelos();
				
				$pagamento_setup = new Pagamento($this->conn);
				$pagamento_setup->setIdUser($this->cliente);
				$return_pagamento = $pagamento_setup->getFormaAndModelo();

				// Cadastro servivo adicional/Plano
				$plano_setup = new Planos($this->conn);
				$plano_setup->codigoPlano($return_modelo["codigo"]);
				$plano_setup->codigoCliente($this->cliente);
				$plano_setup->codigoFormaPagamento($return_pagamento[0]["codigo_forma"]);
				$plano_setup->dataPagamento($data_vencimento);
				$plano_setup->valorPlano($return_modelo["valor"]);
				$plano_setup->repetir("nao");
				$plano_setup->periodo("1");
				$plano_setup->totalParcelas("0");
				$plano_setup->descricao($return_modelo["descricao"]);
				$plano_setup->save();

				// Setup
				$fatura_setup = new Faturas($this->conn);
				$fatura_setup->setCodigoCliente($this->cliente);
				$fatura_setup->setCodigoServico($plano_setup->ultimoId());
				$fatura_setup->setDataVencimento($data_vencimento);
				$fatura_setup->setValor($return_modelo["valor"]);
				$fatura_setup->setDescricao($return_modelo["descricao"]);
				$fatura_setup->cadastra();
				
			}

		} catch (Exception $e){
			return array("error" => "1", "msg" => "Não foi possivel registrar. #$e");
		}
	    
	    return array("error" => $erro, "msg" => $msg);
	}

	public function genKey()
	{
		return $this->generateKey();
	}

}