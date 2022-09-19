<?php
namespace App\Model;

/**
* 
*/
class LicencaEnvios
{

	private $valor;
	private $conexao;

	function __construct($conexao)
	{
		$this->conn = $conexao;
	}

	public function idLicenca($valor)
	{
		$this->idLicenca = $valor;
	}

	public function boasVindas()
	{
		if ($this->idLicenca != "") {

			$cliente = new Clientes($this->conn);
			$cliente->setTable("clientes");
			
			$licenca = new Licenca($this->conn);
			$licenca->setIdLicenca($this->idLicenca);
			$lic = $licenca->getById();
			
			$returnCliente = $cliente->getForId($lic[0]["codigo_cliente"]);

			$sql_dados_sistema = $this->conn->query("SELECT * FROM sistema");
			$dados_sistema = $sql_dados_sistema->fetch_array(MYSQLI_ASSOC);
			
			$envio = new Envio();
			$envio->setConfigSmtp($dados_sistema["servidor_smtp"], $dados_sistema["servidor_smtp_porta"],
								$dados_sistema["servidor_smtp_usuario"], $dados_sistema["servidor_smtp_senha"]);
			$envio->setDadosEmail("atendimento@isistem.com.br", "Isistem Gerenciador Financeiro",
				$returnCliente["email1"], $returnCliente["nome"], 
				$returnCliente["email2"], $returnCliente["nome"], $this->subject(), $this->body());
		    $envio->enviaEmail();
		    
		}
	}

	public function subject()
	{
		return "[Isistem] - Bem Vindo ";
	}

	public function body()
	{	

		$cliente = new Clientes($this->conn);
		$cliente->setTable("clientes");
		
		$licenca = new Licenca($this->conn);
		$licenca->setIdLicenca($this->idLicenca);
		$lic = $licenca->getById();
		
		$returnCliente = $cliente->getForId($lic[0]["codigo_cliente"]);

		$sql = $this->conn->query("SELECT texto FROM textos WHERE codigo = '34'");
		$dados_textos = $sql->fetch_array(MYSQLI_ASSOC);
		
		$mensagem_editada = str_replace ( '[cliente_nome]' , $returnCliente["nome"] , $dados_textos["texto"] );
		$mensagem_editada = str_replace ( '[key]' , $lic[0]["key_licenca"] , $mensagem_editada );
		$mensagem_editada = str_replace ( '[linksistema]' , $lic[0]["sub_dominio"] , $mensagem_editada );
		$mensagem_editada = str_replace ( '[cliente_email_principal]' , $returnCliente["email1"] , $mensagem_editada );

		return $mensagem_editada;
	}

}