<?php
namespace Whatsapp;

require_once __DIR__.'/CurlHelper.php';
use Mervick\CurlHelper;

class Motassl {
    protected $payload;
    protected $media = false;
    protected $button = false;
    protected $template = false;

    private $key;
    private $base_url;
    public function __construct($key,$base_url = 'https://api.mottasl.com/v2/'){
        $this->key = $key;
        $this->base_url = $base_url;
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
    function url ($path) {
        return $this->base_url.$path;
    }

    function getHeaders(){
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'apikey' => $this->key
        ];
    }

    function send() {
        $this->prepare();
        /*
        * For CurlHelper Documentation: https://github.com/mervick/curl-helper
         */
        $client = CurlHelper::factory($this->url('message'));
        $client->setHeaders($this->getHeaders())->setPostFields($this->payload);
        try {
            $response = $client->exec();
            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}