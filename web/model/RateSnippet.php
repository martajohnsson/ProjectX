<?php
require_once dirname(__FILE__) . '/API.php';
require_once dirname(__FILE__) . '/AuthHandler.php';

$api = new API();
$url = $api->GetURL() . "ratings";

$user_id = $_POST['user_id'];
$snippet_id = $_POST['snippet_id'];
$rating = $_POST['rating'];
$api = $_POST['api'];

$query = array('snippetid' => $snippet_id, 'userid' => $user_id, 'rating' => $rating, 'apikey' => $api);

$fields = '';
foreach ($query as $key => $value) {
    $fields .= $key . '=' . $value . '&';
}
rtrim($fields, '&');

$post = curl_init();

curl_setopt($post, CURLOPT_URL, $url);
curl_setopt($post, CURLOPT_POST, count($query));
curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);

$result = curl_exec($post);

curl_close($post);

if ($result) {
    echo 1;
} else {
    echo 0;
}