<?php

header('Content-Type: application/json');

$wsdl = isset($_REQUEST['wsdl']) ? $_REQUEST['wsdl'] : 'https://extdev.seqr.com/extclientproxy/service/v2?wsdl';

try {

    $soapClient = new SoapClient($wsdl);

    $terminalId = isset($_REQUEST['terminalId']) ? $_REQUEST['terminalId'] : '8609bf533abf4a20816e8bfe76639521';
    $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : 'N2YFUhKaB1ZSuVF';

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