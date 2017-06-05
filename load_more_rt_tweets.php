<?php
	require_once('main.php');

	$twitter_object = new main;

	$twitter_object->check_connection();

	$load_more_rt_tweets = $twitter_object->get_retweeted_once_tweets();

	$mystring = '';

	foreach($load_more_rt_tweets->statuses as $key=>$tweet){ 

		$mystring .= '<div style="border-bottom:1px solid lightgrey;"><img src='.$tweet->user->profile_image_url.'><br><label style="color:#365899;">'.$tweet->user->name.'</label>'.$tweet->text.'<br><label style="font-size:15px;">Retweeted['.$tweet->retweet_count.']</label><strong><small>'.$tweet->created_at.'<br><br><br></small></strong></div>';
	}

	echo $mystring;
?>