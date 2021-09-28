<?php
//NinjaNetwork Firewall - V4R1ABLE - T13R
include("nnfconfig.php");
function sifrele($sifre){
$sifrele = md5($sifre);
$degis = str_replace("1", "z", $sifrele);
$degis2 = str_replace("2", "a", $degis);
$degis3 = str_replace("3", "o", $degis2);
$degis4 = str_replace("4", "m", $degis3);
$degis5 = str_replace("5", "f", $degis4);
$degis6 = str_replace("6", "n", $degis5);
$degis7 = str_replace("7", "z", $degis6);
$degis8 = str_replace("8", "q", $degis7);
$degis9 = str_replace("9", "l", $degis8);
$degis0 = str_replace("0", "e", $degis9);
return $degis0;
}

function cfban($ipaddr){
    $cfheaders = array(
        'Content-Type: application/json',
        'X-Auth-Email: '.$mailcf.'',
        'X-Auth-Key: '.$keycf.''
    );
	$data = array(
		'mode' => 'block',
		'configuration' => array('target' => 'ip', 'value' => $ipaddr),
		'notes' => 'Banned on '.date('Y-m-d H:i:s').' by NinjaNetwork'
	);
	$json = json_encode($data);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $cfheaders);
	curl_setopt($ch, CURLOPT_URL, 'https://api.cloudflare.com/client/v4/user/firewall/access_rules/rules');
	$return = curl_exec($ch);
	curl_close($ch);
	if ($return === false){
		return false;
	}else{
		$return = json_decode($return,true);
		if(isset($return['success']) && $return['success'] == true){
			return $return['result']['id'];
		}else{
			return false;
		}
	}
}

//UserAgent Kontrol
$useragent = $_SERVER["HTTP_USER_AGENT"];
if(empty($useragent)) {
exit;
}
$md5_useragent = sifrele($useragent);

//Ip
function GetIP(){
 if(getenv("HTTP_CLIENT_IP")) {
 $ip = getenv("HTTP_CLIENT_IP");
 } elseif(getenv("HTTP_X_FORWARDED_FOR")) {
 $ip = getenv("HTTP_X_FORWARDED_FOR");
 if (strstr($ip, ',')) {
 $tmp = explode (',', $ip);
 $ip = trim($tmp[0]);
 }
 } else {
 $ip = getenv("REMOTE_ADDR");
 }
 return $ip;
}
$ip_adresi = GetIP();
$md5_ip = sifrele($ip_adresi);

//Tarih
date_default_timezone_set('Europe/Istanbul');
$tarih = date('d.m.Y');
$md5_tarih = sifrele($tarih);
$tarih_saat = date('d.m.Y H');
$md5_tarih_saat = sifrele($tarih_saat);

//XML Kontrol
$getir = file_get_contents("nnfirewall/".$md5_tarih.".txt");
if(empty($getir)) {
$dosya = fopen('nnfirewall/'.$md5_tarih.'.txt', 'w+');
fwrite($dosya, '');
fclose($dosya);
}

//Arama Verileri
$tamveri = ("<".$md5_ip."_$md5_tarih_saat>");
$hamveri = ("</".$md5_ip."_$md5_tarih_saat>");


//Kişi Kontrol
$getir_xml = file_get_contents("nnfirewall/".$md5_tarih.".txt");
preg_match_all("@".$tamveri."(.*?)".$hamveri."@si",$getir_xml,$veri);
$verisi = $veri[1][0];
if($verisi>29) {
echo '<meta http-equiv="refresh" content="0;URL='.$mywebsiteurlx.'/limit.php">';
$getir_xml_f = file_get_contents("nnfirewall/".$md5_tarih."_f.txt");
preg_match_all("@".$tamveri."(.*?)".$hamveri."@si",$getir_xml_f,$veri_f);
$verisi_f = $veri_f[1][0];
if(empty($verisi_f)) {
$payl_f = $tamveri."1".$hamveri;
$dosyaf_f = fopen('nnfirewall/'.$md5_tarih.'_f.txt', 'a+');
fwrite($dosyaf_f, $payl_f);
fclose($dosyaf_f);
} else {
if($verisi_f>19) {
echo 'blocked';
cfban($ip_adresi);
exit;
} else {
$sil_f = $tamveri.$verisi_f.$hamveri;
$str_f = str_replace($sil_f, "", $getir_xml_f);
$ekle_f = $verisi_f+1;
$b1_f = $str_f.$tamveri.$ekle_f.$hamveri;
$dosya_gc_f = fopen('nnfirewall/'.$md5_tarih.'_f.txt', 'w+');
fwrite($dosya_gc_f, $b1_f);
fclose($dosya_gc_f);
}
exit;
}
}


if(empty($verisi)) {
$payl = $tamveri."1".$hamveri;
$dosyaf = fopen('nnfirewall/'.$md5_tarih.'.txt', 'a+');
fwrite($dosyaf, $payl);
fclose($dosyaf);
} else {

$sil = $tamveri.$verisi.$hamveri;
$str = str_replace($sil, "", $getir_xml);
$ekle = $verisi+1;
$b1 = $str.$tamveri.$ekle.$hamveri;
$dosya_gc = fopen('nnfirewall/'.$md5_tarih.'.txt', 'w+');
fwrite($dosya_gc, $b1);
fclose($dosya_gc);

}
?>
