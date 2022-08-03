<?php
require_once './config.php';
require_once 'autoload.php';

$telegram = new TelegramBot(BOT_API_KEY);
$database = new db(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$d = $database->fetch(DbQuerys::getVoices('chop chop'))['voice_url'];
echo $d;
die;
try {
    $updates = $telegram->getUpdate();
    $message = $updates['message']['text'];
    switch ($message) {
        case '/start':
            $telegram->sendMessage('hola', $updates['chat']['id'], $updates['message']['id']);
            break;
        case '/suicide':
            if ($telegram->getUserRole($updates['chat']['id'], $updates['user']['id']) == 'member') {
                $telegram->suicide($updates['chat']['id'], $updates['user']['id']);
                $telegram->sendMessage("{$updates['user']['username']} [{$updates['user']['id']}] se ha suicidado, Allau Akbar 💣🔥", $updates['chat']['id'], $updates['message']['id']);
            } else {
                $telegram->sendMessage("El usuario no se puede suicidar porque es parte del personal del grupo.", $updates['chat']['id'], $updates['message']['id']);
            }
            break;
        case '/role':
            $role = $telegram->getUserRole($updates['chat']['id'], $updates['user']['id']);
            $telegram->sendMessage($role, $updates['chat']['id'], $updates['message']['id']);
            break;
        case '/vinci':
            $telegram->sendVoice($updates['chat']['id'], 'AwACAgQAAxkBAAPVYt_l5SW8xVoRRRNb5aTwPj8iq30AAmcMAAJkBmFTfJCPb62f20opBA', $updates['message']['id']);
            break;
        default:
            if ($telegram->getCommand($message) == '/voice') {
                $parameter = $telegram->getCommandParameter($message);
                if (!empty($parameter)) {
                    $query = $database->fetch(DbQuerys::getVoices($parameter))['voice_url'];
                    if (!empty($query)) {
                        $telegram->sendVoice($updates['chat']['id'], $query, $updates['message']['id']);
                    }
                    //$telegram->sendMessage("comando capturado con exito {$message}", $updates['chat']['id'], $updates['message']['id']);
                } else {
                    $telegram->sendMessage("Command ERROR ⚠️", $updates['chat']['id'], $updates['message']['id']);
                }
            } elseif (!empty($updates['voice'])) {
                $telegram->sendMessage($updates['voice']['file_id'], $updates['chat']['id'], $updates['message']['id']);
            }
            break;
    }
} catch (Exception $e) {
    echo 'Houston tenemos un problema: '.$e;
}