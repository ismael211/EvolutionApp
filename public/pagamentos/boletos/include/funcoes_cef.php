<?php
class boleto
{
 
        function banco_caixa(&$X43e0842f867){
 $X43e4ab10179 = "104";
$X43e92f52e6e = "9";
$X43e077effb5 = "0";
$X43e540e4d39 = $this->F540e4d39($X43e0842f867["data_vencimento"]);
$X43e01773a8a = $this->F6266027b($X43e0842f867["valor_boleto"],10,"0","v");
$X43e7c3c1e38 = $X43e0842f867["inicio_nosso_numero"];
$X43eef0ad7ba = $this->F6266027b($X43e0842f867["conta"],8,"0");
$X43e59a3ce9b = $this->F6266027b($X43e0842f867["cn_pj"],3,"0");
$X43e9f808afd = $this->F6266027b($X43e0842f867["agencia"],4,"0");
if ($X43e7c3c1e38 == "8"){
 $X43e0842f867["carteira"] = "SR";
$X43e5b3b7abe = $this->F6266027b($X43e0842f867["nosso_numero"],14,"0");
$X43e7dbac58a = $this->F6266027b($X43e0842f867["conta"],5,"0");
$X43e574f61ed = $X43e7dbac58a . $X43e9f808afd . $X43e7c3c1e38 . "7" . $X43e5b3b7abe;
$X43e5b3b7abe = "8" . $X43e5b3b7abe ;
$X43e1c90f9c3 = $this->F11efdac1($X43e5b3b7abe,9,0);
$X43e5b3b7abe = $X43e5b3b7abe . "-" . $X43e1c90f9c3;
$X43eef0ad7ba = "000" . $X43e7dbac58a ;
}else if ($X43e7c3c1e38 == "80" || $X43e7c3c1e38 == "81" || $X43e7c3c1e38 == "82" || $X43e7c3c1e38 == "00"){
 if($X43e7c3c1e38 == "00"){
 $X43e0842f867["carteira"] = "CS";
}else{
 $X43e0842f867["carteira"] = "SR";
}
$X43e5b3b7abe = $X43e7c3c1e38 . $this->F6266027b($X43e0842f867["nosso_numero"],8,"0");
$X43e574f61ed = $X43e5b3b7abe . $X43e9f808afd . $X43e59a3ce9b . $X43eef0ad7ba;
$X43e1c90f9c3 = $this->F11efdac1($X43e5b3b7abe,9,0);
$X43e5b3b7abe = $X43e5b3b7abe . "-" . $X43e1c90f9c3;
}else if ($X43e7c3c1e38 == "9"){
 $X43e0842f867["carteira"] = "CR";
$X43e5b3b7abe = $this->F6266027b($X43e0842f867["nosso_numero"],9,"0");
$X43e5b3b7abe = "9" . $X43e5b3b7abe ;
$X43e574f61ed = $X43e5b3b7abe . $X43e9f808afd . $X43e59a3ce9b . $X43eef0ad7ba;
$X43e1c90f9c3 = $this->F11efdac1($X43e5b3b7abe,9,0);
$X43e5b3b7abe = $X43e5b3b7abe . "-" . $X43e1c90f9c3;
}else if ($X43e7c3c1e38 == "99" || $X43e7c3c1e38 == "90" || $X43e7c3c1e38 == "01" || $X43e7c3c1e38 == "1"){
 $X43eef0ad7ba = $this->F6266027b($X43e0842f867["conta"],6,"0");
$X43e5b3b7abe = $this->F6266027b($X43e0842f867["nosso_numero"],16,"0");
if( $X43e7c3c1e38 == "90" || $X43e7c3c1e38 == "01" || $X43e7c3c1e38 == "1"){
 $X43e5b3b7abe = "90" . $X43e5b3b7abe;
$X43e0842f867["carteira"] = $X43e7c3c1e38;
}else{
 $X43e5b3b7abe = "99" . $X43e5b3b7abe;
$X43e0842f867["carteira"] = "01";
}        
 $X43e574f61ed = "1". $X43eef0ad7ba . $X43e5b3b7abe;
$X43e1c90f9c3 = $this->F11efdac1($X43e5b3b7abe,9,0);
$X43e5b3b7abe = $X43e5b3b7abe . "-" . $X43e1c90f9c3;
}
$X43ec21a9e1d = "$X43e4ab10179$X43e92f52e6e$X43e540e4d39$X43e01773a8a$X43e574f61ed";
$X43e28dfab58 = $this->F80457cf3($X43ec21a9e1d);
$X43ec21a9e1d = "$X43e4ab10179$X43e92f52e6e$X43e28dfab58$X43e540e4d39$X43e01773a8a$X43e574f61ed";
if($X43e7c3c1e38 == "99" || $X43e7c3c1e38 == "90" || $X43e7c3c1e38 == "01" || $X43e7c3c1e38 == "1"){
 $X43eaf2c4191 = $X43e9f808afd ."/". $X43eef0ad7ba ;
}else{
 $X43eaf2c4191 = $X43e9f808afd .".". $X43e59a3ce9b .".". $X43eef0ad7ba ."-". $X43e0842f867["dac_conta"];        
 }        
 $X43e0842f867["codigo_barras"] = "$X43ec21a9e1d";
$X43e0842f867["linha_digitavel"] = $this->F5aef63b6($X43ec21a9e1d);        
 $X43e0842f867["agencia_codigo"] = $X43eaf2c4191 ;
$X43e0842f867["nosso_numero"] = $X43e5b3b7abe;
}
 
 function F80457cf3($X43e0842f867){
 $X43e0842f867 = $this->F11efdac1($X43e0842f867);
if($X43e0842f867==0 || $X43e0842f867 >9) $X43e0842f867 = 1;
return $X43e0842f867;
}

 function F540e4d39($X43e0842f867){
 $X43e0842f867 = str_replace("/","-",$X43e0842f867);
$X43e465b1f70 = explode("-",$X43e0842f867);
return $this->F1b261b5c($X43e465b1f70[2], $X43e465b1f70[1], $X43e465b1f70[0]);
}

 function F1b261b5c($X43ebde9dee6, $X43ed2db8a61, $X43e465b1f70)
 {
 return(abs(($this->F5a66daf8("1997","10","07")) - ($this->F5a66daf8($X43ebde9dee6, $X43ed2db8a61, $X43e465b1f70))));
}
function F5a66daf8($X43e84cdc76c,$X43e7436f942,$X43e628b7db0)
 {
 $X43e151aa009 = substr($X43e84cdc76c, 0, 2);
$X43e84cdc76c = substr($X43e84cdc76c, 2, 2);
if ($X43e7436f942 > 2) {
 $X43e7436f942 -= 3;
} else {
 $X43e7436f942 += 9;
if ($X43e84cdc76c) {
 $X43e84cdc76c--;
} else {
 $X43e84cdc76c = 99;
$X43e151aa009 --;
}
}
return ( floor(( 146097 * $X43e151aa009) / 4 ) +
 floor(( 1461 * $X43e84cdc76c) / 4 ) +
 floor(( 153 * $X43e7436f942 + 2) / 5 ) +
 $X43e628b7db0 + 1721119);
}
function F11efdac1($X43e0fc3cfbc, $X43e593616de=9, $X43e4b43b0ae=0)
 {
 $X43e15a00ab3 = 0;
$X43e44f7e37e = 2;
 
 for ($X43e865c0c0b = strlen($X43e0fc3cfbc); $X43e865c0c0b > 0; $X43e865c0c0b--) {
 
 $X43e5e8b750e[$X43e865c0c0b] = substr($X43e0fc3cfbc,$X43e865c0c0b-1,1);
 
 $X43eb040904b[$X43e865c0c0b] = $X43e5e8b750e[$X43e865c0c0b] * $X43e44f7e37e;
 
 $X43e15a00ab3 += $X43eb040904b[$X43e865c0c0b];
if ($X43e44f7e37e == $X43e593616de) {
 
 $X43e44f7e37e = 1;
}
$X43e44f7e37e++;
}
 
 if ($X43e4b43b0ae == 0) {
 $X43e15a00ab3 *= 10;
$X43e05fbaf7e = $X43e15a00ab3 % 11;
if ($X43e05fbaf7e == 10) {
 $X43e05fbaf7e = 0;
}
return $X43e05fbaf7e;
} elseif ($X43e4b43b0ae == 1){
 $X43e9c6350b0 = $X43e15a00ab3 % 11;
return $X43e9c6350b0;
}
}
function Fd1ea9d43($X43e0fc3cfbc)
 { 
 $X43e4ec61c61 = 0;
$X43e44f7e37e = 2;
 
 for ($X43e865c0c0b = strlen($X43e0fc3cfbc); $X43e865c0c0b > 0; $X43e865c0c0b--) {
 
 $X43e5e8b750e[$X43e865c0c0b] = substr($X43e0fc3cfbc,$X43e865c0c0b-1,1);
 
 $X43eee487e79[$X43e865c0c0b] = $X43e5e8b750e[$X43e865c0c0b] * $X43e44f7e37e;
 
 $X43e4ec61c61 .= $X43eee487e79[$X43e865c0c0b];
if ($X43e44f7e37e == 2) {
 $X43e44f7e37e = 1;
} else {
 $X43e44f7e37e = 2; 
 }
}
$X43e15a00ab3 = 0;
 
 for ($X43e865c0c0b = strlen($X43e4ec61c61); $X43e865c0c0b > 0; $X43e865c0c0b--) {
 $X43e5e8b750e[$X43e865c0c0b] = substr($X43e4ec61c61,$X43e865c0c0b-1,1);
$X43e15a00ab3 += $X43e5e8b750e[$X43e865c0c0b]; 
 }
$X43e9c6350b0 = $X43e15a00ab3 % 10;
$X43e05fbaf7e = 10 - $X43e9c6350b0;
if ($X43e9c6350b0 == 0) {
 $X43e05fbaf7e = 0;
}
return $X43e05fbaf7e;
}
function F5aef63b6($X43e41ef8940)
 {
 
  
 $X43eec6ef230 = substr($X43e41ef8940, 0, 4);
$X43e1d665b9b = substr($X43e41ef8940, 19, 5);
$X43e7bc3ca68 = $this->Fd1ea9d43("$X43eec6ef230$X43e1d665b9b");
$X43e13207e3d = "$X43eec6ef230$X43e1d665b9b$X43e7bc3ca68";
$X43eed92eff8 = substr($X43e13207e3d, 0, 5);
$X43ec6c27fc9 = substr($X43e13207e3d, 5);
$X43e8a690a8f = "$X43eed92eff8.$X43ec6c27fc9";
  
 $X43eec6ef230 = substr($X43e41ef8940, 24, 10);
$X43e1d665b9b = $this->Fd1ea9d43($X43eec6ef230);
$X43e7bc3ca68 = "$X43eec6ef230$X43e1d665b9b";
$X43e13207e3d = substr($X43e7bc3ca68, 0, 5);
$X43eed92eff8 = substr($X43e7bc3ca68, 5);
$X43e4499f7f9 = "$X43e13207e3d.$X43eed92eff8";
  
 $X43eec6ef230 = substr($X43e41ef8940, 34, 10);
$X43e1d665b9b = $this->Fd1ea9d43($X43eec6ef230);
$X43e7bc3ca68 = "$X43eec6ef230$X43e1d665b9b";
$X43e13207e3d = substr($X43e7bc3ca68, 0, 5);
$X43eed92eff8 = substr($X43e7bc3ca68, 5);
$X43e9e911857 = "$X43e13207e3d.$X43eed92eff8";
 
 $X43e0db9137c = substr($X43e41ef8940, 4, 1);
   
 $X43ea7ad67b2 = substr($X43e41ef8940, 5, 14);
return "$X43e8a690a8f $X43e4499f7f9 $X43e9e911857 $X43e0db9137c $X43ea7ad67b2"; 
 }
function F294e91c9($X43e4d5128a0)
 {
 $X43ee2b64fe0 = substr($X43e4d5128a0, 0, 3);
$X43e284e2ffa = $this->F11efdac1($X43ee2b64fe0);

 return $X43ee2b64fe0 . "-" . $X43e284e2ffa;
}

 function F6266027b($X43e0842f867, $X43ece2db5d6, $X43e0152807c, $X43e401281b0 = "e"){
 if($X43e401281b0=="v"){
 $X43e0842f867 = str_replace(".","",$X43e0842f867); 
 $X43e0842f867 = str_replace(",",".",$X43e0842f867); 
 $X43e0842f867 = number_format($X43e0842f867,2,"","");
$X43e0842f867 = str_replace(".","",$X43e0842f867); 
 $X43e401281b0 = "e";
}
while(strlen($X43e0842f867)<$X43ece2db5d6){
 if($X43e401281b0=="e"){
 $X43e0842f867 = $X43e0152807c . $X43e0842f867;
}else{
 $X43e0842f867 = $X43e0842f867 . $X43e0152807c;
}
}
if(strlen($X43e0842f867)>$X43ece2db5d6){
 if($X43e401281b0 == "e"){
 $X43e0842f867 = $this->F8277e091($X43e0842f867,$X43ece2db5d6);
}else{
 $X43e0842f867 = $this->Fe1671797($X43e0842f867,$X43ece2db5d6);        
 }
}
return $X43e0842f867;        
 }
function Fe1671797($X43e0842f867,$X43e005480c8){
 return substr($X43e0842f867,0,$X43e005480c8);
}
function F8277e091($X43e0842f867,$X43e005480c8){
 return substr($X43e0842f867,strlen($X43e0842f867)-$X43e005480c8,$X43e005480c8);
}

 
}
 
function fbarcode($X43e01773a8a){
$X43e77e77c6a = 1 ;
$X43e5f44b105 = 3 ;
$X43e2c9890f4 = 50 ;
$X43ee5200a9e[0] = "00110" ;
$X43ee5200a9e[1] = "10001" ;
$X43ee5200a9e[2] = "01001" ;
$X43ee5200a9e[3] = "11000" ;
$X43ee5200a9e[4] = "00101" ;
$X43ee5200a9e[5] = "10100" ;
$X43ee5200a9e[6] = "01100" ;
$X43ee5200a9e[7] = "00011" ;
$X43ee5200a9e[8] = "10010" ;
$X43ee5200a9e[9] = "01010" ;
for($X43ebd19836d=9;$X43ebd19836d>=0;$X43ebd19836d--){ 
 for($X43e3667f6a0=9;$X43e3667f6a0>=0;$X43e3667f6a0--){ 
 $X43e8fa14cdd = ($X43ebd19836d * 10) + $X43e3667f6a0 ;
$X43e62059a74 = "" ;
for($X43e865c0c0b=1;$X43e865c0c0b<6;$X43e865c0c0b++){ 
 $X43e62059a74 .= substr($X43ee5200a9e[$X43ebd19836d],($X43e865c0c0b-1),1) . substr($X43ee5200a9e[$X43e3667f6a0],($X43e865c0c0b-1),1);
}
$X43ee5200a9e[$X43e8fa14cdd] = $X43e62059a74;
}
}
 
 
?><img src=boletos/imagens/p.gif width=<?=$X43e77e77c6a?> height=<?=$X43e2c9890f4?> border=0><img 
src=boletos/imagens/b.gif width=<?=$X43e77e77c6a?> height=<?=$X43e2c9890f4?> border=0><img 
src=boletos/imagens/p.gif width=<?=$X43e77e77c6a?> height=<?=$X43e2c9890f4?> border=0><img 
src=boletos/imagens/b.gif width=<?=$X43e77e77c6a?> height=<?=$X43e2c9890f4?> border=0><img 
<?php
$X43e62059a74 = $X43e01773a8a ;
if((strlen($X43e62059a74)%2) <> 0){
        $X43e62059a74 = "0" . $X43e62059a74;
}
 
while (strlen($X43e62059a74) > 0) {
 $X43e865c0c0b = round(Ff2317ae6($X43e62059a74,2));
$X43e62059a74 = F0835e508($X43e62059a74,strlen($X43e62059a74)-2);
$X43e8fa14cdd = $X43ee5200a9e[$X43e865c0c0b];
for($X43e865c0c0b=1;$X43e865c0c0b<11;$X43e865c0c0b+=2){
 if (substr($X43e8fa14cdd,($X43e865c0c0b-1),1) == "0") {
 $X43ebd19836d = $X43e77e77c6a ;
}else{
 $X43ebd19836d = $X43e5f44b105 ;
}
?>
 src=boletos/imagens/p.gif width=<?=$X43ebd19836d?> height=<?=$X43e2c9890f4?> border=0><img 
<?php
 if (substr($X43e8fa14cdd,$X43e865c0c0b,1) == "0") {
 $X43e3667f6a0 = $X43e77e77c6a ;
}else{
 $X43e3667f6a0 = $X43e5f44b105 ;
}
?>
 src=boletos/imagens/b.gif width=<?=$X43e3667f6a0?> height=<?=$X43e2c9890f4?> border=0><img 
<?php
 }
}
 
?>
src=boletos/imagens/p.gif width=<?=$X43e5f44b105?> height=<?=$X43e2c9890f4?> border=0><img 
src=boletos/imagens/b.gif width=<?=$X43e77e77c6a?> height=<?=$X43e2c9890f4?> border=0><img 
src=boletos/imagens/p.gif width=<?=1?> height=<?=$X43e2c9890f4?> border=0> <?php
} 
function Ff2317ae6($X43e0842f867,$X43e005480c8){
        return substr($X43e0842f867,0,$X43e005480c8);
}
function F0835e508($X43e0842f867,$X43e005480c8){
        return substr($X43e0842f867,strlen($X43e0842f867)-$X43e005480c8,$X43e005480c8);
}
?>
