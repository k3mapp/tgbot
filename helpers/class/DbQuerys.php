<?php
class DbQuerys {
    public static function addVoice($voice_url, $description) {
        return "INSERT INTO voices(voice_url, description) VALUES('{$voice_url}', '{$description}')";
    }

    public static function getVoice($description) {
        return "SELECT id, voice_url FROM voices WHERE MATCH(description) AGAINST('{$description}') LIMIT 1";
    }

    public static function addBanedUser($user_id, $username, $reason) {
        return "INSERT INTO banned_users(user_id, username, reason) VALUES($user_id, '{$username}', '$reason')";
    }

    public static function getBanedUsers($user_id) {
        return "SELECT id, user_id, username, reason FROM banned_users WHERE user_id = {$user_id}";
    }
} 