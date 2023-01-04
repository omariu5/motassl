<?php
include_once 'Motassl.php';

$key = "Your API Key";
$motassl = Whatsapp\Motassl::auth($key);

var_dump($templateMessage = $motassl
    ->to('0123456789')
    ->template('test_template','ar')
    ->send());

// success example {"status":"202","statusText":"Accepted","messageId":"40e9e970-8723-11ed-b52f-61bea33aeb0f","customerId":"df6d2cac-7f9d-11ed-9b51-42010a020902"}
// error example {"status":409,"type":"client_error","message":"New customer - please use template message for first contact.","timestamp (truncated...)"}

//$textMessage = $motassl
//    ->text('This is a test text message example')
//    ->to('0123456789')
//    ->send();

