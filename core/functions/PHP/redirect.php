<?php

require_once 'getPage.php';

if (isset($_GET['route'])) {
    echo getPage($_GET['route']);
}
