<?php
namespace PDP\Core;
class PDPUser extends PDPAuth{
    private $id;
    private $name;
    private $photo;
    private $token;
    
    public function __construct($id = null, $name = null, $photo = null) {
        $this->id = $id;
        $this->name = $name;
        $this->photo = $photo;
    }
    /**
     * 
     * @return PDPJSON
     */
    public function create(){
        $ob = PDPRequest::convertJSON($this->getAuth()->run(PDPRequest::create($this, PDPRequest::$ACTION_CREAT)));
        if($ob->user){
            $this->fetchWithAnswer($ob->user);
        }
        return $ob;
    }
    
    public function extract(){
        return [
            "id_user"=>$this->id,
            "name"=>$this->name,
            "photo"=>$this->photo
        ];
    }

    public function fetchWithAnswer($json){
        if(!is_object($json)){
            $data = json_decode($json);
        }else{
            $data = $json;
        }
        $this->id = $data->id_user;
        $this->name = $data->name;
        $this->photo = $data->photo;
        $this->token = $data->token;
        return $this;
    }

    public static function get($user_id){
        $user = new PDPUser($user_id);
        $response = PDPRequest::convertJSON(self::$i->run(PDPRequest::create($user, PDPRequest::$ACTION_GET)));
        if(!$response->getStatus() && $response->getUser() !== false){
            return false;
        }
        $response_user_json = json_decode($response->getUser());
        if(!is_array($user_id)){
            return $user->fetchWithAnswer($response_user_json);
        }else{
            $group = [];
            foreach($response_user_json as $user){
                $group[] = (new PDPUser)->fetchWithAnswer($user);
            }
            return $group;
        }
    }
    
    public function getToken(){
        return $this->token;
    }
}