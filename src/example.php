<?php
include_once 'Motassl.php';

$key = "Your API Key";
$motassl = Whatsapp\Motassl::auth($key);
$templateMessage = $motassl
    ->to('+9660000000')
//    ->hasMedia() // required if the template has any media (image,video,document). the link of the media should be provided as the first argument in the arguments array.
    ->hasButton()  // required if the template has any Buttons or CTA actions.
    ->template('test_template','ar') // the identifying name and language of the template.
//    ->arguments(['Ahmed','9000','SAR']) // optional: array of the template arguments. if any.
    ->send(); // send request to mottasl
//$textMessage = $motassl
//    ->text('This is a test text message example')
//    ->to('+9660000000')
//    ->send();
var_dump($templateMessage);

//success example {"status":"202","statusText":"Accepted","messageId":"40e9e970-8723-11ed-b52f-61bea33aeb0f","customerId":"df6d2cac-7f9d-11ed-9b51-42010a020902"}
//{"status":409,"type":"client_error","message":"New customer - please use template message for first contact.","timestamp (truncated...)"}



