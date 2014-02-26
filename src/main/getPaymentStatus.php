<?php

header('Content-Type: application/json');

$wsdl = isset($_REQUEST['wsdl']) ? $_REQUEST['wsdl'] : 'http://extdev4.seqr.se/extclientproxy/service/v2?wsdl';

try {

    $soapClient = new SoapClient($wsdl);

    $terminalId = isset($_REQUEST['terminalId']) ? $_REQUEST['terminalId'] : '58af556cc3794c66a32aadbb7cab0a2c';
    $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : 'AnReH4NevKSXkcJ';

    $params = array(
        "context" => array(
            "initiatorPrincipalId" => array("id" => $terminalId, "type" => "TERMINALID"),
            "password" => $password,
            "clientRequestTimeout" => "0"
        ),
        "invoiceReference" => $_REQUEST["invoiceReference"],
        "invoiceVersion" => 0
    );

    $result = $soapClient->getPaymentStatus($params);

    echo json_encode($result->return);

} catch (Exception $exception) {

    $result = array(
        "resultCode" => 999,
        "resultDescription" => "EXCEPTION",
        "version" => 0,
        "exception" => $exception
    );

    echo json_encode($result);

}

?>