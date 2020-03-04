<?php require './admin-only.inc.php'; ?>
<?php require_once './Products.class.php'; ?>
<?php include './header.layout.php'; ?>

<?php


$listOfProducts = new Product();
$listOfProducts = $listOfProducts->allProducts();

if( isset($_POST['create']) ) {
    $c = new Product();
    $c->title = $_POST['name'];
    if( $c->insert() ) {
      Helper::addMessage("Kategorija kreirana uspesno.");
      header('Location: ./create-products.php');
      die();
    } else {
      header('Location: ./create-products.php');
      die();
    }
  }

  if( isset($_POST['remove_from_products']) ) {
    $projectToDelete = new Product($_POST['product_id']);
    if( $projectToDelete->delete() ) {
    Helper::addMessage("Project deleted successfully.");
    header('Location: ./create-products.php');
    die();
    } else {
    Helper::addError("Failed to delete project.");
    } 
  }

?>

<form class="mt-4 clearfix" action="./create-products.php" method="post">
  <div class="form-row">
    <div class="form-group col-md-12">
      <label for="inputComponentName">Ime kategorije</label>
      <input type="text" class="form-control" id="inputComponentName" placeholder="Upisi ime kategorije ovde.." name="name" />
    </div>
</div>
  <button name="create" class="btn btn-outline-dark float-right">Kreiraj kategoriju</button>
</form>

<div class="row">
  
    <?php foreach($listOfProducts as $product) { ?>
      <div class="col-md-3 mt-3">
        <form action="./create-products.php" method="post">
          <div class="card" style="width: 100%">
            <div class="card-header" style="text-align:center;">
              <h5 class="card-title">
                <?php echo $product->title; ?>
              </h5>
                <input type="hidden" name="product_id" value="<?php echo $product->id; ?>" />
                <button style="text-align:center;" name="remove_from_products" class="btn btn-outline-danger"><i class="far fa-trash-alt"></i> Delete</button>
            </div>
          </div>
        </form>
      </div>
    <?php } ?>
  </div>


<?php include './footer.layout.php'; ?>



