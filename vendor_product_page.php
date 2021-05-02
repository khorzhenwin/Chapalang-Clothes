<?php
include "includes/vendor_session.php";
include("includes/database.php");
$rows = mysqlidb::fetchAllRows("SELECT * FROM product
WHERE product.ProductId = '$_GET[productid]'");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="images/shirtlogo.png" type="image/x-icon">
  <link rel="stylesheet" href="styles/template.css" />
  <link rel="stylesheet" href="styles/vendor.css" />
  <link rel="stylesheet" href="styles/pagetopbar.css" />
  <title>Chapalang Clothes</title>
</head>

<body>
  <!-- ----------------------------------- TOP-BAR ----------------------------------- -->
  <?php include "includes/pagetopbar.php" ?>
  <!-- ----------------------------------- TOP-BAR ----------------------------------- -->
  <div class="body-wrapper">
    <div></div>
    <!-- ----------------------------------- BODY ----------------------------------- -->
    <div class="content-wrapper container">
      <div class="vendor-container">
        <?php
        include("includes/vendor_sidenav.php");
        ?>
        <div class="vendor-body">
          <?php
          
          foreach ($rows as $row) {
            $productimages = explode(",", $row['ProductImage'])[0];
            echo "<div class=\"productdetails\">
            <h1 class=\"m-0\">Product Details for {$rows[0]['ProductName']}</h1>
            <hr>
            <h3>Product ID : $row[ProductId]</h3>
            <h3>Product Name : $row[ProductName]</h3>
            <h3>Description : $row[ProductDescription]</h3>
            <h3>Product Tags : $row[ProductTags]</h3>
            <h3>Product Price : RM $row[ProductPrice]</h3>
            <h3>Product Stock : $row[ProductStock]</h3>
            <h3>Product Image: </h3>
            <br>
            <div class=\"vendor-product-image\"><img src=\"$productimages\"></div>
            <br>
            </div>
            ";
          }
          ?>
          <br>
        </div>
      </div>
    </div>
    <!-- ----------------------------------- BODY ----------------------------------- -->

    <!-- ----------------------------------- FOOTER ----------------------------------- -->
    <?php include "includes/footeremployee.html" ?>
    <!-- -----------------------------------FOOTER----------------------------------- -->
  </div>
</body>
<script src="scripts/vendor_page.js"></script>
<script src="scripts/main.js"></script>

</html>