<?php 

class User {
    private $db;
    public $id;
    public $email;
    public $name;
    public $password;
    public $created_at;
    public $updated_at;
    public $deleted_at;

    function __construct($id = null) {
      require_once './Helper.class.php';
      $this->db = require './db.inc.php';
  
      if( $id != null ) {
        $this->id = $id;
        $this->loadUserFromDB();
      }
    }
  
    public function loadUserFromDB() {
      $stmt_get = $this->db->prepare("
        SELECT *
        FROM `users`
        WHERE `id` = :id
      ");
      $stmt_get->execute([ ':id' => $this->id ]);
      $user = $stmt_get->fetch();
  
      if( !$user ) {
        return false;
      }
  
      foreach( get_object_vars($user) as $key => $value ) {
        $this->$key = $value;
      }
    }

    public function login() {
      $stmt_getUser = $this->db->prepare("
        SELECT *
        FROM `users`
        WHERE `email` = :email
        AND `password` = :password
      ");
      $stmt_getUser->execute([
        ':email' => $this->email,
        ':password' => md5($this->password)
      ]);
  
      $user = $stmt_getUser->fetch();
  
      if( !$user ) {
        Helper::addError('Prijavljivanje nije uspesno. Proverite vas e-mail ili sifru.');
        return false;
      }
  
      Helper::sessionStart();
      $_SESSION['user_id'] = $user->id;
      return true;
    }
  
    
    public static function isLoggedIn() {
      require_once './Helper.class.php';
      Helper::sessionStart();
      return isset($_SESSION['user_id']) && $_SESSION['user_id'] != "";
    }
  
    public function loadLoggedInUser() {
      if( !User::isLoggedIn() ) {
        return false;
      }
      Helper::sessionStart();
      $this->id = $_SESSION['user_id'];
      $this->loadUserFromDB();
    }

  
}