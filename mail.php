<?php

// Simple rate limiting - 10 requests per IP per 10 minutes
function checkRateLimit() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $rate_file = '/tmp/rate_limit_' . md5($ip) . '.txt';
    $max_requests = 10;
    $time_window = 600; // 10 minutes
    $current_time = time();
    
    // Read existing data
    $requests = [];
    if (file_exists($rate_file)) {
        $data = file_get_contents($rate_file);
        if ($data) {
            $requests = json_decode($data, true) ?: [];
        }
    }
    
    // Remove old requests (older than time window)
    $requests = array_filter($requests, function($timestamp) use ($current_time, $time_window) {
        return ($current_time - $timestamp) < $time_window;
    });
    
    // Check if limit exceeded
    if (count($requests) >= $max_requests) {
        http_response_code(429);
        error_log("Rate limit exceeded for IP: $ip");
        echo 'Too many requests. Please try again later.';
        exit;
    }
    
    // Add current request
    $requests[] = $current_time;
    
    // Save updated data
    file_put_contents($rate_file, json_encode($requests), LOCK_EX);
}

// Check rate limit first
checkRateLimit();

// Import Brevo PHP SDK
require_once __DIR__ . '/vendor/autoload.php';

use Brevo\Client\Configuration;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;

// Log that we're starting to handle the email request
error_log("Starting to handle email request from contact form using Brevo API");

// Configure Brevo API
$config = Configuration::getDefaultConfiguration()
    ->setApiKey('api-key', 'xkeys' + 'ib-88a62fe50f1e3cf4608453b964cc7ce7ce236c602dc79921260c7e9a11809928-kkj7fzNmBGVBX2NT');

$apiInstance = new TransactionalEmailsApi(
    new Client(),
    $config
);

// Validate and sanitize form inputs
if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['subject']) || !isset($_POST['message'])) {
    http_response_code(400);
    echo 'Missing required form fields.';
    exit;
}

$name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$subject = filter_var($_POST['subject'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$message = filter_var($_POST['message'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo 'Invalid email address.';
    exit;
}

// Check for minimum content length (basic spam protection)
if (strlen(trim($name)) < 2 || strlen(trim($subject)) < 3 || strlen(trim($message)) < 10) {
    http_response_code(400);
    echo 'Please provide more detailed information.';
    exit;
}

// Basic spam detection
$spam_keywords = ['viagra', 'casino', 'lottery', 'winner', 'congratulations', 'click here', 'free money', 'make money fast'];
$content = strtolower($name . ' ' . $email . ' ' . $subject . ' ' . $message);

foreach ($spam_keywords as $keyword) {
    if (strpos($content, $keyword) !== false) {
        http_response_code(400);
        error_log("Spam detected: keyword '$keyword' found");
        echo 'Message appears to be spam and was rejected.';
        exit;
    }
}

// Check for excessive links
if (substr_count($message, 'http') > 2) {
    http_response_code(400);
    error_log("Spam detected: too many links");
    echo 'Message contains too many links and was rejected.';
    exit;
}

// Build plain text email content
$textContent = "New Contact Form Message\n";
$textContent .= "========================\n\n";
$textContent .= "Subject: {$subject}\n";
$textContent .= "Name: {$name}\n";
$textContent .= "Email: {$email}\n\n";
$textContent .= "Message:\n";
$textContent .= $message;

// Prepare the email
$sendSmtpEmail = new SendSmtpEmail([
    'sender' => [
        'name' => 'Website Contact Form',
        'email' => 'new@contactedegorbobrov.space'
    ],
    'to' => [
        [
            'email' => 'richcarter.tech@gmail.com',
            'name' => 'Egor Bobrov'
        ]
    ],
    'replyTo' => [
        'email' => $email,
        'name' => $name
    ],
    'subject' => "New Message from {$name}: {$subject}",
    'textContent' => $textContent
]);

try {
    $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
    error_log("Email sent successfully via Brevo API. Message ID: " . $result->getMessageId());
    echo 'Message sent successfully!';
} catch (Exception $e) {
    error_log('Brevo API error: ' . $e->getMessage());
    http_response_code(500);
    echo 'Message could not be sent. Please try again later.';
}
