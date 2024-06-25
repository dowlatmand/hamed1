<?php
// Your bot token from BotFather
$botToken = "7372263166:AAH2Lp8pn1PWbvIb5Gq0Ud5C1LyY53HaGeE";

// The API URL for Telegram bot
$apiUrl = "https://api.telegram.org/bot$botToken/";

// Error logging
function logError($message) {
    file_put_contents("php://stderr", $message . PHP_EOL);
}

// Get the update from Telegram
$update = file_get_contents("php://input");

if ($update === FALSE) {
    logError("Failed to get update.");
}

$updateArray = json_decode($update, TRUE);

if ($updateArray === NULL) {
    logError("Failed to decode JSON.");
}

// Function to send a message with a button
function sendMessage($chatId, $text, $replyMarkup = null) {
    global $apiUrl;
    
    $url = $apiUrl . "sendMessage?chat_id=" . $chatId . "&text=" . urlencode($text);

    if ($replyMarkup) {
        $url .= "&reply_markup=" . urlencode(json_encode($replyMarkup));
    }

    $response = file_get_contents($url);

    if ($response === FALSE) {
        logError("Failed to send message.");
    }
}

// Check if the update contains a message
if (isset($updateArray['message'])) {
    $chatId = $updateArray['message']['chat']['id'];
    $messageText = $updateArray['message']['text'];

    // Define the buttons
    $replyMarkup = [
        'keyboard' => [
            [
                ['text' => "Option 1"],
                ['text' => "Option 2"]
            ]
        ],
        'resize_keyboard' => true,
        'one_time_keyboard' => true
    ];

    // Here you can add custom logic to respond to different messages
    if (strpos($messageText, "/start") === 0) {
        sendMessage($chatId, "Welcome to the bot! Please choose an option:", $replyMarkup);
    } elseif ($messageText === "Option 1") {
        sendMessage($chatId, "You chose Option 1");
    } elseif ($messageText === "Option 2") {
        sendMessage($chatId, "You chose Option 2");
    } else {
        sendMessage($chatId, "You said: " . $messageText);
    }
} else {
    logError("No message in update.");
}
?>
