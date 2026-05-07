<?php
require_once "../src/Database.php";
require_once "../src/Encryption.php";
require_once "../src/Logic.php";
require_once "../src/SMS.php";
require_once "../src/helpers.php";

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$token = $data['api_token'] ?? null;
$messageBody = $data['message_body'] ?? null;

if (!$token || !$messageBody) {
    http_response_code(400);
    echo json_encode(["error" => "Missing api_token or message_body"]);
    exit;
}

$user = getUserFromToken($token);
if (!$user) {
    http_response_code(403);
    echo json_encode(["error" => "Invalid API token"]);
    exit;
}

$parsed = parse_sms_text($messageBody);
$required = ["name", "destination", "message"];
$missing = array_diff($required, array_keys($parsed));
if (!empty($missing)) {
    http_response_code(400);
    echo json_encode(["error" => "Missing fields", "fields" => $missing]);
    exit;
}

$sms = new SMS($parsed, $user['id']);
$db = (new Database())->getConnection();
$sql = "INSERT INTO sms_messages (user_id, sender, label, destination, numero, indicative, 
        message_encrypted, format, priority, status, received_at)
        VALUES (:user_id, :sender, :label, :destination, :numero, :indicative, 
        :message_encrypted, :format, :priority, :status, :received_at)";
$stmt = $db->prepare($sql);

try {
    $stmt->execute([
        ":user_id" => $sms->user_id,
        ":sender" => $sms->sender,
        ":label" => $sms->label,
        ":destination" => $sms->destination,
        ":numero" => $sms->numero,
        ":indicative" => $sms->indicative,
        ":message_encrypted" => $sms->message_encrypted,
        ":format" => $sms->format,
        ":priority" => $sms->priority,
        ":status" => $sms->status,
        ":received_at" => $sms->received_at
    ]);

    echo json_encode([
        "status" => "received",
        "sms_id" => $db->lastInsertId(),
        "from" => $sms->sender,
        "to" => $sms->destination,
        "priority" => $sms->priority,
        "received_at" => $sms->received_at
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error"]);
}

