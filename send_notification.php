<?php

require 'vendor/autoload.php';

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Client;

// The path to your JSON key
$serviceAccountFile = 'path/to/you/key.json';

// Function for obtaining access token
function getAccessToken($serviceAccountFile) {
    $client = new Google\Client();
    $client->setAuthConfig($serviceAccountFile);
    $client->setScopes(['https://www.googleapis.com/auth/cloud-platform']);
    $client->fetchAccessTokenWithAssertion();
    
    return $client->getAccessToken()['access_token'];
}

// Function for sending push notifications
function sendPushNotification($platform, $topic, $title, $message, $serviceAccountFile) {
    // Get access token
    $accessToken = getAccessToken($serviceAccountFile);

    // URL for FCM API V1
    $projectId = json_decode(file_get_contents($serviceAccountFile))->project_id;
    $url = "https://fcm.googleapis.com/v1/projects/$projectId/messages:send";

    // Setting the headers
    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json',
    ];

    // Generating platform-specific payloads
    if (strtolower($platform) == "ios") {
        $payload = [
            "message" => [
                "topic" => $topic,
                "data" => [
                    "title" => $title,
                    "body" => $message
                ],
                "notification" => [
                    "title" => $title,
                    "body" => $message
                ],
                "apns" => [
                    "payload" => [
                        "aps" => [
                            "content-available" => 1
                        ]
                    ]
                ]
            ]
        ];
    } elseif (strtolower($platform) == "android") {
        $payload = [
            "message" => [
                "topic" => $topic,
                "data" => [
                    "title" => $title,
                    "body" => $message
                ]
            ]
        ];
    } else {
        echo "Invalid platform. Choose either 'ios' or 'android'.";
        return;
    }

    // Initialise the cURL request
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    // Executing the request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Response processing
    if ($httpCode == 200) {
        echo "Notification successfully sent to $platform on topic $topic\n";
    } else {
        echo "Error sending notification: $httpCode, Response: $response\n";
    }
}

// Main part of the programme
echo "Enter the platform (ios or android): ";
$platform = trim(fgets(STDIN));

echo "Enter the topic (e.g., 'all' or 'ios'): ";
$topic = trim(fgets(STDIN));

echo "Enter the notification title: ";
$title = trim(fgets(STDIN));

echo "Enter the notification message (type 'END' on a new line to finish):\n";

// Collecting a multiline message
$lines = [];
while (true) {
    $line = trim(fgets(STDIN));
    if (strtoupper($line) == "END") {
        break;
    }
    $lines[] = $line;
}

$message = implode("\n", $lines);

// Sending a notification
sendPushNotification($platform, $topic, $title, $message, $serviceAccountFile);

?>