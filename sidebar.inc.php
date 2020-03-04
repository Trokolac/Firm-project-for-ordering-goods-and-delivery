<?php require_once './Products.class.php'; ?>


<?php

  $productList = new Product();
  $productList = $productList->allProducts();
  
?>

<?php if($loggedInUser->acc_type == 'admin') { ?>
  <div class="list-group sidebar" style="padding-bottom:15px;">
    <a href="./create-products.php" class="list-group-item list-group-item-action">
      <h6><strong>Kreiraj kategoriju</strong></h6>
    </a>
  </div>
<?php } ?>

<p>
  <button class="btn btn-outline-dark" type="button" style="width:100%;" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><i class="fas fa-bars"></i> Kategorije</button>
</p>
<div id="collapseOne" class="list-group sidebar collapse show">
  <?php foreach($productList as $product) { ?>
    <a href="./product-details.php?id=<?php echo $product->id;?>" class="list-group-item list-group-item-action">
      <h6><strong><?php echo "$product->title"; ?></strong></h6>
    </a>
  <?php } ?>
</div>


    