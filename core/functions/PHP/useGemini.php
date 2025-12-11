<?php

/**
 * Sends a request to the Google Gemini API and returns the response.
 *
 * This function constructs a request to the Gemini API with the provided message,
 * knowledge, instructions, and generation parameters. It handles the API call
 * via cURL and returns a JSON-encoded string containing either the successful
 * response or an error message.
 *
 * @param string $userMessage The primary message or prompt from the user.
 * @param string $geminiKnowledge Optional. Contextual information or knowledge to provide to the model.
 * @param string $geminiInstrcutions Optional. Specific instructions for the model on how to behave or process the prompt.
 * @param float $geminiTemperature Optional. Controls the randomness of the output. Value from 0.0 to 1.0.
 * @param int $geminiTopK Optional. The number of highest probability vocabulary tokens to keep for Top-K sampling.
 * @param float $geminiTopP Optional. The cumulative probability of tokens to consider for nucleus sampling.
 * @param int $geminiMaxOutPutTokens Optional. The maximum number of tokens to generate in the response.
 * @return string A JSON-encoded string with a 'success' status and either a 'message' or 'error' key.
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
