<?php 

require_once './Helper.class.php';

Helper::sessionStart();

unset($_SESSION['user_id']);

header("Location: ./login.php");