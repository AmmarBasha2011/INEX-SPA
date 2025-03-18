<?php

function inexSpaHelper($userMessage) {
// Gemini API configuration
$apiKey = getEnvValue('GEMINI_API_KEY');
$apiUrl = getEnvValue('GEMINI_ENDPOINT') . getEnvValue('GEMINI_MODEL_ID') .':generateContent';

// Get the prompt context from the knowledge file
$knowledgeContent = getKnowledgeContent();

// Prepare the prompt for Gemini
$promptText = buildPrompt($userMessage, $knowledgeContent);

// Build the request payload
$payload = [
    'contents' => [
        [
            'parts' => [
                [
                    'text' => $promptText
                ]
            ]
        ]
    ],
    'generationConfig' => [
        'temperature' => 0.7,
        'topK' => 40,
        'topP' => 0.95,
        'maxOutputTokens' => 2048,
    ]
];

// Send request to Gemini API
try {
    $response = callGeminiApi($apiUrl, $apiKey, $payload);
    
    // Extract the response text
    if (isset($response['candidates'][0]['content']['parts'][0]['text'])) {
        $aiResponse = $response['candidates'][0]['content']['parts'][0]['text'];
        return sendSuccessResponse($aiResponse);
    } else {
        return sendErrorResponse('Invalid response format from Gemini API');
    }
} catch (Exception $e) {
    return sendErrorResponse('Error communicating with Gemini API: ' . $e->getMessage());
}
}

/**
 * Call the Gemini API with the provided payload
 */
function callGeminiApi($url, $apiKey, $payload) {
    // Prepare the URL with API key
    $fullUrl = $url . '?key=' . $apiKey;
    
    // Setup cURL request
    $ch = curl_init($fullUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    
    // Execute the request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Check for errors
    if (curl_errno($ch)) {
        curl_close($ch);
        throw new Exception('cURL error: ' . curl_error($ch));
    }
    
    curl_close($ch);
    
    // Process response
    $responseData = json_decode($response, true);
    
    // Check HTTP status code
    if ($httpCode != 200) {
        $errorMessage = isset($responseData['error']['message']) 
            ? $responseData['error']['message'] 
            : 'Unknown error (HTTP ' . $httpCode . ')';
        throw new Exception($errorMessage);
    }
    
    return $responseData;
}

/**
 * Get the content of the knowledge file
 */
function getKnowledgeContent() {
    // Try to fetch the README.md from GitHub
    $readmeUrl = __DIR__ . "/train.txt";
    
    // $ch = curl_init($readmeUrl);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // $content = curl_exec($ch);
    
    // if (curl_errno($ch)) {
    //     curl_close($ch);
    //     return ""; // Return empty if no knowledge content is available
    // }
    
    // curl_close($ch);

    $content = file_get_contents($readmeUrl);
    
    return $content;
}

/**
 * Build the prompt for Gemini, combining user query with knowledge
 */
function buildPrompt($userMessage, $knowledgeContent) {
    // Construct a system prompt that instructs the model on how to respond
    $systemPrompt = <<<EOT
You are INEX SPA Helper, an AI assistant specializing in the INEX SPA PHP Framework.
Your purpose is to help users understand the framework and build applications with it.

Use the following knowledge about the INEX SPA Framework to answer user questions accurately:

---FRAMEWORK KNOWLEDGE---
$knowledgeContent
---END FRAMEWORK KNOWLEDGE---

Guidelines for your responses:
1. Provide accurate, helpful information about the INEX SPA Framework.
2. When showing code examples, use proper formatting with code blocks.
3. If you don't know the answer, say so rather than making up information.
4. Keep responses concise but thorough.
5. Format code snippets using triple backticks.
6. When a user asks about implementing something, provide code examples when possible.

User Query: $userMessage

Please provide a helpful response based on the framework knowledge above.
EOT;

    return $systemPrompt;
}

/**
 * Send a success response
 */
function sendSuccessResponse($message) {
    return json_encode([
        'success' => true,
        'message' => $message
    ]);
}

/**
 * Send an error response
 */
function sendErrorResponse($error) {
    return json_encode([
        'success' => false,
        'error' => $error
    ]);
}