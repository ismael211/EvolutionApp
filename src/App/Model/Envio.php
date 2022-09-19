<?php
namespace App\Model;
/**
* 
*/
class Envio
{

	public function setConfigSmtp($host, $port, $user, $senha)
	{
		$this->host = $host;
		$this->port = $port;
		$this->user = $user;
		$this->senha = $senha;

	}

	public function setDadosEmail($from, $nameFrom, $address, $nameAddress, $addressDois, $nameAddressDois, $subject, $body)
	{
		$this->from = $from;
		$this->nameFrom = $nameFrom;
		$this->address = $address;
		$this->nameAddress = $nameAddress;
		$this->addressDois = $addressDois;
		$this->nameAddressDois = $nameAddressDois;
		$this->subject = $subject;
		$this->body = $body;
	}

	public function enviaEmail()
	{

		$mail = new \PHPMailer;


		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = $this->host;  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = $this->user;                 // SMTP username
		$mail->Password = $this->senha;                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = $this->port;                                    // TCP port to connect to

		$mail->setFrom($this->from, $this->nameFrom);

		$mail->addAddress($this->address, $this->nameAddress);     // Add a recipient
		$mail->addReplyTo($this->addressDois, $this->nameAddressDois);

		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = $this->subject;
		$mail->Body    = $this->body;
		
		if(!$mail->send()) {
		    $msg = 'Message could not be sent.'. $mail->ErrorInfo;
		    $erro = "1";
		}
		else {
			$erro = "0";
			$msg = "";
		}

		return array("msg" => $msg, "erro" => $erro);

	}
}