<?php
use PDP\Core\PDPUser;
use PDP\Core\PDPAuth;
use PDP\Core\PDPWhiteboard;
require __DIR__."/../PDP/autoload.php";
include 'layout/layout.php';
/**
 * First you'll need to register your key.
 */
$private_key = "abc";
$public_key = "JEpf1MnXqMqdEGeLYlyd";
PDPAuth::register($private_key, $public_key);

$whiteboard = new PDPWhiteboard();
//[optional] sets the whiteboard's type. 1 to 1(default) or 1 to many.
$whiteboard->setType(PDPWhiteboard::$TYPE_1_TO_1);
//creates the whiteboard and insert the token into the object
$response = $whiteboard->create();
//You need store the token to be used later
$token = $whiteboard->getToken();
/**
 * to retrieve a whiteboard just use the token
 */
$whiteboard_response = ( new PDPWhiteboard())->get($token);
if($whiteboard_response !== false){
    
}else{
    var_dump(PDPWhiteboard::lastResponse());
}
/**
 * You need add users in the whiteboard so they can have access to it.
 * You can do it passing the users' id in the method PDPWhiteboard->addUser($id)
 */
$whiteboard->addUser(10132);
//Them the method associate() make the resquest to store the users.
$whiteboard->associate();