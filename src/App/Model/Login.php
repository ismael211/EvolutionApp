<?php
namespace App\Model;
/**
*
*/
class Login
{
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

	public function setTable($table)
	{
		$this->table = $table;
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

			$query = $this->conn->query("SELECT codigo,nome,senha FROM ".$this->table."
				WHERE login = '".addslashes($this->user)."' LIMIT 1");
			$cnt = $query->num_rows;
			if ($cnt == 1) {
				$linha = $query->fetch_array();
				if (password_verify($this->pass, $linha["2"])) {
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
