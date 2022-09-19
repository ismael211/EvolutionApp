<?php
namespace App\Model;
/**
* Class de Seguranca
*/
class Seguranca
{

	public function setSession($sessao)
	{
		return $this->sessao = $sessao;
	}

	public function checaLogado()
	{
		if(isset($_SESSION[$this->sessao]) and $_SESSION[$this->sessao] != ""){
			return true;
		}
		else
		{
			return false;
		}
	}
}
