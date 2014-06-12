<?php

/*
 * Returns last tweets, number of tweets, following & followers for a specified twitter user
 * author: Aleck Kulabukhov
 * date: 13-Jun-2014
 * version: 1.0.0
 *
 * Notes:
 * Tested manually, but we somewhat rely on Twitter API to return legit values.
 * Stricter checks may be required to be implemented depending on the application purpose
 */

// External library with API 1.1 support
require_once("twitteroauth.php");

$twitteruser = "Brazil";
define( "constTwitsNumber", 5 );
define( "constConsumerKey", "kOyAQIOkgDo6he74uVhU27quN" );
define( "constConsumerSecret", "wPc77nIzdRgBuaUsEbgKKaVYma9JDU9ssYRdHzRduBPff86O2Y" );
define( "constAccessToken", "109957816-n2WanOUy9CFGB6hfbWim7TjJOaUvAtAA1HFVzpBv" );
define( "constAccessTokenSecret", "MKkPbfUeCAk6Tpgsizb4M0fQr9C2Iyeq2a0iaD8F9V4Dj" );

function finalEcho( $msg ) {
    echo $msg;
    exit;
}

$strUsage = "The program takes a Twitter username as an input.\n";
$strFailed2Connect = "Fatal: Twitter connection failure, probably access keys should be updated.\n";
if ($argc === 2) {
    $objConnection = new TwitterOAuth( constConsumerKey, constConsumerSecret, constAccessToken, constAccessTokenSecret );
    $objConnection->token !== NULL or finalEcho( $strFailed2Connect );
    $strTwitterUsername = addslashes( strip_tags( $argv[1] ) ); // sanitize the input
    $strTwitterUsername !== '' or finalEcho( $strUsage );
    $arLastTweets = $objConnection->get( "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=" . $strTwitterUsername . "&count=" . constTwitsNumber );
    $arLastTweet = (array) ($arLastTweets[0]);
    $arUserInfo = (array) ($arLastTweet["user"]);
    $intFollowersCount = $arUserInfo["followers_count"];
    $intFollowingCount = $arUserInfo["friends_count"];
    $intTweetsCount = $arUserInfo["statuses_count"];
    array_walk( $arLastTweets, function(&$objTweet) {
        $arTweet = (array) $objTweet;
        $objTweet = $arTweet["text"];
    } );
    printf( "User: %s\nTweets: %d\nFollowers: %d\nFollowing: %d\nLast %d tweets:\n", $strTwitterUsername, $intTweetsCount, $intFollowersCount,
            $intFollowingCount, constTwitsNumber );
    echo join( "\n", $arLastTweets );
} else
    echo $strUsage;
