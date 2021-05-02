<?php
if (!isset($_GET['id'])) {
  header("location: error.php?error=bad_item");
}

include_once 'includes/database.php';
$productId = mysqlidb::escape($_GET['id']);
$item = mysqlidb::fetchRow(
  "SELECT product.*,IFNULL(AVG(review.ReviewRating),0) as ProductRating,
  vendor.ShopName,vendor.Description,vendor.VendorImage,
  IFNULL(SUM(orderitem.ProductQuantity),0) as TotalSold 
  FROM product 
  LEFT JOIN vendor ON product.VendorId = vendor.VendorId 
  LEFT JOIN review ON review.ProductId = product.ProductId 
  LEFT JOIN orderitem ON orderitem.ProductId = product.ProductId 
  WHERE product.ProductId = $productId"
);

if ($item['ProductStatus'] != "Approved") {
  header("location: error.php?error=bad_item");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <script src="scripts/jquery-3.5.1.min.js"></script>
  <meta charset="UTF-8" />
  <link rel="shortcut icon" href="images/shirtlogo.png" type="image/x-icon">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="styles/style.css" />
  <title><?php echo $item['ProductName']; ?> - Chapalang Clothes</title>
</head>

<body>
  <!-- ----------------------------------- TOP-BAR ----------------------------------- -->
  <?php include "includes/storetopbar.php"; ?>
  <!-- ----------------------------------- TOP-BAR ----------------------------------- -->

  <!-- ----------------------------------- MODAL ----------------------------------- -->
  <div class="modal-background modal hidden">
    <div class="modal-content center-in-page-abs product-modal">
      <div onclick="DismissModal()" class="material-icons modal-close-button white">close</div>
      <div class="modal-body">
        <div class="flex flex-justify-center flex-align-center">
          <div class="material-icons md-48 unselectable ruby">info</div>
          <div class="modal-text" id="modal-text">Item has been added to cart!</div>
        </div>
        <div class="btn-primary w-3 m-0-a" onclick="DismissModal();">Ok</div>
      </div>
    </div>
  </div>
  <!-- ----------------------------------- MODAL ----------------------------------- -->

  <div class="body-wrapper">
    <div></div>
    <!-- ----------------------------------- BODY ----------------------------------- -->
    <div class="content-wrapper container">
      <div class="product-container bg-white">
        <div class="product-image p-2">
          <?php
          $productImages = explode(",", $item['ProductImage']);
          ?>
          <img src="<?php echo $productImages[0]; ?>" alt="">
          <div class="product-image-list">
            <?php
            foreach ($productImages as $image) {
              echo "<div class=\"product-image-small\">
                      <img src=\"{$image}\" alt=\"\">
                    </div>";
            }

            ?>
          </div>
        </div>
        <div class="product-details p-2">
          <div class="product-name">
            <?php echo $item['ProductName']; ?>
          </div>
          <div class="flex flex-align-center">
            <div class="product-rating ruby"><?php echo number_format($item['ProductRating'], 1); ?>
              <?php
              $fullStars = intval($item['ProductRating']);
              $halfStars = ($item['ProductRating'] - $fullStars > 0 ? 1 : 0);
              $emptyStars = 5 - ($fullStars + $halfStars);

              for ($i = 0; $i < $fullStars; $i++) {
                echo '<span class="material-icons md-24 unselectable">star</span>';
              }
              for ($i = 0; $i < $halfStars; $i++) {
                echo '<span class="material-icons md-24 unselectable">star_half</span>';
              }
              for ($i = 0; $i < $emptyStars; $i++) {
                echo '<span class="material-icons md-24 unselectable">star_outline</span>';
              }
              ?>
            </div>
            <div class="product-total-sold cultured-dark"><?php echo number_format($item['TotalSold'], 0); ?> Sold</div>
          </div>
          <div class="product-price bg-cultured-light ruby">RM <?php echo number_format($item['ProductPrice'], 2); ?></div>
          <div class="product-quantity-wrapper unselectable">
            <div class="product-quantity-text cultured-dark">Quantity</div>
            <div class="product-quantity-minus material-icons md-18 cultured-dark btn-inset" onclick="ProductQuantity(-1)">remove</div>
            <div id="product-quantity" class="product-quantity cultured-dark">1</div>
            <div class="product-quantity-add material-icons md-18 cultured-dark btn-inset" onclick="ProductQuantity(1)">add</div>
            <div class="product-quantity-instock cultured-dark"><span id="product-instock"><?php echo $item['ProductStock']; ?></span> in stock</div>
          </div>
          <div onclick="<?php echo isset($_SESSION["userid"]) ? "AddToCart(" . $item['ProductId'] . ")" : "location.href='loginpage.php'" ?>" class="product-addtocart w-3 flex btn-inset">
            <span class="material-icons md-24 ruby unselectable">add_shopping_cart</span>
            <div class="product-atc-text unselectable">Add To Cart</div>
          </div>
          <div class="product-description-wrapper">
            <div class="product-description-header cultured-dark">Product Description</div>
            <div class="product-description-body"><?php echo $item['ProductDescription']; ?></div>
          </div>
        </div>
      </div>
      <div class="product-information bg-white p-2">
        <img src="<?php echo $item['VendorImage']; ?>" class="product-information-vendor-image" alt="">
        <div class="product-information-body">
          <div class="information-header">About <span class="ruby"><?php echo $item['ShopName']; ?></span></div>
          <div class="information-body"><?php echo $item['Description']; ?></div>
        </div>
      </div>
      <div class="product-information-reviews bg-white p-2">
        <div class="information-header flex flex-align-baseline">
          Product Reviews
          <div class="product-review-add ruby" id="add-review">Add Review</div>
        </div>
        <?php
        if (isset($_SESSION["userid"])) {
          $userId = $_SESSION["userid"];

          $reviewButtonText = "Review";
          $reviewFormAction = "review.php";
          $prevReviewRating = 0;
          $prevReviewTitle = "";
          $prevReviewBody = "";
          if (mysqlidb::checkRecordExists("SELECT * FROM productorder INNER JOIN orderitem ON orderitem.OrderId = productorder.OrderId WHERE productorder.UserId = $userId AND orderitem.ProductId = $productId")) {
            if ($review = mysqlidb::fetchRow("SELECT * FROM review WHERE UserId=$userId AND ProductId=$productId")) {
              $reviewButtonText = "Update Review";
              $reviewFormAction = "update_review.php";
              $prevReviewRating = intval($review["ReviewRating"]);
              $prevReviewTitle = $review["ReviewTitle"];
              $prevReviewBody = $review["ReviewComment"];
            }
            echo '<form class="no-overflow h-0" id="product-review-form" action="' . $reviewFormAction . '" method="POST">
              <div class="form-input-group">
                <label class="block" for="review-rating">Rating</label>
                <input type="hidden" name="review-rating" id="review-rating" value="' . $prevReviewRating . '">
                <input type="hidden" name="productid" id="productid" value="' . $item['ProductId'] . '">
                <div class="flex review-rating-stars">
                  <span class="material-icons md-36 unselectable">star_outline</span>
                  <span class="material-icons md-36 unselectable">star_outline</span>
                  <span class="material-icons md-36 unselectable">star_outline</span>
                  <span class="material-icons md-36 unselectable">star_outline</span>
                  <span class="material-icons md-36 unselectable">star_outline</span>
                </div>
              </div>
              <div class="form-input-group">
                <label for="review-title">Title</label>
                <input type="text" maxlength="120" name="review-title" id="review-title" value="' . $prevReviewTitle . '">
              </div>
              <div class="form-input-group">
                <label for="review-body">Review</label>
                <textarea maxlength="240" class="resize-none" name="review-body" id="review-body">' . $prevReviewBody . '</textarea>
              </div>
              <div class="form-input-group text-center">
              <input class="w-2" type="submit" value="' . $reviewButtonText . '">
            </div>
            </form>';
          } else {
            echo '<div id="product-review-form" class="no-overflow h-0">
                    <h1 class="text-center cultured-dark m-0 center-in-page-rel">You cannot review items you have not purchased</h1>
                  </div>';
          }
        } else {
          echo '<div class="h-0 no-overflow text-center cultured-dark" id="product-review-form">
                  <h2>You need to <a href="loginpage.php" class="vis-ruby ruby">Login</a> to post reviews!</h2>
                </div>';
        }

        $reviews = mysqlidb::fetchAllRows("SELECT review.*,user.UserImage,user.Username FROM `review` INNER JOIN user on review.UserId = user.UserId WHERE ProductId=$productId ORDER BY `review`.`ReviewDate` DESC LIMIT 25 ");

        if (count($reviews) > 0) {
          foreach ($reviews as $review) {

            echo '<div class="product-review">
                  <div class="product-review-information">
                  <div class="product-review-user"><img class="product-review-user-image" src="' . $review["UserImage"] . '" alt=""> <span class="product-review-user-name ruby">' . $review["Username"] . '</span></div>
                  <div class="product-review-title">' . $review["ReviewTitle"] . '</div>
                  <div class="product-review-rating flex">';

            $fullStars = intval($review['ReviewRating']);
            $emptyStars = 5 - $fullStars;
            for ($i = 0; $i < $fullStars; $i++) {
              echo '<span class="material-icons md-24 unselectable">star</span>';
            }
            for ($i = 0; $i < $emptyStars; $i++) {
              echo '<span class="material-icons md-24 unselectable">star_outline</span>';
            }

            echo '</div>
                  </div>
                  <div class="product-review-comment">' . $review["ReviewComment"] . '</div>
                  <div class="product-review-date cultured-dark">' . $review["ReviewDate"] . '</div>
                  </div>';
          }
        } else {
          echo '<h2 class="cultured-dark text-center">There are no reviews for this product.</h2>';
        }
        ?>

        <!-- <div class="product-review">
          <div class="product-review-information">
            <div class="product-review-title">best ever</div>
            <div class="product-review-rating flex">
              <span class="material-icons md-24 unselectable">star</span>
              <span class="material-icons md-24 unselectable">star</span>
              <span class="material-icons md-24 unselectable">star</span>
              <span class="material-icons md-24 unselectable">star</span>
              <span class="material-icons md-24 unselectable">star</span>
            </div>
            <div class="product-review-user">by <img class="product-review-user-image" src="images/users/user.jpg" alt=""> <span class="ruby">usernamehere123</span></div>
          </div>
          <div class="product-review-comment">Lorem ipsum dolor sit amet consectetur adipisicing elit. Alias et ea ullam totam corporis maiores labore saepe necessitatibus delectus officiis optio recusandae aut quidem dolore quibusdam excepturi, nisi veritatis quos.</div>
        </div> -->

      </div>
    </div>
  </div>

  <!-- ----------------------------------- BODY ----------------------------------- -->

  <!-- ----------------------------------- FOOTER ----------------------------------- -->
  <?php include "includes/footer.php" ?>
  <!-- -----------------------------------FOOTER----------------------------------- -->
  </div>
</body>
<script src="scripts/main.js"></script>
<?php include "includes/store_scripts.php"; ?>
<script src="scripts/item_page.js"></script>

</html>