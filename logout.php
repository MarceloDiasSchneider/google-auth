<?php
session_start();
session_destroy();

require __DIR__ . "/vendor/autoload.php";


header("Location: " . GOOGLE['redirectUri']);

