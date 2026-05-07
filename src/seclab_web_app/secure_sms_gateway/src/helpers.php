<?php
require_once "Database.php";

function getUserFromToken($token) {
    $db = (new Database())->getConnection();
    $stmt = $db->prepare("SELECT * FROM sms_users WHERE api_token = :token");
    $stmt->execute(['token' => $token]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

