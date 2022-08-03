<?php
class TelegramBot {
    private $apiKey;
    private $endPointUrl;

    public function __construct($apiKey)
    {
        $this->apiKey  = $apiKey;
        $this->endPointUrl = 'https://api.telegram.org/bot'.$this->apiKey;
    }

    public function getUpdate() {
        $result = [];
        $update = json_decode(file_get_contents('php://input'), TRUE);
        $result = [
            'chat' => [
                'id' => $update["message"]["chat"]["id"],
            ],
            'user' => [
                'id' => $update["message"]["from"]["id"],
                'username'  => $update["message"]["from"]["username"],
                'firstname' => $update["message"]["from"]["first_name"],
            ],
            'message' => [
                'id' => $update["message"]["message_id"],
                'text' => $update["message"]["text"],
            ],
            'callback' => [
                'id' => $update['callback_query']['from']['id'],
                'data' => $update['callback_query']['data'],
            ],
            'voice' => [
                'file_id'  => $update["message"]['voice']['file_id'],
                'duration' => $update["message"]['voice']['duration'],
                'file_size'=> $update["message"]['voice']['file_size'],
            ],
        ];
        return $result;
    }

    public function createAction($method, $params = []) {
        $data = http_build_query($params);
        return file_get_contents($this->endPointUrl."/{$method}?".$data);
    }

    function getCommand($message) {
        $parse = explode(' ', $message);
        return $parse[0];
    }

    function getCommandParameter($message, $numberOfParameters = 2) {
        $parse = explode(' ', $message, $numberOfParameters);
        return $parse[1];
    }

    // public function getCommandParameter($command, $text) {
    //     $pattern = '/^\/'.$command.'\b/';
    //     return (preg_match($pattern, $text)) ? trim(preg_replace($pattern, '', $text)) : '';
    // }

    public function sendMessage($message, $chat_id, $reply) {
        $get = $this->createAction('sendMessage', [
            'chat_id' => $chat_id,
            'text'    => $message,
            'reply_to_message_id' => $reply,
            'parse_mode' => 'HTML',
        ]);
        return $get = json_decode($get, true)['result'];
    }

    public function editMessage($chat_id, $message_id, $text) {
        $get = $this->createAction('editMessageText', [
            'chat_id'    => $chat_id,
            'message_id' => $message_id,
            'text'       => $text,
            'parse_mode' => 'HTML',
        ]);
        return $get = json_decode($get, true)['result'];
    }

    public function suicide($chat_id, $user_id) {
        $get = $this->createAction('banChatMember', [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
        ]);
        return $get = json_decode($get, true)['result'];
    }

    public function getUserRole($chat_id, $user_id) {
        $get = $this->createAction('getChatMember', [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
        ]);
        return $get = json_decode($get, true)['result']['status'];
    }

    public function sendVoice($chat_id, $voice_url, $reply) {
        $this->createAction('sendVoice', [
            'chat_id' => $chat_id,
            'voice' => $voice_url,
            'reply_to_message_id' => $reply,
            // 'voice' => [
            //     'file_id' => $voice_url
            // ]
        ]);
    }

    public function sendAudio($chat_id, $voice_url) {
        $this->createAction('sendAudio', [
            'chat_id' => $chat_id,
            'voice' => $voice_url
            // 'voice' => [
            //     'file_id' => $voice_url
            // ]
        ]);
    }
}
