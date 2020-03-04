<?php

require_once './User.class.php';
require_once './Helper.class.php';

$loggedInUser = new User();
$loggedInUser->loadLoggedInUser();

if( $loggedInUser->acc_type != 'admin' ) {
  Helper::addError('Nemate pristup ovoj stranici.');
  header('Location: ./index.php');
  die();
}
