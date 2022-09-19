<?php
namespace App\Model;

/**
* 
*/
class ClienteCadastro
{
	private $valor;
	private $retorno;

	function __construct($conexao)
	{
		return $this->conn = $conexao;
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

	public function parceiro($valor)
	{
		return $this->parceiro = $valor;
	}

	public function check($validator)
	{
		$erro = "0";
		$erro_string = "";

		$this->validate = $validator;

		$this->validate->set('Tipo Cliente', $this->tipoCliente)->is_required();
		$this->validate->set('Nome', $this->nome)->is_required();

		$this->validate->set('Tipo Pessoa', $this->tipoPessoa)->is_required();

		if ($this->tipoPessoa == "fisica") {

			$this->validate->set('RG', $this->rg)->is_required();
			$this->validate->set('CPF', $this->cpf)->is_required()->is_cpf();
			$this->validate->set('Data de Nascimento', $this->dataNascimento)->is_required();

		}

		if ($this->tipoPessoa == "juridica") {

			$this->validate->set('CNPJ', $this->cnpj)->is_required()->is_cnpj();
			$this->validate->set('Razão Social', $this->razaoSocial)->is_required();

		}
		
		$this->validate->set('Telefone', $this->telefone)->is_required()->is_phone();
		$this->validate->set('Celular', $this->celular)->is_required()->is_phone();
		$this->validate->set('Email', $this->email)->is_required()->is_email();
		$this->validate->set('Email Secundário', $this->emailSecundario)->is_required()->is_email();
		$this->validate->set('Repetir Senha', $this->rSenha)->is_required();
		$this->validate->set('Senha', $this->senha)->is_required()->is_equals($this->rSenha);
		$this->validate->set('Status', $this->status)->is_required();

		$this->validate->set('Tipo de Plano', $this->tipoPlano)->is_required();
		$this->validate->set('Forma de Pagamento', $this->formaPagamento)->is_required();
		$this->validate->set('Dia de Vencimento', $this->diaVencimento)->is_required()->min_value(00)->max_value(31);
		
		
		$retorno_validate = $this->validate->get_errors();
		if (count($retorno_validate) > 0) {
			$erro = 1;
			$erro_string = "";
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
			
		$id_cliente = $this->saveCliente();

		if($id_cliente != ""){

			$plano = $this->newPlano($this->conn);
			$modeloPlanos = $this->newModeloPlanos($this->conn);

			$modeloPlanos->setCodigo($this->tipoPlano);
			$return = $modeloPlanos->getModelos();
			$valorPlano = $return["valor"];

			if ($valorPlano == "0" or $valorPlano == "") {
				$erro = 1;
				$msg .= "Sem valor de plano! \n";
			}

			$plano->codigoPlano($this->tipoPlano);
			$plano->codigoCliente($id_cliente);
			$plano->codigoFormaPagamento($this->formaPagamento);
			$plano->dataPagamento($this->diaVencimento);
			$plano->valorPlano($valorPlano);
			$plano->repetir("sim");
			$plano->periodo("1");
			$plano->totalParcelas("0");
			$plano->descricao($return["descricao"]);
			
			if ($plano->save() == 0) {
				$erro = 1;
				$msg .= "Plano Não Cadastrado! \n";
			}

			
		}
		else{
			$erro = 1;
			$msg .= "Cliente Não Cadastrado! \n";
		}

		if ($erro == 1) {
			$return = array("error" => "1", "msg" => "$msg");
		}
		else {
			$return = array("error" => "0", "msg" => "$msg");
		}

	}

	private function saveCliente()
	{
			$erro = 0;
			$msg = 0;
			try{

				$data_cadastro = date("Y-m-d");

				if($this->dataNascimento != ""){
					$ex = explode("/", $this->dataNascimento);
					$dataNascimento = $ex[2] ."-". $ex[1] ."-". $ex[0];
			    }
			    else {
			    	$dataNascimento = date("Y-m-d");
			    }

				$senha = password_hash(
					$this->senha,
					PASSWORD_DEFAULT,
					['cost' => 12]
				    );

				$query = $this->conn->query("INSERT INTO clientes(nome,email1,email2,fone,celular,tipo_pessoa,cpf,rg,razao_social,
					cnpj,data_cadastro,status,senha,data_nac,tipo_cliente,obs,parceiro) 
				VALUES ('".$this->nome."','".$this->email."','".$this->emailSecundario."','".$this->telefone."','".$this->celular."',
				'".$this->tipoPessoa."','".$this->cpf."','".$this->rg."','".$this->razaoSocial."','".$this->cnpj."','$data_cadastro',
				'".$this->status."','".$senha."','".$dataNascimento."','".$this->tipoCliente."', '".$this->obs."',
				'".$this->parceiro."')");

				// Salva Serviço (Plano)
				$id_cliente = $this->conn->insert_id;

			} catch (Exception $e){
				$erro = 1;
				$msg .= "Não foi possivel registrar. #$e \n";
			}

		    return $id_cliente;
	}

	private function newPlano($conexao)
	{
		return new Planos($conexao);
	}

	private function newModeloPlanos($conexao)
	{
		return new ModeloPlanos($conexao);
	}

}