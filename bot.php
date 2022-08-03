<?php
require_once './helpers/functions.php';
$botToken = "1909991963:AAEaNM1eQ9TMPwvCkFGDg6xasa81eWl9OIU"; // Enter ur bot token
$website = "https://api.telegram.org/bot".$botToken;
$update = json_decode(file_get_contents('php://input'), TRUE);
$chatId = $update["message"]["chat"]["id"];
$gId = $update["message"]["from"]["id"];
$userId = $update["message"]["from"]["id"];
$firstname = $update["message"]["from"]["first_name"];
$username = $update["message"]["from"]["username"];
$message = $update["message"]["text"];
$message_id = $update["message"]["message_id"];
$callbakId = $update['callback_query']['from']['id'];



// Check for normal command
if ((preg_match('/\/start|!start/', $message))){
    // Create keyboard
    $data = http_build_query([
        'text' => 'Por favor seleccione una opcion :',
        'chat_id' => $update['message']['from']['id']
    ]);
    $keyboard = json_encode([
        "inline_keyboard" => [
            [textButton("COMPRAR DNI", "buy_dni")],
            [textButton("RECARGAR BALANCE", "recharge_balance")],
            [textButton("CHECKEAR IBAN", "check_iban")],
            [textButton("CHECKEAR BALANCE", "check_balance")],
            [textButton("PROMOCIONES", "promotions")],
            [urlButton("SOPORTE", "https://t.me/davidluna27")],
        ]
    ]);
    // Send keyboard
    file_get_contents($website . "/sendMessage?{$data}&reply_markup={$keyboard}");
}
elseif ((preg_match('/\/iban|!iban/', $message))) {
	$read_iban = getComand($message);
	$iban_info = ibanInfo($iban);

	if ($iban_info['valid'] == "This is a valid IBAN.") {
		$msg  = "✅ VALID : ".$read_iban."%0A%0A";
        $msg .= $iban_info['bank'];
		sendMsg($chatId, $msg);
    }else {
		$msg  = "❌ INVALID IBAN: ".$read_iban."";
		sendMsg($chatId, $msg);
	}
}
else {
    sendMsg($chatId, "Comando invalido");
}


// Check if callback is set
if (isset($update['callback_query'])) {
    //Reply with callback_query data
    if ($update['callback_query']['data'] == 'english') {
        sendMsg2($callbakId, 'Selected language: ' . $update['callback_query']['data']);
    }
    elseif ($update['callback_query']['data'] == "check_iban") {
        sendMsg2($callbakId, "Para checkear un IBAN use el comando /iban seguido del iban");
    }
}
