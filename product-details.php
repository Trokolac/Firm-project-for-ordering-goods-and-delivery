<?php require './user-only.inc.php'; ?>
<?php require_once './Products.class.php';?>
<?php include './header.layout.php'; ?>


<?php
  if( !isset($_GET['id']) ) {
    Helper::addError("Stranica ne postoji.");
    header("Location: ./index.php");
  }

  $product = new Product($_GET['id']);
  
  $subproductslist = new Product();
  $subproductslist = $subproductslist->subProducts($_GET['id']);
  
  if( isset($_POST['create']) ) {
    $c = new Product();
    $c->id = $_POST['product_id'];
    if( $c->addSubProducts($_POST['name'] ) ) {
      Helper::addMessage("Proizvod unutar kategorije je kreiran.");
      header("Location: ./product-details.php?id=".$_GET['id']);
      die();
    } else {
      header("Location: ./product-details.php?id=".$_GET['id']);
      die();
    }
  }

  if( isset($_POST['add_to_cart']) ) {
    $productCart= new Product($_GET['id']);
    $productCart->measure = $_POST['measure'];
    
    if( $productCart->addToCart($_POST['quantity'],$_POST['ident']) ) {
      Helper::addMessage("{$_POST['quantity']} proizvod je dodat u listu trebovanja.");
      header("Location: ./product-details.php?id=".$_GET['id']);
      die();
    } else {
      Helper::addError("Nije uspelo dodavanje proizvoda / proverite da li ste oznacili jedinicu mere.");
      header("Location: ./product-details.php?id=".$_GET['id']);
      die();
    }
  } 

  
  if( isset($_POST['delete_subproduct']) ) {
    $d = new Product();
    if( $d->removeFromSubproducts($_POST['sub_id'] ) ) {
      Helper::addMessage("Proizvod unutar kategorije je izbrisan.");
      header("Location: ./product-details.php?id=".$_GET['id']);
      die();
    } else {
      header("Location: ./product-details.php?id=".$_GET['id']);
      die();
    }
  }

?>

<?php if($loggedInUser->acc_type == 'admin') { ?>
<form class="mt-4 clearfix" action="./product-details.php?id=<?php echo $product->id; ?>" method="post">
  <div class="form-row">
    <div class="form-group col-md-12">
      <label for="subproduct">Ime proizvoda</label>
      <input type="text" class="form-control" id="subproduct" placeholder="Upisi ime proizvoda ovde.." name="name" />
    </div>
</div>
  
  <input type="hidden" name="product_id" value="<?php echo $product->id; ?>" />
  <button name="create" class="btn btn-outline-dark btn-sm float-right">Kreiraj proizvod</button>
  
</form>
<?php } ?>


  <div class="row">
    <div class="col-md-12">
      <h5 class="mt-1"> <b> <?php echo $product->title; ?> </b> </h5>
    </div>
  </div>

  <div class="row mb-5">
  
    <?php foreach($subproductslist as $product) { ?>
      <div class="col-md-3 mt-3">
        <form action="./product-details.php?id=<?php echo $_GET['id']; ?>" method="post">
          <div class="card" style="width: 100%">
            <div class="card-header">
              <h6 class="card-title">
                <?php if($loggedInUser->acc_type == 'admin') { ?>
                  <input type="hidden" name="sub_id" value="<?php echo $product->id; ?>" />
                  <button style="text-align: right;" name="delete_subproduct" class="btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button> &emsp;
                <?php } ?>
                <?php echo $product->name; ?>  
              </h6>

                <label class="radio-inline"><input type="radio" name="measure" value="Kom"> Kom</label> &emsp;
                <label class="radio-inline"><input type="radio" name="measure" value="Ris"> Ris</label> &emsp;
                <label class="radio-inline"><input type="radio" name="measure" value="Pak"> Pak</label>
              
              <div class="input-group mt-3">
                <input type="hidden" name="ident" value="<?php echo $product->id; ?>" />
                <input type="number" name="quantity" class="form-control" value="1" min="1" />
                  <div class="input-group-append">  
                    <button name="add_to_cart" class="btn btn-outline-danger">
                      Dodaj
                    </button>
                  </div>
                  
              </div>
            </div>
          </div>
        </form>
      </div>
    <?php } ?>
  </div>





<?php include './footer.layout.php'; ?>