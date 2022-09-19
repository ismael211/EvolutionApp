<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>
<HTML>
<HEAD>
<TITLE><? echo $dadosboleto["cedente"]; ?></TITLE>
<META http-equiv=Content-Type content=text/html; charset=windows-1252>
<style type="text/css">
<!--
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
}
body {
	background-color: #FFFFFF;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.titulo {
	font-size: 16px;
	font-weight: bold;
}
.campo_descricao {
	font-size: 9px;
}
.campo_importante {
	font-weight: bold;
}
.boleto_codigo {
	font-size: 24px;
	font-weight: bold;
}
-->
</style>
</head>
<body>
<table width="640" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="640" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td class="campo_importante">Instru&ccedil;&otilde;es para pagamento deste boleto em ag&ecirc;ncias banc&aacute;rias:</td>
      </tr>
      <tr>
        <td class="campo_descricao">&bull; no Internet Explorer, configure-o para usar fontes tamanho m&eacute;dio (menu Exibir &gt; Tamanho do texto &gt; M&eacute;dio);<br />
&bull; para o Netscape Navigator, configure-o para utilizar as fontes definidas no documento, tamanho 12 (menu Editar &gt; Prefer&ecirc;ncias &gt; Fontes &gt; tamanho da fonte de largura vari&aacute;vel igual a 12 e selecionar &quot;Usar Fontes do Documento&quot;);<br />
&bull; imprima em impressora tipo jato-de-tinta (<i>ink je</i>t) ou laser;<br />
&bull; configure a impressora para modo normal de impress&atilde;o, n&atilde;o use op&ccedil;&atilde;o rascunho; <br />
&bull; imprimir em folha branca A4 ou tamanho carta;<br />
&bull; corte nas duas linhas indicadas e n&atilde;o fure, dobre, amasse, rasure ou risque o c&oacute;digo de barras.</td>
      </tr>
    </table><table width="640" cellspacing="0" cellpadding="0" border="0" align="Default">
  <tr>
    <td><img src="<? echo $dadosboleto["empresa_logo"]; ?>" alt="Logo" /></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td style="border-bottom:1px #000000 dashed"><div align="right" class="campo_descricao">Corte na linha pontilhada</div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="640" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="140"><img src="boletos/imagens/logo_sicredi.jpg" width="106" height="31" /></td>
        <td width="100" align="center" valign="bottom"><span class="boleto_codigo">| 748-X |</span></td>
        <td width="400" align="right" valign="bottom"><span class="titulo">RECIBO DO SACADO</span></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="640" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
      <tr>
        <td width="300" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Cedente</td>
          </tr>
          <tr>
            <td><? echo $dadosboleto["cedente"]; ?></td>
          </tr>
        </table></td>
        <td width="120" align="left" valign="top"><table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td class="campo_descricao">&Acirc;g&ecirc;ncia/Cod. Cedente </td>
          </tr>
          <tr>
            <td><? echo $dadosboleto["agencia"] . "." . $dadosboleto["posto_cedente"] . "." . RetiraDigito($dadosboleto["conta"]); ?></td>
          </tr>
        </table></td>
        <td width="120" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Data do Documento </td>
          </tr>
          <tr>
            <td><? echo $dadosboleto["data_documento"]?></td>
          </tr>
        </table></td>
        <td width="100" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Vencimento</td>
          </tr>
          <tr>
            <td align="right" class="campo_importante"><? echo $dadosboleto["data_vencimento"]?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Sacado</td>
          </tr>
          <tr>
            <td><? echo $dadosboleto["sacado"]?></td>
          </tr>
        </table></td>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">N&uacute;mero do Documento </td>
          </tr>
          <tr>
            <td><?= $documento; ?></td>
          </tr>
        </table></td>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Nosso N&uacute;mero </td>
          </tr>
          <tr>
            <td><?= $nosso_numero; ?></td>
          </tr>
        </table></td>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Valor a pagar </td>
          </tr>
          <tr>
            <td align="right" class="campo_importante"><span class="campo">
              <? echo $dadosboleto["valor_boleto"]?>
            </span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Demonstrativo</td>
          </tr>
          <tr>
            <td><? echo $dadosboleto["demonstrativo1"]?><br>
  <? echo $dadosboleto["demonstrativo2"]?><br>
  <? echo $dadosboleto["demonstrativo3"]?><br>
  <? echo $dadosboleto["descricao"]?><br>
  <? echo $dadosboleto["taxa_pagamento"]?><br></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="right" class="campo_descricao">Autentica&ccedil;&atilde;o Mec&acirc;nica </td>
      </tr>
      <tr>
        <td height="40">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td style="border-bottom:1px #000000 dashed"><div align="right" class="campo_descricao">Corte na linha pontilhada</div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="640" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="140"><img src="boletos/imagens/logo_sicredi.jpg" width="106" height="31" /></td>
        <td width="100" align="center" valign="bottom"><span class="boleto_codigo">| 748-X |</span></td>
        <td width="400" align="right" valign="bottom" class="campo_importante"><? echo  $linha_digitada; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="640" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
      <tr>
        <td colspan="5" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Local de Pagamento </td>
          </tr>
          <tr>
            <td>PAG&Aacute;VEL PREFERENCIALMENTE NO SICREDI</td>
          </tr>
        </table></td>
        <td width="210" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Vencimento</td>
          </tr>
          <tr>
            <td align="right" class="campo_importante"><span class="campo"><? echo $dadosboleto["data_vencimento"]?></span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="5" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Cedente </td>
          </tr>
          <tr>
            <td><span class="campo"><? echo $dadosboleto["cedente"]; ?></span></td>
          </tr>
        </table></td>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">&Acirc;g&ecirc;ncia/Cod. Cedente </td>
          </tr>
          <tr>
            <td align="right"><? echo $dadosboleto["agencia"] . "." . $dadosboleto["posto_cedente"] . "." . RetiraDigito($dadosboleto["conta"]); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td width="100" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Data do Documento </td>
          </tr>
          <tr>
            <td><span class="campo"><? echo $dadosboleto["data_documento"]?></span></td>
          </tr>
        </table></td>
        <td width="90" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">N&ordm; do Documento </td>
          </tr>
          <tr>
            <td><?= $documento; ?></td>
          </tr>
        </table></td>
        <td width="80" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Esp&eacute;cie Doc. </td>
          </tr>
          <tr>
            <td align="center"><span class="campo">
              <?=$dadosboleto["especie_doc"]?>
            </span></td>
          </tr>
        </table></td>
        <td width="60" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Aceite</td>
          </tr>
          <tr>
            <td align="center"><span class="campo">
              <?=$dadosboleto["aceite"]?>
            </span></td>
          </tr>
        </table></td>
        <td width="100" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Data Processamento </td>
          </tr>
          <tr>
            <td><span class="campo"><? echo $dadosboleto["data_documento"]?></span></td>
          </tr>
        </table></td>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Nosso N&uacute;mero </td>
          </tr>
          <tr>
            <td align="right"><?= $nosso_numero; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Uso do Banco </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table></td>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Esp&eacute;cie Moeda </td>
          </tr>
          <tr>
            <td align="center"><span class="campo">
              <?=$dadosboleto["especie"]?>
            </span></td>
          </tr>
        </table></td>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Quantidade</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table></td>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">(x) Valor  </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table></td>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">(=) Valor do Documento </td>
          </tr>
          <tr>
            <td align="right"><span class="campo"><? echo $dadosboleto["valor_boleto"]?></span></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="5" rowspan="5" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Instru&ccedil;&otilde;es:</td>
          </tr>
          <tr>
            <td><? echo $dadosboleto["instrucoes1"]; ?><br>
<? echo $dadosboleto["instrucoes2"]; ?><br>
<? echo $dadosboleto["instrucoes3"]; ?><br>
<? echo $dadosboleto["instrucoes4"]; ?></td>
          </tr>
        </table></td>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">(-) Descontos/Abatimento </td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">(-) Outras Dedu&ccedil;&otilde;es </td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">(+) Mora/Multa </td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">(+) Outros Acr&eacute;scimos </td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">(=) Valor Cobrado </td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="6" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="campo_descricao">Sacado</td>
          </tr>
          <tr>
            <td><span class="campo">
              <?=$dadosboleto["sacado"]?>
            </span></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="10" class="campo_descricao">&nbsp;</td>
        <td width="500" height="60" valign="bottom"><?
// Criamos um array associativo com os binários
$Bar[0] = "00110";
$Bar[1] = "10001";
$Bar[2] = "01001";
$Bar[3] = "11000";
$Bar[4] = "00101";
$Bar[5] = "10100";
$Bar[6] = "01100";
$Bar[7] = "00011";
$Bar[8] = "10010";
$Bar[9] = "01010";

// Inicio padrão do Código de Barras
echo "<img src=boletos/imagens/ptfin.gif>";
echo "<img src=boletos/imagens/brfin.gif>";
echo "<img src=boletos/imagens/ptfin.gif>";
echo "<img src=boletos/imagens/brfin.gif>";

//Verifica impar
if(bcmod(strlen($codigo_barras),2) <> 0){
	$codigo_barras = '0'.$codigo_barras;
}

//Imprime
for($a = 0; $a < strlen($codigo_barras); $a++){
	$Preto = $codigo_barras[$a];
	$CodPreto = $Bar[$Preto];

	$a = $a + 1;
	
	$Branco = $codigo_barras[$a];
	$CodBranco = $Bar[$Branco];

	for($y = 0; $y < 5; $y++){
		if($CodPreto[$y] == '0'){
			echo "<img src=boletos/imagens/ptfin.gif>";
 		}else{
			echo "<img src=boletos/imagens/ptgr.gif>";
 		}

		if($CodBranco[$y] == '0'){
			echo "<img src=boletos/imagens/brfin.gif>";
		}else{
			echo "<img src=boletos/imagens/brgr.gif>";
		}
	}
}

// Final padrão do Codigo de Barras
echo "<img src=boletos/imagens/ptgr.gif  border=0>";
echo "<img src=boletos/imagens/brfin.gif border=0>";
echo "<img src=boletos/imagens/ptfin.gif border=0>";
?></td>
        <td width="130" align="right" valign="top" class="campo_descricao">FICHA DE COMPENSA&Ccedil;&Atilde;O <br />
          Autentica&ccedil;&atilde;o Mec&acirc;nica</td>
      </tr>
      
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>