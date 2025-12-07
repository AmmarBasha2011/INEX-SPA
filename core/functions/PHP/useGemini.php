<?php

/**
 * Sends a request to the Gemini API and returns the response.
 *
 * @param string $userMessage           The user's message to send to the API.
 * @param string $geminiKnowledge       Optional knowledge to provide to the API.
 * @param string $geminiInstrcutions    Optional instructions for the API.
 * @param float  $geminiTemperature     The temperature for the generation.
 * @param int    $geminiTopK            The top-k value for the generation.
 * @param float  $geminiTopP            The top-p value for the generation.
 * @param int    $geminiMaxOutPutTokens The maximum number of output tokens.
 *
 * @return string A JSON-encoded string containing the API response.
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

        if (!empty($geminiKnowledge)) {
            $data['contents']['parts'][0]['text'] = $geminiKnowledge."\n".$userMessage;
        }

        if (!empty($geminiInstrcutions)) {
            $data['contents']['parts'][0]['text'] = $geminiInstrcutions."\n".$data['contents']['parts'][0]['text'];
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $geminiEndPoint.$geminiModelId.':generateContent?key='.$geminiApiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);

        $responseData = json_decode($response, true);

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
