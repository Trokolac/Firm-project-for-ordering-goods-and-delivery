<?php require './user-only.inc.php'; ?>
<?php include './header.layout.php'; ?>

<?php 

$productObject = new Product();

if( isset($_POST['remove_from_cart']) ) {
    if( $productObject->removeFromCart($_POST['cart_id']) ) {
      Helper::addMessage('Uspesno ste uklonili proizvod sa liste.');
      header("Location: ./cart.php");
      die();
    } else {
      Helper::addError('Doslo je do greske, osvezite stranicu i pokusajte ponovo.');
      header("Location: ./cart.php");
      die();
    }
  }

if( isset($_POST['update_quantity']) ) {
    if( $productObject->updateQuantity($_POST['cart_id'], $_POST['new_quantity']) ) {
      Helper::addMessage('Kolicina uspesno promenjena.');
      header("Location: ./cart.php");
      die();
    } else {
      Helper::addError('Doslo je do greske, osvezite stranicu i pokusajte ponovo.');
      header("Location: ./cart.php");
      die();
    }
  }

  
  if( isset($_POST['mail_to']) ) {
    $productMail = new Product();
    $productMail->mail();
    if($productMail){
        Helper::addMessage('Uspesno ste poslali poruku.');
        header("Location: ./cart.php");
        die();
    } else {
        Helper::addError('Doslo je do greske, osvezite stranicu i pokusajte ponovo.');
      header("Location: ./cart.php");
      die();
    }
  }

$cartProducts = $productObject->getCart();

?>

<?php if(!empty($cartProducts)) { ?> 
<div class="row">
    <div class="col-md-12 mt-2">
        <h3 style="">Lista trebovanja</h3> 
    </div>
</div>

<div class="row mt-3">  
     <div class="col-md-12">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Kategorija</th>
                <th scope="col">Proizvod</th>
                <th scope="col">Kolicina</th>
                <th scope="col">Izbrisati</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($cartProducts as $product) { ?>
                <tr>
                    <td><?php echo $product->title; ?></td>
                    <td><?php echo $product->name; ?></td>
                    <td>
                        <form action="./cart.php" method="post">
                            <div class="input-group input-group-sm">
                                <input type="hidden" name="cart_id" value="<?php echo $product->id; ?>" />
                                <input type="number" name="new_quantity" class="form-control" value="<?php echo $product->quantity; ?>" min="1" />
                                <div class="input-group-append">
                                    <button name="update_quantity" class="btn btn-outline-dark">Update</button>
                                </div>
                            </div>
                        </form>
                    </td>
                    <td>
                        <form action="./cart.php" method="post">
                        <input type="hidden" name="cart_id" value="<?php echo $product->id ?>" />
                        <button name="remove_from_cart" class="btn btn-sm btn-outline-danger"><i class="far fa-trash-alt"></i> Delete</button>
                        </form>
                    </td>
                </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>
</div>
<?php } ?>

<form action="cart.php" method="post">
    
    <div class="row mt-3">
        <div class="col-md-12">
            <input type="text" id="subject" name="subject" class="form-control">
            <label for="subject"> <b> Naziv mail-a </b> </label>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="md-form">
                <textarea rows="5" type="text" id="message" name="message" class="form-control md-textarea"><?php foreach($cartProducts as $product) { ?><?php echo $product->title; ?> / <?php echo $product->name; ?> x<?php echo $product->quantity; ?> <?php echo $product->measure; ?>&#10;<?php } ?></textarea>
                <label for="message"> <b> Poruka </b> </label>
            </div>
        </div>
    </div>
    
    <div class="row">
            <div class="col-md-12" style="text-align:right;">
                    <button name="mail_to" class="btn btn-outline-primary btn-lg mb-5" style="width:100px;">Posalji</button>
            </div>
    </div>
</form>

<?php include './footer.layout.php'; ?>