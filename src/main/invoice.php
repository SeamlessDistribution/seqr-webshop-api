<?php

$soapClient = new SoapClient("http://seqrextdev4/extclientproxy/service/v2?wsdl");

$params = array("context" =>
    array(
        "initiatorPrincipalId" =>
        array(
            "id" => "58af556cc3794c66a32aadbb7cab0a2c",
            "type" => "TERMINALID"),
            "password" => "AnReH4NevKSXkcJ",
            "clientRequestTimeout" => "0"
    ),
    "invoiceReference" => $_GET["invoiceId"],
    "invoiceVersion" => 0
);

$result = $soapClient->getPaymentStatus($params);

//print_r($result);

$status = $result->return->status;
?>

{"status":"<?php echo $status; ?>"}