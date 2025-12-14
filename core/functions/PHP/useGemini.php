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
                'temperature'     => $geminiTemperature,
                'topK'            => $geminiTopK,
                'topP'            => $geminiTopP,
                'maxOutputTokens' => $geminiMaxOutPutTokens,
            ],
        ];

        // Add context if provided
        if (!empty($geminiKnowledge)) {
            $data['contents']['parts'][0]['text'] = $geminiKnowledge."\n".$userMessage;
        }

        // Add instructions if provided
        if (!empty($geminiInstrcutions)) {
            $data['contents']['parts'][0]['text'] = $geminiInstrcutions."\n".$data['contents']['parts'][0]['text'];
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

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);

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
        return json_encode([
            'success' => 'error',
            'error'   => $e->getMessage(),
        ]);
    }
}
