<?php

header('Content-Type: application/json');

try {

    $data = json_decode(file_get_contents('php://input'), true);

    $wsdl = isset($data['wsdl']) ? $data['wsdl'] : 'http://extdev4.seqr.se/extclientproxy/service/v2?wsdl';

    $soapClient = new SoapClient($wsdl);

    $terminalId = isset($data['terminalId']) ? $data['terminalId'] : '58af556cc3794c66a32aadbb7cab0a2c';
    $password = isset($data['password']) ? $data['password'] : 'AnReH4NevKSXkcJ';

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