<?php
namespace Whatsapp;
use GuzzleHttp\Client;

class Motassl {
    protected $payload;
    protected $media = false;
    protected $button = false;
    protected $template = false;
    private $key;
    private $base_url;
    public function __construct($key,$base_url = 'https://api.mottasl.com/v2/'){
        $this->key = $key;
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
        $this->template = true;
        $this->payload['type'] = 'template';
        $this->payload['templateId'] = $templateId;
        $this->payload['templateLanguage'] = $templateLanguage;
        return $this;
    }

    function hasButton(){
        $this->button = true;
        $this->payload['type'] = 'buttonTemplate';
        return $this;
    }

    function hasMedia(){
        $this->media = true;
        $this->payload['type'] = 'richTemplate';
        return $this;
    }

    function arguments($args) {
        if (!empty($args)) {
            $this->payload['templateArgs'] = $args;
        }
        return $this;
    }

    private function prepare() {
        if ($this->template) {
            $this->payload['type'] = 'template';
        }
        if ($this->media) {
            $this->payload['type'] = 'richTemplate';
        }
        if ($this->button) {
            $this->payload['type'] = 'buttonTemplate';
        }
    }
    function send() {
        $this->prepare();
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