<?php
use WhiteboardPDP\PDPUser;
use WhiteboardPDP\PDPAuth;
use WhiteboardPDP\PDPWhiteboard;
require __DIR__."/../autoload.php";
include 'layout/layout.php';
/**
 * First you'll need to register your key.
 */
$private_key = "abc";
$public_key = "JEpf1MnXqMqdEGeLYlyd";
PDPAuth::register($private_key, $public_key);

$user = PDPUser::get(111119);