<?php

require_once('main.php'); //include main.php

$twitter_object = new main; //instance of main class

$twitter_object->callback(); //this gives access token from user, meaning that user has been verified to use this app

?>