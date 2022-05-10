<?php
include("bd.php");
$login=$_POST['login'];
$password=$_POST['password'];
$text=$_POST['text'];

$output="";
if (!empty($_POST['login'])){
	$res = $mysqli->query("SELECT * FROM cryptomessage WHERE name='".$login."'");
	$res->data_seek(0);
	$myrow = $res->fetch_assoc();
	if ($myrow['name']==null){
		echo $text."<br>";
		$text=$text."|good|";
		echo $text."<br>";
		$simple_string = $text;
		$ciphering = "AES-128-CTR";
		$iv_length = openssl_cipher_iv_length($ciphering);
		$options = 0;
		$encryption_iv = '1234567891011121';
		$encryption_key = $password;
		$encryption = openssl_encrypt($simple_string, $ciphering,
        $encryption_key, $options, $encryption_iv);
		$res = $mysqli->query("INSERT INTO cryptomessage (name,text) VALUES ('".$login."', '".$encryption."')");
		$output="<a href='http://lst-corp.ru/cryptomessage/message/".$login.".php'>Ваша ссылка згенерированна.</a>";
		$file = 'message/'.$login.'.php';
		$current = file_get_contents("test.php");
		file_put_contents($file, $current);

	}else{
		$output="Данная ссылка уже существует";
	}
}


//echo "Encrypted String: " . $encryption . "\n";
?>

<!DOCTYPE HTML>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Crypto Message</title>
  <link href="external.css" rel="stylesheet">
 </head>
 <body>

<div class="navigation">
<form  action="crypt.php" method="post">
<a style="margin-left: 50px;">Введите имя ссылки:</a><br>
<input name="login"  type="text" value="" /><br>
<a style="margin-left: 50px;">Введите пароль:</a><br>
<input name="password"  type="text" value="" /><br>
<a style="margin-left: 50px;">Текст:</a><br>
<input name="text"  type="text" value="" /><br>
<input class="btn third" type="submit" value="Зашифровать" />
</form><br>
<a style="margin-left: 50px;"><?php echo $output; ?></a><br>


</div>

 </body>
</html>
