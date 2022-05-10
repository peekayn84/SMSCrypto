<?php
$login=basename(__FILE__,'.php');
$password=$_POST['password'];
$sURL = 'http://lst-corp.ru/cryptomessage/decrypt.php'; // URL-адрес POST
$sPD = 'login='.$login; // Данные POST
if (!empty($_POST['password'])){
	$sPD=$sPD.'&password='.$password;
}
$aHTTP = array(
  'http' => // Обертка, которая будет использоваться
    array(
    'method'  => 'POST', // Request Method
    // Ниже задаются заголовки запроса
    'header'  => 'Content-type: application/x-www-form-urlencoded',
    'content' => $sPD
  )
);
$context = stream_context_create($aHTTP);
$handle = fopen($sURL, 'r', false, $context);
$contents = '';
while (!feof($handle)) {
  $contents .= fread($handle, 8192);
}
fclose($handle);
echo $contents;
?>