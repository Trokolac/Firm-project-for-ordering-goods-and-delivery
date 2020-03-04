<?php

$db=require './db.inc.php';
$conf=require './config.inc.php';

// ADDING USERS TO DB
$stmt_createUsersTable = $db->prepare("
    CREATE TABLE IF NOT EXISTS `users`(
        `id` int AUTO_INCREMENT PRIMARY KEY,
        `email` varchar(30),
        `name` varchar(30),
        `password` varchar(32),
        `acc_type` enum('user', 'admin') DEFAULT 'user',
        `created_at` datetime DEFAULT now(),
        `updated_at` datetime DEFAULT now() ON UPDATE now(),
        `deleted_at` datetime DEFAULT NULL  
    )
");

$stmt_createUsersTable->execute();

// ADDING ADMIN TO USERS
$stmt_getUsers = $db->prepare("
  SELECT *
  FROM `users`
");
$stmt_getUsers->execute();
$numOfUsers = $stmt_getUsers->rowCount();

if( $numOfUsers <= 0 ) {
  $stmt_addAdmin = $db->prepare("
    INSERT INTO `users`
      (`name`, `email`, `password`, `acc_type`)
    VALUES
      (:name, :email, :password, :acc_type)
  ");
  $stmt_addAdmin->execute([
    ':name' => $conf['admin_name'],
    ':email' => $conf['admin_email'],
    ':password' => md5($conf['admin_password']),
    ':acc_type' => 'admin'
  ]);
}

// ADDING PRODUCTS TO DB
$stmt_createProductsTable = $db->prepare("
    CREATE TABLE IF NOT EXISTS `products`(
        `id` int AUTO_INCREMENT PRIMARY KEY,
        `title` varchar(255),
        `created_at` datetime DEFAULT now(),
        `updated_at` datetime DEFAULT now() ON UPDATE now(),
        `deleted_at` datetime DEFAULT NULL  
    )
");

$stmt_createProductsTable->execute();

//ADDING SUBPRODUCTS TO DB
$stmt_createSubProductsTable = $db->prepare("
    CREATE TABLE IF NOT EXISTS `subproducts`(
        `id` int AUTO_INCREMENT PRIMARY KEY,
        `product_id` int,
        `name` varchar(255),
        `created_at` datetime DEFAULT now(),
        `updated_at` datetime DEFAULT now() ON UPDATE now(),
        `deleted_at` datetime DEFAULT NULL  
    )
");

$stmt_createSubProductsTable->execute();

//ADDING CART TO DB
$stmt_createCartsTable = $db->prepare("
  CREATE TABLE IF NOT EXISTS `carts` (
    `id` int AUTO_INCREMENT PRIMARY KEY,
    `user_id` int,
    `subproduct_id` int,
    `product_id` int,
    `quantity` int,
    `measure` varchar(3),
    `created_at` datetime DEFAULT now()
  )
");
$stmt_createCartsTable->execute();


var_dump( $db->errorInfo() );