<?php
//////////////////////////////////////////////////////////////////////////
// Isistem Gerenciador Financeiro para Hosts  		                    //
// Descrição: Sistema de Gerenciamento de Clientes		                //
// Site: www.isistem.com.br       										//
//////////////////////////////////////////////////////////////////////////


require_once('PHPMailer/PHPMailerAutoload.php');

function envia_Email($nome_cliente,$email_cliente1,$email_cliente2,$assunto,$mensagem) {

	// Uso Geral
	include('ds8.php');
	$mysqli = new mysqli($dados_conn["host"], $dados_conn["user"], $dados_conn["pass"], $dados_conn["db"]);


	$sql_dados_empresa = $mysqli->query("SELECT * FROM empresa");
	$dados_empresa = $sql_dados_empresa->fetch_array(MYSQLI_ASSOC);

	$sql_dados_sistema = $mysqli->query("SELECT * FROM sistema");
	$dados_sistema = $sql_dados_sistema->fetch_array(MYSQLI_ASSOC);


	$mail = new PHPMailer;

	//$mail->SMTPDebug = 3;                               // Enable verbose debug output

	$mail->isSMTP();                                      				// Set mailer to use SMTP
	$mail->Host = $dados_sistema["servidor_smtp"];          			     // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                              				 // Enable SMTP authentication
	$mail->Username = $dados_sistema["servidor_smtp_usuario"];      	 	// SMTP username
	$mail->Password = $dados_sistema["servidor_smtp_senha"];             // SMTP password
	$mail->SMTPSecure = 'tls';                           			 	// Enable TLS encryption, `ssl` also accepted
	$mail->Port = $dados_sistema["servidor_smtp_porta"];   				 // TCP port to connect to

	$mail->From = $dados_empresa["email"];
	$mail->FromName =  $dados_empresa["nome"];


	$mail->addAddress($email_cliente1, $nome_cliente);     // Add a recipient

	if($email_cliente2) {
		$mail->addCC($email_cliente2, $nome_cliente);
	}


	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = $assunto;
	$mail->Body    = $mensagem;



	if($mail->Send()){
		return "ok";
	} else {
		return $mail->ErrorInfo;
	}

}


?>
