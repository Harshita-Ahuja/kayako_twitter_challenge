<?php
	require_once('main.php');

	$twitter_object = new main;

	$twitter_object->check_connection();

	$more_hashtags = $twitter_object->get_hashtag_tweets('#custserv');

	$mystring = '';

	foreach($more_hashtags->statuses as $key=>$tweet){ 

		$mystring .= '<div style="border-bottom:1px solid lightgrey;"><img src='.$tweet->user->profile_image_url.'><br><label style="color:#365899;">'.$tweet->user->name.'</label>'.$tweet->text.'<br><strong><small>'.$tweet->created_at.'<br><br></small></strong></div>';
	}

	echo $mystring;
?>