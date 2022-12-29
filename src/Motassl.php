<?php
namespace Omarius;
use GuzzleHttp\Client;
require_once __DIR__.'../vendor/autoload.php';
class Motassl {
    protected $base_url = 'https://api.mottasl.com/v2/';
    protected $instance;
    protected $payload;
    public function __construct(private $key){
        $this->instance = $this;
        $this->payload = [
            'channel' => 'whatsapp',
        ];
    }

    static function auth($key) {
        return new Motassl($key);
    }
    function to($recipient) {
        $this->payload['recipient'] = $recipient;
        return $this;
    }
    function text($message){
        $this->payload['type'] = 'text';
        $this->payload['message'] = $message;
        return $this;
    }

    function template($templateId, $templateLanguage,$args=[]){
        $this->payload['type'] = 'template';
        $this->payload['templateId'] = $templateId;
        $this->payload['templateLanguage'] = $templateLanguage;
        if (!empty($args)) {
            $this->payload = array_merge($this->payload, $args);
        }
        return $this;
    }

    function send() {
        $client = new Client(['base_uri'=>$this->base_url]);
        $req = $client->request('POST','message',[
            'headers'=>[
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'apikey' => $this->key
            ],
            'json' => $this->payload
        ]);
        return $req->getBody()->getContents();
    }


}