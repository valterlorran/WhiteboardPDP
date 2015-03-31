<?php
namespace PDP\Core;
define('PDP_API_PATH', "http://localhost/sitenovo/public/lousa/");
class PDPAuth{
    /**
     * Holds the app private key
     * @var string 
     */
    protected $key = null;
    protected $public_key = null;
    /**
     * 
     * @param string $private_key
     */
    public function __construct($private_key,$public_key) {
        $this->key = $private_key;
        $this->public_key = $public_key;
    }
    /**
     * Holds the PDPAuth's instance
     * @var PDPAuth 
     */
    public static $i;
    /**
     * 
     * @param string $private_key
     * @return PDPAuth
     */
    public static function register($private_key,$public_key){
        self::$i = new PDPAuth($private_key,$public_key);
        return self::$i;
    }
    
    protected function run(PDPRequest $request){
        return $request->pushRequest($this->key);
    }
    
    protected function getAuth(){
        return self::$i;
    }
}

class PDPRequest{
    public $action = null;
    public $data = null;
    public $instance = null;

    public static $ACTION_GET = 'read';
    public static $ACTION_CREAT = 'create';
    public static $ACTION_ASSOCIATE = 'associate';
    public function __construct($instance, $request_type){
        $this->instance = $instance;
        if(get_class($this->instance) != 'PDP\Core\PDPUser' && get_class($this->instance) != 'PDP\Core\PDPWhiteboard'){
            throw new \Exception("\$instance must be PDPUser or PDPWhiteboard");
        }
        $this->action = self::createRequestType($instance, $request_type);
        $this->data = $instance->extract();
    }
    
    private function createParams(array $data){
        return http_build_query($data);
    }
    
    public static function create($instance, $request_type){
        return new PDPRequest($instance, $request_type);
    }
    
    public function pushRequest($key){
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->buildUrl($key)
        ));
        $resp = curl_exec($curl);
        curl_close($curl);
        return $resp;
    }
    
    private function buildUrl($key){
        $this->data["key"] = $key;
        $params = $this->createParams($this->data);
        $base = PDP_API_PATH."api/{$this->action['action']}/{$this->action['class']}?$params";
        return $base;
    }
    
    public static function createRequestType($class, $action){
        $c = null;
        if(get_class($class) == 'PDP\Core\PDPUser'){
            $c = 'user';
        }
        if(get_class($class) == 'PDP\Core\PDPWhiteboard'){
            $c = 'whiteboard';
        }
        if(is_null($c)){
            throw new \Exception("\$class must be PDPUser or PDPWhiteboard");
        }
        
        return array(
            'class'=>$c,
            'action'=>$action
        );
    }
    
    public static function convertJSON($json){
        return new PDPJSON($json);
    }
}

class PDPJSON{
    public $status = false;
    public $messagem = false;
    public $message = false;
    public $user = false;
    public $whiteboard = false;
    public function __construct($json) {
        $decode = json_decode($json);
        $this->status = $decode->status;
        $this->messagem = isset($decode->message) ? $decode->message : $this->messagem;
        $this->user = isset($decode->user) ? $decode->user : $this->user;
        $this->whiteboard = isset($decode->whiteboard) ? $decode->whiteboard : $this->whiteboard;
        $this->message = $this->messagem;
    }
    
    public function getStatus(){
        return $this->status;
    }
    
    public function getMessagem(){
        return $this->messagem;
    }
    
    public function getUser(){
        return $this->user;
    }
    
    public function getWhiteboard(){
        return $this->whiteboard;
    }
}

