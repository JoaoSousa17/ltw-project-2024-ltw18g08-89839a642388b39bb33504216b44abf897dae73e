<?php
include_once(__DIR__ . '/../utils/session.php');

session_destroy();
session_start();

header('Location: ../pages/mainPage.php');