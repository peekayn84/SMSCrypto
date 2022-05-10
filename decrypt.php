<?php
 function handleError($errorNumber, $errorString, $errorFile, $errorLine, array $errContext)
{
    // Path to log file
    $logFilePath = 'errors.log';

    // Do we want to prevent the default PHP error handler from executing?
    $allowDefaultLogging = true;

    // error was suppressed with the @-operator do not log and cancel the default error handler
    if (0 === error_reporting())
    {
        return false;
    }

    // Build log message string from error components
	$date = date('d-m-y h:i:s');
$message = '['.$date.']'.$errorFile . ':' . $errorLine . ' errno ' . $errorNumber . ' ' . $errorString . PHP_EOL;

    // Open the file for writing, append, create file if it does not exist
    $logHandle = fopen($logFilePath, 'a+');

    // Write the error message
    fwrite($logHandle, $message);

    // Close the file handle
    fclose($logHandle);

    // Tell PHP whether or not to handle the error with the default handler
    return $allowDefaultLogging;
}
set_error_handler('handleError');
include("bd.php");
$login=$_POST['login'];
$password=$_POST['password'];
if (!empty($_POST['password'])){
	$res = $mysqli->query("SELECT * FROM cryptomessage WHERE name='".$login."'");
	$res->data_seek(0);
	$myrow = $res->fetch_assoc();
	$textCrypt=$myrow['text'];
	//echo $textCrypt;
	$ciphering = "AES-128-CTR";
	$decryption_iv = '1234567891011121';
	$decryption_key = $password;
	$options = 0;
	$decryption=openssl_decrypt ($textCrypt, $ciphering, 
        $decryption_key, $options, $decryption_iv);
	//echo $decryption."<br>";
	if (substr($decryption, -6)=="|good|"){
		$decryption=substr($decryption, 0, strlen($decryption)-6);
		$out=$decryption;
		unlink("/var/www/html/cryptomessage/message/".$login.".php");
		$res = $mysqli->query("DELETE FROM cryptomessage WHERE name='".$login."'");
	}else{
		$out="Пароль не верный";
	}
}
//echo "qwe";
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
<form  action="<?php echo $login;?>.php" method="post">
<a style="margin-left: 50px;">Логин:<?php echo $login ?></a><br>
<a style="margin-left: 50px;">Введите пароль:</a><br>
<input name="password"  type="text" value="" /><br>
<input class="btn third" type="submit" value="Разшифровать" />
</form><br>
<a style="margin-left: 50px;"><?php echo $out; ?></a><br>


</div>

 </body>
</html>