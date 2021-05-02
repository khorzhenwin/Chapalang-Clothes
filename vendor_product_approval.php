<?php
include "includes/vendor_session.php";
include("includes/database.php");
$vendorid = $_SESSION["vendorid"]; // USE SESSIONS
$approvprodrows = mysqlidb::fetchAllRows("SELECT * FROM product
WHERE ProductStatus = 'Approved'
AND VendorId = $vendorid");
$pendingprodrows = mysqlidb::fetchAllRows("SELECT * FROM product
WHERE ProductStatus = 'Pending'
AND VendorId = $vendorid");
$rejectedprodrows = mysqlidb::fetchAllRows("SELECT * FROM product
WHERE ProductStatus = 'Denied'
AND VendorId = $vendorid");
$blacklistprodrows = mysqlidb::fetchAllRows("SELECT * FROM product
WHERE ProductStatus = 'Blacklisted'
AND VendorId = $vendorid");
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
          <h3>Approved Products</h3>
          <hr><br>
          <div class="approvalimgs">
            <?php
            if (count($approvprodrows) > 0) {
              foreach ($approvprodrows as $row) {
                $productimages = explode(",", $row['ProductImage'])[0];
                echo "<a class=\"item-card\" href=\"vendor_product_page.php?productid={$row['ProductId']}\">
                <img class=\"item-image\" src=\"$productimages\">
                <div class=\"item-name\">
                    $row[ProductName]
                </div>
              </a>
              ";
              }
            } else {
              echo "<h3 class=\"cultured-dark\">You have no approved products.</h3>";
            }
            ?>
          </div>

          <br>

          <h3>Pending Approval</h3>
          <hr><br>
          <div class="approvalimgs">
            <?php
            if (count($pendingprodrows) > 0) {
              foreach ($pendingprodrows as $row) {
                $productimages = explode(",", $row['ProductImage'])[0];
                echo "<a class=\"item-card\" href=\"vendor_product_page.php?productid={$row['ProductId']}\">
                  <img class=\"item-image\" src=\"$productimages\">
                  <div class=\"item-name\">
                      $row[ProductName]
                  </div>
                </a>
                ";
              }
            } else {
              echo "<h3 class=\"cultured-dark\">You have no pending products.</h3>";
            }
            ?>
          </div>

          <br>

          <h3>Rejected Products</h3>
          <hr><br>
          <div class="approvalimgs">
            <?php
            if (count($rejectedprodrows) > 0) {
              foreach ($rejectedprodrows as $row) {
                $productimages = explode(",", $row['ProductImage'])[0];
                echo "<a class=\"item-card\" href=\"vendor_product_page.php?productid={$row['ProductId']}\">
                        <img class=\"item-image\" src=\"$productimages\">
                        <div class=\"item-name\">
                            $row[ProductName]
                        </div>
                      </a>
                      ";
              }
            } else {
              echo "<h3 class=\"cultured-dark\">You have no rejected products.</h3>";
            }

            ?>
          </div>

          <br>

          <h3>Blacklisted Products</h3>
          <hr><br>
          <div class="approvalimgs">
            <?php
            if (count($blacklistprodrows) > 0) {
              foreach ($blacklistprodrows as $row) {
                $productimages = explode(",", $row['ProductImage'])[0];
                echo "<a class=\"item-card\" href=\"vendor_product_page.php?productid={$row['ProductId']}\">
                  <img class=\"item-image\" src=\"$productimages\">
                  <div class=\"item-name\">
                      $row[ProductName]
                  </div>
                </a>
                ";
              }
            } else {
              echo "<h3 class=\"cultured-dark\">You have no blacklisted products.</h3>";
            }

            ?>
          </div>
          <br><br><br>
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

</html>