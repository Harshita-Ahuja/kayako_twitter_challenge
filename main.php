<?php
	
session_start();

//importing twitter oauth class

require 'autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

Class main{

	//twitter app credentials

	public $consumer_key = 'rfAHzuANMnvMiC2jFWYb05hWF';
	public $consumer_secret = '3WD9p27hxBLhnCJPlad4lhBcOY45uIKnSmtMAFabKrcpxLBD9P';

	//this function will execute with every instance of main class

	public function __construct(){
		if ($this->consumer_key === '' || $this->consumer_secret === '') {
		  echo 'Please supply Consumer Key And Consumer Secret to use this application.';
		  exit;
		}		
	}

	//in case that user logs in for the first time then 'check_connection' will execute

	public function check_connection(){ 
		if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {

			//If access token is not in session that means user has not been verified to use this app, so redirects user to sign in page.

		    header('Location: ./signin.php');

		}

		//If user is a verified one to use this app then get access token from him and connect him to twitter api.

		$access_token = $_SESSION['access_token'];

		$twitter = new TwitterOAuth('rfAHzuANMnvMiC2jFWYb05hWF', '3WD9p27hxBLhnCJPlad4lhBcOY45uIKnSmtMAFabKrcpxLBD9P', $access_token['oauth_token'], $access_token['oauth_token_secret']);

	}

	public function get_user_data(){

		$access_token = $_SESSION['access_token']; //user is verified to use this app so get access token
		
		$twitter = new TwitterOAuth('rfAHzuANMnvMiC2jFWYb05hWF', '3WD9p27hxBLhnCJPlad4lhBcOY45uIKnSmtMAFabKrcpxLBD9P', $access_token['oauth_token'], $access_token['oauth_token_secret']); //connects to twitter api

		//store user information in a variable

		$user_data = $twitter->get('account/verify_credentials');	
		return $user_data;
	}

	public function connect(){
		$consumer_key = 'rfAHzuANMnvMiC2jFWYb05hWF';
		$consumer_secret = '3WD9p27hxBLhnCJPlad4lhBcOY45uIKnSmtMAFabKrcpxLBD9P';

		//connecting to twitter api

		$twitter = new TwitterOAuth($consumer_key, $consumer_secret); 

		// used to get oauth token that were received during twitter configuration.

		$request_token = $twitter->oauth("oauth/request_token", ['oauth_callback' => 'https://127.0.0.1/twitter_challenge/callback.php'] ); 
		//print_r($request_token);

		$_SESSION['oauth_token'] = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

		//Pass oauth token so that user can authenticate access from their twitter account to use this application

		$url = $twitter->url("oauth/authenticate", ["oauth_token" => $request_token['oauth_token']]);

		//After user authorizes this app from his twitter handle he is taken to callback url which we mentioned in the twitter api configuration

	    header('Location: ' . $url); 
		
	}

	public function callback(){
		if(isset($_REQUEST['oauth_token'],$_REQUEST['oauth_verifier']) && $_REQUEST['oauth_token'] == $_SESSION['oauth_token']){

			//If user has oauth token set in session, meaning that he has authorized to use this application from his twitter account

			$request_token['oauth_token'] = $_SESSION['oauth_token'];
			$request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];

			//Pass oauth token and connect to twitter api

			$twitter = new TwitterOAuth($consumer_key, $consumer_secret, $request_token['oauth_token'], $request_token['oauth_token_secret']); 

			//Get access token meaning that user is a verified user to use this application.

			$access_token = $twitter->oauth("oauth/access_token", ["oauth_verifier" =>$_REQUEST['oauth_verifier']]);

			//set this in session
			$_SESSION['access_token'] = $access_token;

			//go to index.php and then user can see tweets results
		  	header('Location: ./index.php');

		}else{

			//if oauth token is not set in session, redirects user back to sign in page or logs him out of this application.

			header('Location: ./logout.php');
		}
	}

	public function get_retweeted_once_tweets(){

		$access_token = $_SESSION['access_token']; //get access token for this application

		$twitter = new TwitterOAuth('rfAHzuANMnvMiC2jFWYb05hWF', '3WD9p27hxBLhnCJPlad4lhBcOY45uIKnSmtMAFabKrcpxLBD9P', $access_token['oauth_token'], $access_token['oauth_token_secret']); // connects to twitter api

		//This is a Query to get Tweets which have been retweeted atleast once, such tweets will have RT in front of them, hence we supply RT in query parameters, we fetch 10 such tweets and they are recent

		$tweets = $twitter->get('search/tweets',array("q" => "RT", "result_type" => "recent", "count" => 10));
		//print_r($tweets); 

		return $tweets;
	}

	public function get_hashtag_tweets($q){

		$access_token = $_SESSION['access_token']; //get access token for this application

		$twitter = new TwitterOAuth('rfAHzuANMnvMiC2jFWYb05hWF', '3WD9p27hxBLhnCJPlad4lhBcOY45uIKnSmtMAFabKrcpxLBD9P', $access_token['oauth_token'], $access_token['oauth_token_secret']); // connects to twitter api

		//This is a Hashtag Query which will find tweets containing any specific hashtag that you pass in index.php, adding a -RT will exclude tweets which are retweets of tweets that has the supplied hashtag. We fetch 10 such tweets and they are of mixed type, i.e. either popular or recent or none.

		$tweets = $twitter->get('search/tweets',array("q" => $q.' -RT', "result_type" => "mixed", "count" => 10)); 

		return $tweets;
	}

	public function logout(){

		//destroy access token to use this application
		session_destroy();

		//unset connection variable for twitter api
		unset($twitter);

		//redirects user back to sign in page
		header('Location: ./signin.php');		
	}
}

?>