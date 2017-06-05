<?php
	require_once('main.php'); //include main.php

	$twitter_object = new main; //create an instance of main class to use its methods

	$twitter_object->check_connection(); //check if user is logged in, else takes him to sign in page

	$user = $twitter_object->get_user_data(); //get user info 

	$hashtags = $twitter_object->get_hashtag_tweets('#custserv'); //supply a hashtag that you want to search for

	$retweeted_once_tweets = $twitter_object->get_retweeted_once_tweets(); //get tweets which have been retweeted atleast once

	include('view/tweets_view.php'); //view the result
?>