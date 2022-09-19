<?php
namespace App\Model\Cliente;

/**
* 
*/
class Login
{	
	private $conexao;
	private $cnt;
	
	function __construct($conexao)
	{
		$this->conn = $conexao;
	}

	public function setUser($user)
	{
		$this->user = $user;
	}

	public function setPass($pass)
	{
		$this->pass = $pass;
	}

	public function toLogin()
	{
			if ($this->user == "") {
				$return = array("error" => "1", "msg" => "Usuário em branco!");
			}
			elseif($this->pass == ""){
				$return = array("error" => "1", "msg" => "Senha em branco!");
			}
			else{

				$query = $this->conn->query("SELECT codigo,nome,email1,senha FROM clientes
					WHERE email1 = '".addslashes($this->user)."' LIMIT 1");
				$cnt = $query->num_rows;
				if ($cnt == 1) {
					$linha = $query->fetch_array();
					if (password_verify($this->pass, $linha["3"])) {
						$codigo = $linha["0"];
						$nome = $linha["1"];
						$return = array("error" => "0", "codigo" => "$codigo", "nome" => "$nome");
					}
					else {
						$return = array("error" => "1", "msg" => "Usuário ou Senha Inválido!");
					}
				}
				else{
					$return = array("error" => "1", "msg" => "Usuário ou Senha Inválido!");
				}

		    }
			return $return;
	}

}