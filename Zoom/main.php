<?php
require_once 'vendor/autoload.php';
require_once 'function.php';
require_once 'config.php';

if (!isset($_GET['code'])) {
    // Starting the OAuth flow
    $url = "https://zoom.us/oauth/authorize?response_type=code&client_id=" . CLIENT_ID . "&redirect_uri=" . REDIRECT_URI;
    echo '<a href="' . $url . '">Login with Zoom</a>';
} else {
    // Get the access token
    $get_token = get_access_token($_GET['code']);

    // Create the meeting
    $get_new_meeting_details = create_a_zoom_meeting([
        'topic'         => 'Let Learn Zoom API Integration In PHP',
        'type'          => 2,
        'start_time'    => date('Y-m-d\TH:i:00\Z'),
        'password'      => mt_rand(),
        'jwtToken'      => $get_token['access_token'], // Corrected the key name from 'token' to 'jwtToken'
        'refresh_token' => $get_token['refresh_token'],
    ]);

    if (isset($get_new_meeting_details['msg']) && $get_new_meeting_details['msg'] == 'success') {
        echo $get_new_meeting_details['response']['uuid']; // Uncomment to display the UUID of the created meeting
    } else {
        echo "OPPS!! Error";
    }
}
