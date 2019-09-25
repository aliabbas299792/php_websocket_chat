<?php
// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 360000);

// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(3600);

session_start(); // ready to go!

error_reporting(E_ALL ^ E_NOTICE);
session_start();

$passcode = 123456789; //gotta make this random once every 12 hours, and no messages after a week
$method = "AES-128-ECB";

$dbAddress = "localhost";
$dbPass = "";
$dbUsername = "root";
$dbName = "erewhon";