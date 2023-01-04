<?php
namespace Whatsapp;
use GuzzleHttp\Client;

class Motassl {
    protected $payload;
    public function __construct(private $key,private $base_url = 'https://api.mottasl.com/v2/'){
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
        $options = [
            'headers'=>[
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'apikey' => $this->key
            ],
            'json' => $this->payload
        ];
        try {
            $req = $client->request('POST','message',$options);
            return $req->getBody()->getContents();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}