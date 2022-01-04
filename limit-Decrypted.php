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

//Arama Verileri
$tamveri = ("<".$md5_ip."_$md5_tarih_saat>");
$hamveri = ("</".$md5_ip."_$md5_tarih_saat>");

$getir_xml = file_get_contents("nnfirewall/".$md5_tarih.".txt");

preg_match_all("@".$tamveri."(.*?)".$hamveri."@si",$getir_xml,$veri);
$verisi = $veri[1][0];

if($verisi>29) {
echo '';
} else {
echo '<meta http-equiv="refresh" content="0;URL='.$mywebsiteurlx.'/">';
exit;	
}

$getir_xml_f = file_get_contents("nnfirewall/".$md5_tarih."_f.txt");
preg_match_all("@".$tamveri."(.*?)".$hamveri."@si",$getir_xml_f,$veri_f);
$verisi_f = $veri_f[1][0];

if($verisi_f>19) {
echo 'blocked by ninjanetwork firewall.';
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>NinjaNetwork Firewall</title>

	<!-- Google font -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">

	<!-- Custom stlylesheet -->
	<link type="text/css" rel="stylesheet" href="css-f/style2.css" />
	
	<!-- Font Awesome -->
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	
	<script src="https://hcaptcha.com/1/api.js" async defer></script>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

</head>

<body>

	<div id="notfound">
		<div class="notfound">
			<div class="notfound-404">
				<img src="https://1.bp.blogspot.com/-DdJhpwGyffo/XojT0YHHxnI/AAAAAAAABAw/yi2JauLh1Sc13E8rDFULpW4siW5n5RqXQCLcBGAsYHQ/s1600/J31lly.png">
			</div><br><br>
			<h2>NNFirewall duvarına takıldınız captcha onayı ile geçiş sağlayınız.</h2>
<?php
if(isset($_POST['submit'])):
    if(isset($_POST['h-captcha-response']) && !empty($_POST['h-captcha-response'])):
        // your secret key
        $secret = ''.$hcaptchakeycodesecret.'';
        // get verify response
        $verifyResponse = file_get_contents('https://hcaptcha.com/siteverify?secret='.$secret.'&response='.$_POST['h-captcha-response'].'&remoteip='.$_SERVER['REMOTE_ADDR']);
        $responseData = json_decode($verifyResponse);
        
        if($responseData->success):

$sil = $tamveri.$verisi.$hamveri;
$str = str_replace($sil, "", $getir_xml);
$dosya_gc = fopen('nnfirewall/'.$md5_tarih.'.txt', 'w+');
fwrite($dosya_gc, $str);
fclose($dosya_gc);	

$sil2 = $tamveri.$verisi_f.$hamveri;
$str2 = str_replace($sil2, "", $getir_xml_f);
$dosya_gc2 = fopen('nnfirewall/'.$md5_tarih.'_f.txt', 'w+');
fwrite($dosya_gc2, $str2);
fclose($dosya_gc2);	
      
            $succMsg = '<br>Doğrulama başarılı yönlendiriliyorsunuz.<br><br><meta http-equiv="refresh" content="0;URL='.$mywebsiteurlx.'">';
      $name = '';
      $email = '';
      $message = '';
        else:
            $errMsg = '<br>hCaptcha doğrulaması yapılamadı tekrar deneyiniz.<br><br>';
        endif;
    else:
        $errMsg = '<br>hCaptcha doğrulamasını yapınız.<br><br>';
    endif;
else:
    $errMsg = '';
    $succMsg = '';
  $name = '';
  $email = '';
  $message = '';
endif;
?>
    <div>
        <?php if(!empty($errMsg)): ?><div class="errMsg"><?php echo $errMsg; ?></div><?php endif; ?>
        <?php if(!empty($succMsg)): ?><div class="succMsg"><?php echo $succMsg; ?></div><?php endif; ?>
    <div>
      <form action="" method="POST" class="notfound-search">
        <div class="h-captcha" data-sitekey="<?php echo $hcaptchakeycodepub; ?>"></div>
        <input type="submit" name="submit" value="Gönder">
      </form>
    </div>      
    <div class="clear"></div>
			<br>
		</div>
	</div>

</body>

</html>
