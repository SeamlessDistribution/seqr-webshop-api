<?php

header('Content-Type: application/json');

$wsdl = isset($_REQUEST['wsdl']) ? $_REQUEST['wsdl'] : 'https://extdev.seqr.com/extclientproxy/service/v2?wsdl';

try {

    $soapClient = new SoapClient($wsdl);

    $terminalId = isset($_REQUEST['terminalId']) ? $_REQUEST['terminalId'] : 'tajzlz6xflfo6yq2i8aq62i3k11orpl1';
    $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : 'MgFQ1OO1dj2JTCS';

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
