<?php
namespace App\Model;
/**
* 
*/
class FaturasQuitar
{
	use Ferramentas;

	private $valor;

	function __construct($conexao)
	{
		$this->conn = $conexao;
	}

	public function idFatura($valor)
	{
		$this->idFatura = $valor;
	}

	public function quita()
	{
		$erro = "0";
		$msg = "";
		if ($this->idFatura != "") {
			
			foreach ($this->idFatura as $value) {


				// Uso Geral
				$sql_dados_empresa = $this->conn->query("SELECT * FROM empresa");
				$dados_empresa = $sql_dados_empresa->fetch_array(MYSQLI_ASSOC);

				$sql_dados_sistema = $this->conn->query("SELECT * FROM sistema");
				$dados_sistema = $sql_dados_sistema->fetch_array(MYSQLI_ASSOC);

				$sql_dados_fatura = $this->conn->query("SELECT * FROM faturas where codigo = '".$value."'") or $erro = "1";
				$dados_fatura = $sql_dados_fatura->fetch_array(MYSQLI_ASSOC);

				$sql_dados_cliente = $this->conn->query("SELECT * FROM clientes where codigo = '".$dados_fatura["codigo_cliente"]."'")
				or $erro = "1";
				$dados_cliente = $sql_dados_cliente->fetch_array(MYSQLI_ASSOC);


				if ($dados_fatura["tipo"] == 's' and $dados_fatura["status"] != 'on' ) {

					// Quita a fatura
					$this->conn->query("UPDATE faturas SET status = 'on', data_pagamento = NOW()
						WHERE codigo = '".$dados_fatura["codigo"]."'") or $erro = "1";

					$sql_dados_servicos = $this->conn->query("SELECT * FROM servicos_adicionais
						WHERE codigo = '".$dados_fatura["codigo_servico"]."'") or $erro = "1";
					$dados_servicos = $sql_dados_servicos->fetch_array(MYSQLI_ASSOC);

					$sql_formas_pagamento = $this->conn->query("SELECT * FROM formas_pagamento 
						WHERE codigo = '".$dados_servicos["codigo_forma_pagto"]."'") or $erro = "1";
					$dados_formas_pagamento = $sql_formas_pagamento->fetch_array(MYSQLI_ASSOC);

					$sql_dados_textos = $this->conn->query("SELECT * FROM textos 
						WHERE codigo = '".$dados_formas_pagamento["texto_confirmacao"]."'") or $erro = "1";
					$dados_textos = $sql_dados_textos->fetch_array(MYSQLI_ASSOC);

					if($dados_servicos["repetir"] == 'sim') {
					if($dados_servicos["parcela_atual"] < $dados_servicos["total_parcelas"] || $dados_servicos["total_parcelas"] == 0) {

						list($ano,$mes,$dia) = explode("-",$dados_fatura["data_vencimento"]);
						$vencimento_proxima_fatura = date("Y-m-d",mktime (0, 0, 0, $mes+$dados_servicos["periodo"],
							$dados_servicos["data_pagto"], $ano));

						$parcela_atual = $dados_servicos["parcela_atual"]+1;

						$this->conn->query("UPDATE servicos_adicionais SET parcela_atual = '$parcela_atual' 
						WHERE codigo = '".$dados_servicos["codigo"]."'") or $erro = "1";


						// Verifica se existe alguma fatura com os mesmo dados da próxima fatura
						$total_faturas_atuais = $this->conn->query("SELECT * FROM faturas 
							WHERE codigo_cliente = '".$dados_servicos["codigo_cliente"]."'
							AND codigo_servico = '".$dados_servicos["codigo"]."'
							AND data_vencimento = '".$vencimento_proxima_fatura."'
							AND valor = '".$dados_servicos["valor"]."' AND status = 'off'") or $erro = "1";

						if($total_faturas_atuais->num_rows == "0") {

							$this->conn->query("INSERT INTO faturas (codigo_cliente,codigo_servico,data_vencimento,valor,descricao,tipo) 
								VALUES ('$dados_servicos[codigo_cliente]','$dados_servicos[codigo]','$vencimento_proxima_fatura',
								'$dados_servicos[valor]','$dados_servicos[descricao]','s')") or $erro = "1";

						}

					
						if ($erro != "1") {


							// Muda o status da licenca para ativado
							$licenca_muda_status = new Licenca($this->conn);
							$licenca_muda_status->setIdFatura($value);
							$licenca_muda_status->setStatusLicenca("1");
							$licenca_muda_status->mudaByidFatura();


							// Chama Envio Email
							$email = new Envio();
							$email->setConfigSmtp($dados_sistema["servidor_smtp"], $dados_sistema["servidor_smtp_porta"],
								$dados_sistema["servidor_smtp_usuario"], $dados_sistema["servidor_smtp_senha"]);
							
							$data_vencimento = $this->convertDataView($dados_fatura["data_vencimento"]);
							$valor = $this->moneyFormatView($dados_fatura["valor"]);

							$mensagem_editada = str_replace ( '[cliente_nome]' , $dados_cliente["nome"] , $dados_textos["texto"] );
							$mensagem_editada = str_replace ( '[cliente_email_principal]' , $dados_cliente["email1"] , $mensagem_editada );
							$mensagem_editada = str_replace ( '[cliente_senha]' , $dados_cliente["senha"] , $mensagem_editada );
							$mensagem_editada = str_replace ( '[vencimento]' , $data_vencimento , $mensagem_editada );
							$mensagem_editada = str_replace ( '[valor]' , "R$ ".$valor , $mensagem_editada );
							$mensagem_editada = str_replace ( '[descricao]' , $dados_fatura["descricao"] , $mensagem_editada );
							$mensagem_editada = str_replace ( '[empresa_nome]' , $dados_empresa["nome"] , $mensagem_editada );
							$mensagem_editada = str_replace ( '[empresa_url_site]' , $dados_empresa["url_site"] , $mensagem_editada );
							$mensagem_editada = str_replace ( '[empresa_url_sistema]' , $dados_empresa["url_sistema"] , $mensagem_editada );
							$mensagem_editada = str_replace ( '[empresa_url_logo]' , $dados_empresa["logo"] , $mensagem_editada );
							$mensagem_editada = str_replace ( '[empresa_email]' , $dados_empresa["email"] , $mensagem_editada );

							$email->setDadosEmail($dados_empresa["email"], $dados_empresa["nome"], 
								$dados_cliente["email1"], $dados_cliente["nome"], 
								$dados_cliente["email2"], $dados_cliente["nome"], 
								$dados_textos["titulo"], 
								$mensagem_editada);

							// $return = $email->enviaEmail();
							// if ($return["erro"] == "1") {
							// 	$erro = "1";
							// 	$msg = $return["msg"];
							// }
						}

					}
					}

				} else { $erro == "1";}


			} // Fim foreach
			
		} else { $erro = "1";}

		if ($erro == "1") {
			$msg = "Nao Foi possivel quitar fatura(s)";
		}
		else {
			$msg = "Fatura(s) Quitada(s) com sucesso!";
		}

		return array("erro" => $erro , "msg" => $msg);
	}

}