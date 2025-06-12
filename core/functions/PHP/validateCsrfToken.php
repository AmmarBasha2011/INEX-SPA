<?php

session_start();

function validateCsrfToken()
{
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        exit('CSRF validation failed!');
    }
}
