<?php

class Product {
    private $db;
    public $id;
    public $name;
    public $created_at;
    public $updated_at;
    public $deleted_at;

    function __construct($id = null) {
        $this->db = require './db.inc.php';

        if($id) {
            $this->id = $id;
            $this->loadFromDB();
        }
    }

  public function loadFromDB() {
    $stmt_get = $this->db->prepare("
      SELECT *
      FROM `products`
      WHERE `id` = :id
    ");
    $stmt_get->execute([ ':id' => $this->id ]);
    $product = $stmt_get->fetch();

    if( !$product ) {
      return false;
    }

    foreach( get_object_vars($product) as $key => $value ) {
      $this->$key = $value;
    }
  }

  public function insert() {

    if( !$this->nameIsEmpty() ) {
      return false;
    }

    if( !$this->productIsAvailable() ) {
      return false;
    } 

    $stmt_insert = $this->db->prepare("
      INSERT INTO `products`
        (`title`)
      VALUES
        (:title)
    ");
    return $stmt_insert->execute([
      ':title' => ucfirst($this->title)
    ]);
  }

  public function nameIsEmpty() {

    if( $this->title == "" ) {
      Helper::addError('Kategorija mora da ima ime.');
      return false;
    }

    return true;
  }

  public function productIsAvailable() {
    $stmt_getName = $this->db->prepare("
      SELECT *
      FROM `products`
      WHERE `title` = :title
    ");
    $stmt_getName->execute([ ':title' => $this->title ]);

    if( $stmt_getName->rowCount() > 0 ) {
      Helper::addError('Ime je vec zauzeto.');
      return false;
    }

    return true;
  }

  public function allProducts() { 
    $stmt_get = $this->db->prepare("
      SELECT *
      FROM `products`
      WHERE `deleted_at` IS NULL
      ORDER BY id ASC
    ");
    $stmt_get->execute();
    return $stmt_get->fetchAll();
  }

  public function addSubProducts($name) {

    if( !$this->subnameIsEmpty($name) ) {
      return false;
    } 
    if( !$this->subproductIsAvailable($name) ) {
      return false;
    } 

    $stmt_addSubProducts = $this->db->prepare("
      INSERT INTO `subproducts`
      (`product_id`, `name`)
      VALUES
      (:product_id, :name)
      ");
    return $stmt_addSubProducts->execute([
      ':product_id' => $this->id,
      ':name' => ucfirst($name)
      ]);

  }
    
  public function subnameIsEmpty($name) {

    if( $name == "" ) {
      Helper::addError('Proizvod mora da ima ime.');
      return false;
    }
      return true;
    }

  public function subproductIsAvailable($name) {
    $stmt_getName = $this->db->prepare("
      SELECT *
      FROM `subproducts`
      WHERE `name` = :name
    ");
    $stmt_getName->execute([ ':name' => $name ]);
  
    if( $stmt_getName->rowCount() > 0 ) {
      Helper::addError('Ime proizvoda je vec zauzeto.');
      return false;
      die();
    }
      return true;
  }

  public function removeFromSubproducts($id) {
    $stmt_removeFromCart = $this->db->prepare("
      DELETE
      FROM `subproducts`
      WHERE `id` = :id
    ");
    $stmt_removeFromCart->execute([ ':id' => $id ]);

    $stmt_deleteSubFromCarts = $this->db->prepare("
      DELETE
      FROM `carts`
      WHERE `subproduct_id` = :subproduct_id
    ");
    return $stmt_deleteSubFromCarts->execute([':subproduct_id' => $id]);
  }

  public function subProducts($id) { 
    $stmt_get = $this->db->prepare("
      SELECT *
      FROM `subproducts`
      WHERE `product_id` = $id
      ORDER BY id ASC
    ");
    $stmt_get->execute();
    return $stmt_get->fetchAll();
  }
    
    // <=====================================================================>

  public function addToCart($quantity, $ident) {
    Helper::sessionStart();
  
    if( !isset($_SESSION['user_id']) ) {
      Helper::addError('Morate biti ulogovani da bi dodali proizvod u listu trebovanja.');
      return false;
    }
  
    

    //SHOWING CART TO SPECIFIC USER
    $stmt_getCartProduct = $this->db->prepare("
      SELECT *
      FROM `carts`
      WHERE `user_id` = :user_id
      AND `subproduct_id` = :subproduct_id
    ");
    $stmt_getCartProduct->execute([
      ':user_id' => $_SESSION['user_id'],
      ':subproduct_id' => $ident
    ]);
    $productInCart = $stmt_getCartProduct->fetch();

    //INSERT INTO CART AND UPDATE
    if( !$this->measureIsEmpty() ) {
      return false;
    }

    if($productInCart) {
      $stmt_updateQuantity = $this->db->prepare("
        UPDATE `carts`
        SET `quantity` = :new_quantity
        WHERE `id` = :cart_id
      ");
      return $stmt_updateQuantity->execute([
        ':new_quantity' => $productInCart->quantity + $quantity,
        ':cart_id' => $productInCart->id
      ]);
    } else {

      

      $stmt_addToCart = $this->db->prepare("
        INSERT INTO `carts`
        (`user_id`, `subproduct_id`, `product_id` , `quantity`, `measure`)
        VALUES
        (:user_id, :subproduct_id, :product_id , :quantity, :measure)
      ");
      return $stmt_addToCart->execute([
        ':user_id' => $_SESSION['user_id'],
        ':product_id' => $this->id,
        ':subproduct_id' => $ident,
        ':quantity' => $quantity,
        ':measure' => $this->measure

      ]);
    }
  }
  
  public function measureIsEmpty() {

    if( $this->measure == "" ) {
      return false;
    }

    return true;
  }

  public function getCart() {
    Helper::sessionStart();
  
    $stmt_getCart = $this->db->prepare("
      SELECT
      `carts`.`id`,
      `carts`.`subproduct_id`,
      `carts`.`product_id`,
      `products`.`title`,
      `carts`.`quantity`,
      `carts`.`measure`,
      `carts`.`created_at`,
      `subproducts`.`name`
      FROM `carts`, `products`, `subproducts`
      WHERE `carts`.`subproduct_id` = `subproducts`.`id`
      AND `carts`.`user_id` = :user_id
      AND `carts`. `product_id` = `products`.`id`
      ORDER BY product_id ASC, subproduct_id ASC
    ");
    $stmt_getCart->execute([ ':user_id' => $_SESSION['user_id'] ]);
    return $stmt_getCart->fetchAll();
  }
  
  public function updateQuantity($cartId, $newQuantity) {
    $stmt_updateQuantity = $this->db->prepare("
      UPDATE `carts`
      SET `quantity` = :new_quantity
      WHERE `id` = :cart_id
    ");
    return $stmt_updateQuantity->execute([
      ':cart_id' => $cartId,
      ':new_quantity' => $newQuantity
    ]);
  }
    
  public function removeFromCart($id) {
    $stmt_removeFromCart = $this->db->prepare("
      DELETE
      FROM `carts`
      WHERE `id` = :id
    ");
    return $stmt_removeFromCart->execute([ ':id' => $id ]);
  }

  public function all() {
      
    $usid = $_SESSION['user_id'];
      
    $stmt_get = $this->db->prepare("
      SELECT *
      FROM `carts`
      WHERE `user_id` = $usid
    ");
    $stmt_get->execute();
    $test = $stmt_get->fetchAll();
    
    $end = count($test);
      
    if($end){
      echo '&ensp;<span class="badges" >';
      echo $end;
      echo '</span>';
    }
  }
  
  public function delete() {
    $stmt_delete = $this->db->prepare("
      DELETE
      FROM `products`
      WHERE `id` = :id
    ");
    $stmt_delete->execute([ ':id' => $this->id ]);

    $stmt_deleteImages = $this->db->prepare("
      DELETE
      FROM `carts`
      WHERE `product_id` = :product_id
    ");
    $stmt_deleteImages->execute([ ':product_id' => $this->id ]);
  
    $stmt_deleteComponents = $this->db->prepare("
      DELETE
      FROM `subproducts`
      WHERE `product_id` = :product_id
    ");
    return $stmt_deleteComponents->execute([':product_id' => $this->id]);
  }

  //SEND MAIL

  public function mail(){
    require_once './Helper.class.php';
    require_once './User.class.php';
      
    $loggedInUser = new User();
    $loggedInUser->loadLoggedInUser();

    $id = $loggedInUser->id;
    $name = $loggedInUser->name;
    $email = $loggedInUser->email;
    if(isset( $_POST['message']))
    $message = $_POST['message'];
    if(isset( $_POST['subject']))
    $subject = $_POST['subject'];
      
    if ($email === ''){
      echo "Doslo je do greske.";
      die();
    } else {
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "Email format nije validan.";
        die();
      }
    }
      
    if ($subject === ''){
      Helper::addError('Poruka mora da sadrzi naziv email-a.');
      header("Location: ./cart.php");
      die();
    }

    // send mail via php

    $content="Mail recived from: $email" . " / $name" . PHP_EOL . "\nMessage:" . PHP_EOL . "\n$message";
    $recipient = "email1@gmail.com,email2@gmail.com"; // staviti 2 mail-a
    $mailheader = "From: $email" . "\r\n" . PHP_EOL;

    $mail_status = mail($recipient, $subject, $content, $mailheader);

    if ($mail_status){
      

      $stmt_deleteComponents = $this->db->prepare("
        DELETE
        FROM `carts`
        WHERE `user_id` = :user_id
      ");
      return $stmt_deleteComponents->execute([':user_id' => $id]);
    } 
  }
}