<?php

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
