<?php
function sendmail($addr,$subj,$body,$name)
{
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	//$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= 'From: Whip <whip@kefcom.be>' . "\r\n";

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($body,70);

// send email
mail($addr,$subj,$msg, $headers);
}
?> 