<?php
namespace App\Model;

/**
*
*/
class ClienteEditar
{
	use Ferramentas;
	private $valor;
	private $retorno;

	function __construct($conexao)
	{
		return $this->conn = $conexao;
	}

	public function setCodCliente($valor)
	{
		return $this->codigoCliente = $valor;
	}

	public function tipoCliente($valor)
	{
		return $this->tipoCliente = $valor;
	}

	public function nome($valor)
	{
		return $this->nome = $valor;
	}

	public function tipoPessoa($valor)
	{
		return $this->tipoPessoa = $valor;
	}

	public function rg($valor)
	{
		return $this->rg = $valor;
	}

	public function cpf($valor)
	{
		return $this->cpf = $valor;
	}

	public function dataNascimento($valor)
	{
		return $this->dataNascimento = $valor;
	}

	public function cnpj($valor)
	{
		return $this->cnpj = $valor;
	}

	public function razaoSocial($valor)
	{
		return $this->razaoSocial = $valor;
	}

	public function telefone($valor)
	{
		return $this->telefone = $valor;
	}

	public function celular($valor)
	{
		return $this->celular = $valor;
	}

	public function email($valor)
	{
		return $this->email = $valor;
	}

	public function emailSecundario($valor)
	{
		return $this->emailSecundario = $valor;
	}

	public function senha($valor)
	{
		return $this->senha = $valor;
	}

	public function rSenha($valor)
	{
		return $this->rSenha = $valor;
	}

	public function obs($valor)
	{
		return $this->obs = $valor;
	}

	public function status($valor)
	{
		return $this->status = $valor;
	}

	public function tipoPlano($valor)
	{
		return $this->tipoPlano = $valor;
	}

	public function formaPagamento($valor)
	{
		return $this->formaPagamento = $valor;
	}

	public function diaVencimento($valor)
	{
		return trim($this->diaVencimento = $valor);
	}

	public function edita()
	{
		if ($this->codigoCliente != "") {

			if ($this->senha != "" and $this->rSenha != "") {
				if ($this->rSenha == $this->senha) {

					$senha = password_hash(
						$this->senha,
						PASSWORD_DEFAULT,
						['cost' => 12]
					    );
					$clasula_mod_senha = ",senha='".$senha."'";
					$msg = "";
				}
				else{
					$clasula_mod_senha = "";
					$erro = 1;
					$msg = "Senhas não conferem!";
				}
			}
			else {
				$clasula_mod_senha = "";
			}

			if($this->conn->query("UPDATE clientes SET nome='".$this->nome ."',email1='".$this->email ."',
				email2='".$this->emailSecundario."',
			fone='".$this->telefone."',celular='".$this->celular."',tipo_pessoa='".$this->tipoPessoa."', cpf='".$this->cpf."',
			rg='".$this->rg."',razao_social='".$this->razaoSocial."',cnpj='".$this->cnpj."',obs='".$this->obs."',status='".$this->status."',
			data_nac='".$this->convertDataBD($this->dataNascimento)."',tipo_cliente='".$this->tipoCliente."'$clasula_mod_senha
			WHERE codigo = '".$this->codigoCliente."'")){

					if($this->conn->query("UPDATE servicos_adicionais SET codigo_servico='".$this->tipoPlano."',codigo_forma_pagto='".$this->formaPagamento."',data_pagto='".$this->diaVencimento."' WHERE codigo_cliente = '".$this->codigoCliente ."'"))
					{
						$msg = "Cliente Editado com sucesso!";
						$erro = "0";
					}
					else {
						$msg = "Não foi possivel salvar edição";
						$erro = "1";
					}
			}


		}
		else {
			$erro = "1";
			$msg = "Nenhum cliente para  salvar edição";
		}

		$return = array("erro" => "$erro", "msg" => "$msg");
		return $return;
	}




}
