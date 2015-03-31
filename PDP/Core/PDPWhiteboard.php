<?php

namespace PDP\Core;
class PDPWhiteboard extends PDPAuth{
    private $token;
    private $users = [];
    
    private static $last_response = null;
    
    public function __construct($token = null) {
        $this->token = $token;
    }
    /**
     * Creates a new whiteboard
     * @return PDPJSON
     */
    public function create(){
        $ob = PDPRequest::convertJSON($this->getAuth()->run(PDPRequest::create($this, PDPRequest::$ACTION_CREAT)));
        if($ob->whiteboard){
            $this->fetchWithAnswer($ob->whiteboard);
        }
        return $ob;
    }
    
    /**
     * Convert the object data to array
     * @return array
     */
    public function extract(){
        return [
            "whiteboard_token"=>$this->token,
            "id_user"=>$this->users
        ];
    }
    /**
     * Add an user id so you can associate with the whiteboard
     * @param array|int|string $user_id
     */
    public function addUser($user_id){
        $this->users[] = $user_id;
    }
    /**
     * Associate the users added before with the whiteboard
     * @return PDPJSON
     */
    public function associate(){
        if(empty($this->users)){ 
            echo "You need to add users. Use \$whitboard->addUsers(\$id); and them run \$whiteboard->associate()";
            return false;
        }
        if($this->token == null){
            echo "You are missing the token, make sure you passed the token in the constructor.";
            return false;
        }
        $response = PDPRequest::convertJSON($this->getAuth()->run(PDPRequest::create($this, PDPRequest::$ACTION_ASSOCIATE)));
        return $response;
    }

    public function fetchWithAnswer($json){
        $data = json_decode($json);
        $this->token = $data->token;
        return $this;
    }
    /**
     * Get the token of the whiteboard
     * @return string
     */
    public function getToken(){
        return $this->token;
    }
    
    public static function get($token){
        $whiteboard = new PDPWhiteboard($token);
        $response = PDPRequest::convertJSON(self::$i->run(PDPRequest::create($whiteboard, PDPRequest::$ACTION_GET)));
        if(!$response->getStatus()){
            self::$last_response = $response;
            return false;
        }
        return $whiteboard->fetchWithAnswer($response->getWhiteboard());
    }
    /**
     * Retrives the last response.
     * @return PDPJSON
     */
    public static function lastResponse(){
        return self::$last_response;
    }
    
    /**
     * Creates a url so you can use in the iframe.
     * @param string $whiteboard_token
     * @param string $user_token
     * @return string
     */
    public static function url($whiteboard_token, $user_token){
        $public_key = self::$i->public_key;
        $array_url = [
            'public_key'=>$public_key,
            'user_token'=>$user_token,
            'whiteboard_token'=>$whiteboard_token
        ];
        return PDP_API_PATH."main?".http_build_query($array_url);
    }
    
}