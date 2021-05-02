<?php
include "includes/vendor_session.php";
include("includes/database.php");
$vendorid = $_SESSION["vendorid"]; // USE SESSIONS
$rows = mysqlidb::fetchAllRows("SELECT * FROM productorder
INNER JOIN orderitem ON orderitem.OrderId = productorder.OrderId
INNER JOIN product ON product.ProductId = orderitem.ProductId
INNER JOIN user ON user.UserId = productorder.UserId 
WHERE (OrderStatus = 'Shipped' OR OrderStatus = 'To Ship')
AND ProductStatus = 'Approved'
AND VendorId = $vendorid
GROUP BY orderitem.OrderId
ORDER BY PurchaseDate Desc");
$receivedrow = mysqlidb::fetchRow("SELECT SUM(Total.OrderTotal) as Total FROM (SELECT productorder.OrderId, user.Username, productorder.PurchaseDate, productorder.OrderTotal FROM productorder
INNER JOIN orderitem ON orderitem.OrderId = productorder.OrderId
INNER JOIN product ON  product.ProductId = orderitem.ProductId
INNER JOIN user ON user.UserId = productorder.UserId
WHERE (OrderStatus = 'Shipped' OR OrderStatus = 'To Ship')
AND ProductStatus = 'Approved'
AND product.VendorId = $vendorid
GROUP BY productorder.OrderId) as Total");
$balancerow = mysqlidb::fetchRow("SELECT * FROM vendor
WHERE VendorId = $vendorid");
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
          <h3>Income</h3>
          <hr><br>

          <table style="width: 80%; float: left;">
            <tr>
              <th width="15%">Order ID</th>
              <th width="25%">Customer</th>
              <th width="25%">Purchase Date</th>
              <th width="15%">Amount Paid</th>
            </tr>
            <?php
            if (count($rows) > 0) {
              foreach ($rows as $row) {
                echo "
                <tr>
                  <td>{$row['OrderId']}</td>
                  <td>{$row['Username']}</td>
                  <td>{$row['PurchaseDate']}</td>
                  <td>RM " . number_format($row['OrderTotal'], 2) . "</td>
                </tr>";
              }
            } else {
              echo "<tr><td colspan=\"4\"><h2 class=\"cultured-dark\">There are no orders.</h2></td></tr>";
            }

            ?>
          </table>

          <?php
          echo "<div class=\"total\" style=\"float: right\">
          <h3>Total Received</h3>
          <h4>RM " . number_format($receivedrow['Total'], 2) . "</h4>
          <br>
          <h3>Total Balance</h3>
          <h4>RM " . number_format($balancerow['Balance'], 2) . "</h4>
          <br>
          <a href=\"vendor_withdraw.php\" class=\"btn-primary p-2 m-1 visited-white\">Withdraw</a>
          </div>
          ";
          ?>
        </div>
      </div>
    </div>
    <br><br>
    <!-- ----------------------------------- BODY ----------------------------------- -->

    <!-- ----------------------------------- FOOTER ----------------------------------- -->
    <?php include "includes/footeremployee.html" ?>
    <!-- -----------------------------------FOOTER----------------------------------- -->
  </div>
</body>
<script src="scripts/vendor_page.js"></script>

</html>