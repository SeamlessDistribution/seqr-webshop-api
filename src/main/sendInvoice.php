<?php

header('Content-Type: application/json');

try {

    $data = json_decode(file_get_contents('php://input'), true);

    $wsdl = isset($data['wsdl']) ? $data['wsdl'] : 'https://extdev.seqr.com/extclientproxy/service/v2?wsdl';

    $soapClient = new SoapClient($wsdl);

    $terminalId = isset($data['terminalId']) ? $data['terminalId'] : '8609bf533abf4a20816e8bfe76639521';
    $password = isset($data['password']) ? $data['password'] : 'N2YFUhKaB1ZSuVF';

    $title = $data['title'];
    $currency = $data['currency'];

    $totalAmount = 0;
    $invoiceRows = array();

    foreach ($data['items'] as $item) {
        $totalAmount += $item['amount'];
        $invoiceRow =
            array(
                "itemDescription" => $item['description'],
                "itemTotalAmount" =>
                    array(
                        "currency" => $currency,
                        "value" => $item['amount']
                    )
            );
        array_push($invoiceRows, $invoiceRow);
    }

    $invoice = array(
        "acknowledgmentMode" => "NO_ACKNOWLEDGMENT",
        "title" => $title,
        "totalAmount" =>
            array(
                "currency" => $currency,
                "value" => $totalAmount
            ),
        "invoiceRows" => $invoiceRows
    );

    if (isset($data['backURL'])) {
        $invoice['backURL'] = $data['backURL'];
    }

    if (isset($data['notificationUrl'])) {
        $invoice['notificationUrl'] = $data['notificationUrl'];
    }

    $params = array(
        "context" => array(
            "initiatorPrincipalId" => array("id" => $terminalId, "type" => "TERMINALID"),
            "password" => $password,
            "clientRequestTimeout" => "0"
        ),
        "invoice" => $invoice
    );

    $result = $soapClient->sendInvoice($params);

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