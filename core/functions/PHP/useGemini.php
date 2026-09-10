<?php

/**
 * Interacts with the Google Gemini API to get a generative model response.
 *
 * This function constructs and sends a request to the Google Gemini API's `generateContent`
 * endpoint. It takes a user message and optional parameters for context, instructions,
 * and generation configuration (like temperature, topK, etc.). The function handles
 * the cURL request and returns a JSON-encoded string indicating the outcome.
 *
 * @param string $userMessage           The primary text prompt or question for the model.
 * @param string $geminiKnowledge       (Optional) Additional context or knowledge to prepend to the user message,
 *                                      guiding the model's response.
 * @param string $geminiInstrcutions    (Optional) Specific instructions on how the model should process the request,
 *                                      prepended before knowledge and the user message.
 * @param float  $geminiTemperature     (Optional) Controls the randomness of the output. Accepts values between 0.0 and 1.0.
 *                                      Higher values produce more creative responses. Defaults to 0.7.
 * @param int    $geminiTopK            (Optional) The number of highest-probability tokens to consider at each step.
 *                                      Defaults to 40.
 * @param float  $geminiTopP            (Optional) The cumulative probability threshold for nucleus sampling. Defaults to 0.95.
 * @param int    $geminiMaxOutPutTokens (Optional) The maximum number of tokens to generate in the response. Defaults to 2048.
 *
 * @return string A JSON-encoded string representing the result. On success, it will contain
 *                `['success' => true, 'message' => 'The generated text']`. On failure, it will
 *                contain `['success' => 'error', 'error' => 'Error message']`.
 */
function useGemini(
    $userMessage,
    $geminiKnowledge = '',
    $geminiInstrcutions = '',
    $geminiTemperature = 0.7,
    $geminiTopK = 40,
    $geminiTopP = 0.95,
    $geminiMaxOutPutTokens = 2048
) {
    $geminiApiKey = getEnvValue('GEMINI_API_KEY');
    $geminiEndPoint = getEnvValue('GEMINI_ENDPOINT');
    $geminiModelId = getEnvValue('GEMINI_MODEL_ID');

    // SECURITY: Validate endpoint URL
    if (!filter_var($geminiEndPoint, FILTER_VALIDATE_URL)) {
        return json_encode(['success' => 'error', 'error' => 'Invalid endpoint URL']);
    }

    // SECURITY: Validate endpoint is Google's API (prevent SSRF)
    $allowedHosts = ['generativelanguage.googleapis.com'];
    $parsedUrl = parse_url($geminiEndPoint);
    if (!isset($parsedUrl['host']) || !in_array($parsedUrl['host'], $allowedHosts)) {
        return json_encode(['success' => 'error', 'error' => 'Invalid API endpoint host']);
    }

    // SECURITY: Validate temperature is within bounds
    if (!is_numeric($geminiTemperature) || $geminiTemperature < 0 || $geminiTemperature > 1) {
        $geminiTemperature = 0.7;
    }

    // SECURITY: Validate topK is within bounds
    if (!is_numeric($geminiTopK) || $geminiTopK < 1 || $geminiTopK > 100) {
        $geminiTopK = 40;
    }

    // SECURITY: Validate topP is within bounds
    if (!is_numeric($geminiTopP) || $geminiTopP < 0 || $geminiTopP > 1) {
        $geminiTopP = 0.95;
    }

    // SECURITY: Validate maxOutputTokens is within bounds
    if (!is_numeric($geminiMaxOutPutTokens) || $geminiMaxOutPutTokens < 1 || $geminiMaxOutPutTokens > 8192) {
        $geminiMaxOutPutTokens = 2048;
    }

    // SECURITY: Sanitize user message
    $userMessage = substr($userMessage, 0, 10000); // Limit message length

    try {
        // Prepare the request data
        $data = [
            'contents' => [
                'role'  => 'user',
                'parts' => [['text' => $userMessage]],
            ],
            'safetySettings' => [
                ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
            ],
            'generationConfig' => [
                'temperature'     => (float) $geminiTemperature,
                'topK'            => (int) $geminiTopK,
                'topP'            => (float) $geminiTopP,
                'maxOutputTokens' => (int) $geminiMaxOutPutTokens,
            ],
        ];

        // Add context if provided
        if (!empty($geminiKnowledge)) {
            $data['contents']['parts'][0]['text'] = substr($geminiKnowledge, 0, 5000)."\n".$userMessage;
        }

        // Add instructions if provided
        if (!empty($geminiInstrcutions)) {
            $data['contents']['parts'][0]['text'] = substr($geminiInstrcutions, 0, 5000)."\n".$data['contents']['parts'][0]['text'];
        }

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $geminiEndPoint.$geminiModelId.':generateContent?key='.$geminiApiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        // SECURITY: Set timeouts to prevent hanging
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        // SECURITY: Do not follow redirects
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // SECURITY: Validate HTTP response code
        if ($httpCode !== 200) {
            throw new Exception('API returned HTTP '.$httpCode);
        }

        // Decode response
        $responseData = json_decode($response, true);

        // Check if response contains candidates
        if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            return json_encode([
                'success' => true,
                'message' => $responseData['candidates'][0]['content']['parts'][0]['text'],
            ]);
        } else {
            throw new Exception('Invalid response format from Gemini API');
        }
    } catch (Exception $e) {
        // SECURITY: Don't expose internal error details
        if (getEnvValue('DEV_MODE') === 'true') {
            return json_encode(['success' => 'error', 'error' => $e->getMessage()]);
        }

        return json_encode(['success' => 'error', 'error' => 'API request failed']);
    }
}
