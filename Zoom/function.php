<?php
require_once 'vendor/autoload.php'; // Assuming you have GuzzleHttp installed via Composer

use GuzzleHttp\Client;

define('CLIENT_ID', 'jBcRiU9mQlwlIBiY1twjw');
define('CLIENT_SECRET', 'D6LwOroc0uYoFB5nL7XjQyV4NrF5UV8M');
define('REDIRECT_URI', 'http://localhost/myproject/Zoom/main.php');

// Function to Get Access Token
function get_access_token($code)
{
    try {
        $client = new Client(['base_uri' => 'https://zoom.us']);
        $response = $client->request('POST', '/oauth/token', [
            "headers" => [
                "Authorization" => "Basic " . base64_encode(CLIENT_ID . ':' . CLIENT_SECRET),
            ],
            'form_params' => [
                "grant_type" => "authorization_code",
                "code" => $code,
                "redirect_uri" => REDIRECT_URI,
            ],
        ]);
        $token = json_decode($response->getBody()->getContents(), true);
        return $token;
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

// Function to Get Refresh Token
function get_refresh_token($refresh_token)
{
    try {
        $client = new Client(['base_uri' => 'https://zoom.us']);
        $response = $client->request('POST', '/oauth/token', [
            "headers" => [
                "Authorization" => "Basic " . base64_encode(CLIENT_ID . ':' . CLIENT_SECRET),
            ],
            'form_params' => [
                "grant_type" => "refresh_token",
                "refresh_token" => $refresh_token,
            ],
        ]);
        $token = json_decode($response->getBody()->getContents(), true);
        return $token;
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

// Function to create a Zoom meeting
function create_a_zoom_meeting($meetingConfig = [])
{
    $requestBody = [
        'topic' => $meetingConfig['topic'] ?? 'New Meeting General Talk',
        'type' => $meetingConfig['type'] ?? 2,
        'start_time' => $meetingConfig['start_time'] ?? date('Y-m-d\TH:i:00') . 'Z',
        'duration' => $meetingConfig['duration'] ?? 30,
        'password' => $meetingConfig['password'] ?? mt_rand(),
        'timezone' => 'Asia/Kathmandu',
        'agenda' => $meetingConfig['agenda'] ?? 'Interview Meeting',
        'settings' => [
            'host_video' => false,
            'participant_video' => true,
            'cn_meeting' => false,
            'in_meeting' => false,
            'join_before_host' => true,
            'mute_upon_entry' => true,
            'watermark' => false,
            'use_pmi' => false,
            'approval_type' => 0,
            'registration_type' => 0,
            'audio' => 'voip',
            'auto_recording' => 'none',
            'waiting_room' => false,
        ],
    ];

    $client = new Client(['base_uri' => 'https://api.zoom.us']);
    try {
        $response = $client->request('POST', '/v2/users/me/meetings', [
            'headers' => [
                "Authorization" => "Bearer " . $meetingConfig['jwtToken'],
                "Content-Type" => "application/json",
                "cache-control" => "no-cache",
            ],
            'json' => $requestBody,
        ]);
        return [
            'success' => true,
            'msg' => 'success',
            'response' => json_decode($response->getBody()->getContents(), true),
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'msg' => $e->getMessage(),
            'response' => null,
        ];
    }
}
