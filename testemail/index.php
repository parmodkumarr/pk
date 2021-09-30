<?php
include_once 'class.verifyEmail.php';

//
$email = "pk131132@gmail.com"; //pass an email here to test
//

$vmail = new verifyEmail();
$vmail->setStreamTimeoutWait(20);
$vmail->Debug= TRUE;
$vmail->Debugoutput= 'html';

$vmail->setEmailFrom('info.cloudevils@yahoo.com');//email which is used to set from headers,you can add your own/company email over here

if ($vmail->check($email)) {
	echo '<h1>email &lt;' . $email . '&gt; exist!</h1>';
} elseif (verifyEmail::validate($email)) {
	echo '<h1>email &lt;' . $email . '&gt; valid, but not exist!</h1>';
} else {
	echo '<h1>email &lt;' . $email . '&gt; not valid and not exist!</h1>';
}
?>