<?php
session_start();
require_once('config.inc.php');
require_once('common.inc.php');
require_once('TwitterOAuth/TwitterOAuth.php');

# Build TwitterOAuth object and get temporary credentials
$oauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
$request_token = $oauth->getRequestToken(OAUTH_CALLBACK_URL);
if (!$request_token || $oauth->http_code != 200) {
    add_message("There was an unexpected OAuth error while attempting to login with Twitter.");
    header("Location: ./");
    exit;
}
# Save temporary credentials to session
$_SESSION['twitter']['temp_oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['twitter']['temp_oauth_token_secret'] = $request_token['oauth_token_secret'];
# Build authorize URL and redirect user to Twitter
$url = $oauth->getAuthorizeURL($token);
header("Location: $url");
