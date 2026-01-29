<?php

class AskGeminiCommand extends Command {
    public function __construct() {
        parent::__construct('ask:gemini', 'Ask Gemini');
    }

    public function execute($args) {
        $question = $args['1'] ?? readline("1- What's your question? ");
        if (!$question) {
            Terminal::error("Question is required!");
            return;
        }

        Terminal::info("Asking Gemini...");
        $response = useGemini($question);
        $gemini = json_decode($response, true);

        if (isset($gemini['success']) && $gemini['success'] == true) {
            Terminal::header("Gemini Response");
            echo $gemini['message'] . PHP_EOL . PHP_EOL;
        } else {
            Terminal::error("Error: " . ($gemini['error'] ?? 'Unknown error'));
        }
    }
}

$registry->register(new AskGeminiCommand());
