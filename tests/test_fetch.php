<?php

// To run this test, make sure USE_FETCH is true in your .env file.
define('TESTING', true);
require_once __DIR__.'/../index.php';

function testFetchFunctionExists()
{
    if (function_exists('useFetch')) {
        echo "Test Passed: useFetch function exists.\n";
    } else {
        echo "Test Failed: useFetch function does not exist.\n";
    }
}

function testFetchApiCall()
{
    $response = useFetch('https://jsonplaceholder.typicode.com/todos/1');

    if ($response['status'] === 200) {
        $data = json_decode($response['body'], true);
        if (isset($data['userId']) && $data['userId'] === 1) {
            echo "Test Passed: API call to jsonplaceholder successful.\n";
        } else {
            echo "Test Failed: API call to jsonplaceholder failed, response body is not as expected.\n";
        }
    } else {
        echo 'Test Failed: API call to jsonplaceholder failed with status code '.$response['status'].".\n";
    }
}

testFetchFunctionExists();
testFetchApiCall();
