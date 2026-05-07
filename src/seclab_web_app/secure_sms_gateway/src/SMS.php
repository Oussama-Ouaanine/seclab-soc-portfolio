<?php
require_once "Logic.php";
require_once "Encryption.php";

class SMS {
    public $user_id;
    public $sender;
    public $label;
    public $destination;
    public $numero;
    public $indicative;
    public $message_encrypted;
    public $format = 'text';
    public $priority;
    public $status = 'queued';
    public $received_at;
    public $sent_at = null;
    public $delivered_at = null;

    public function __construct($data, $userId) {
        $this->user_id = $userId;
        $this->sender = $data['name'];
        $this->label = $data['label'] ?? null;
        $this->destination = $data['destination'];
        $this->priority = determine_priority($data['message']);
        $this->received_at = date('Y-m-d H:i:s');

        $encryptor = new Encryptor();
        $this->message_encrypted = $encryptor->encrypt($data['message']);
        $this->format = ctype_print($data['message']) ? 'text' : 'unicode';
        $this->indicative = $this->extractIndicative($this->destination);
        $this->numero = $this->extractNumero($this->destination);
    }

    private function extractIndicative($destination) {
        if (preg_match('/^\\+?(\\d{1,6})/', $destination, $matches)) {
            return $matches[1];
        }
        return null;
    }

    private function extractNumero($destination) {
        return preg_replace('/^\\+?\\d{1,6}/', '', $destination);
    }
}

