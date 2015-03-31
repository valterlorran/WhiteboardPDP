<?php
use PDP\Core\PDPUser;
use PDP\Core\PDPAuth;
use PDP\Core\PDPWhiteboard;
require __DIR__."/../PDP/autoload.php";
include 'layout/layout.php';
/**
 * First you'll need to register your key.
 */
$private_key = "MY_PRIVATE_KEY";
$public_key = "MY_PUBLIC_KEY";
$private_key = "abc";
$public_key = "JEpf1MnXqMqdEGeLYlyd";
PDPAuth::register($private_key, $public_key);
//============================================================================//
//USERS
//============================================================================//
/**
 * Here we'll create an user
 * First instantate the class PDPUser passing the user's id(the identification
 * that you use), name and photo(optional).
 */
$user = new PDPUser(10, "Valter L.", NULL);
//Now user the method create() to create a new user.
$response = $user->create();
/**
 * You'll get response with a status(BOOLEAN) that will tell you if the 
 * transaction was completed successfully or not.
 * If an error happen you'll receive the message(array of strings) parameter. 
 * That tells what happend.
 */
if(!$response->status){
    //Some error happend
    echo join('<br>', $response->message);
}else{
    echo "User created successfully!<br>";
}
/**
 * To get a user you can use the static method PDPUser::get($id).
 * If you want get more than one user you can pass an array of ids.
 */
//Retrieve a single user.
$user = PDPUser::get(10);
//Retrieve more than one user. This will return a array of PDPUser
$users = PDPUser::get([10,11]);
//You should store the users token
$user_token = $user->getToken();

//============================================================================//
//WHITEBOARD
//============================================================================//
/**
 * To create a new user you just need to instantiate PDPWhiteboard and call the 
 * method create.
 * Here some examples how to do it.
 */
$whiteboard = new PDPWhiteboard();
//creates the whiteboard and insert the token into the object
$response = $whiteboard->create();
//You need store the token to be used later
$token = $whiteboard->getToken();
/**
 * to retrieve a whiteboard just use the token
 */
$whiteboard_response = ( new PDPWhiteboard())->get("AOhhCTGGIL4PpNxzPQKe0xscD0t48aw5AQzx9qUT");
if($whiteboard_response !== false){
    
}else{
    var_dump(PDPWhiteboard::lastResponse());
}
/**
 * You need add users in the whiteboard so they can have access to it.
 * You can do it passing the users' id in the method PDPWhiteboard->addUser($id)
 */
$whiteboard->addUser(10);
$whiteboard->addUser(11);
//Them the method associate() make the resquest to store the users.
$whiteboard->associate();
/**
 * To display a whiteboard you need to create an iframe and set the url using the
 * PDPWhiteboard::url($whiteboard_token, $current_user_token)
 */
$whiteboard_token = "AOhhCTGGIL4PpNxzPQKe0xscD0t48aw5AQzx9qUT";
$current_user_token = $user_token;
$url = PDPWhiteboard::url($whiteboard_token, $current_user_token);
echo '<iframe src="'.$url.'"></iframe>';
$layout->print_layout();

