<?php

require_once './User.class.php';
require_once './Helper.class.php';

if( !User::isLoggedIn() ) {
  header('Location: ./login.php');
  die();
}


