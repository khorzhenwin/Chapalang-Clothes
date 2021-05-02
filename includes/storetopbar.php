<div class="top-bar">
  <div class="top-bar-wrapper container">
    <div class="top-bar-elements">
      <div class="top-bar-text">
        <!-- <a class="m-0">Seller Dashboard</a> -->
        <a href="#" class="m-0">Contact Us</a>
      </div>
      <div class="top-bar-text">
        <?php
        include "includes/startsession.php";
        if (isset($_SESSION["userid"])) {
          $userId = $_SESSION["userid"];
          include_once "includes/database.php";
          $user = mysqlidb::fetchRow("SELECT * FROM user WHERE UserId=$userId");
          echo '<div class="top-bar-username">
                  <div class="flex flex-align-center">
                    <img src="' . $user['UserImage'] . '" alt="" class="top-bar-username-image">
                    <a href="user_profile.php">' . $user['Username'] . '</a>
                  </div>
                  <div class="top-bar-username-dropdown invisible">
                  <div class="top-bar-username-dropdown-body bg-white">
                      <a class="black vis-black" href="user_profile.php">
                        <div class="top-bar-username-menu-item"><span class="material-icons unselectable">person</span>My Profile</div>
                      </a>
                      <a class="black vis-black" href="purchase_history.php">
                        <div class="top-bar-username-menu-item"><span class="material-icons unselectable">shopping_basket</span>My Purchases</div>
                      </a>
                      <a class="black vis-black" href="logout.php">
                        <div class="top-bar-username-menu-item"><span class="material-icons unselectable">exit_to_app</span>Logout</div>
                      </a>
                    </div>
                  </div>
                </div>';
        } else {
          echo "<div class=\"top-bar-username\"><a class=\"m-0\" href=\"loginpage.php\">Login</a></div>";
        }
        ?>

      </div>
    </div>
    <div class="top-bar-header">
      <a class="top-bar-logo-wrapper" href="index.php"><img class="chapalang-logo" src="images/logo.png" alt="" /></a>
      <div class="top-bar-search">
        <div class="search-bar-input">
          <input class="top-bar-search-bar" id="search-bar" maxlength="128" type="text" autocomplete="off" placeholder="Pimp Clothes" />
          <button class="bg-ruby top-bar-search-button btn-inset" id="search-bar-button" onclick="Search()">
            <span class="material-icons md-24">search</span>
          </button>
        </div>
        <div class="top-bar-suggestions">
          <a href="search.php?q=men">Men's</a><a href="search.php?q=women">Women's</a><a href="search.php?q=kid">Kids'</a><a href="search.php?q=shirt">Shirts</a><a href="search.php?q=pants">Pants</a><a href="search.php?q=shoe">Shoes</a><a href="search.php?q=bag">Bags</a><a href="search.php?q=sneakers">Sneakers</a><a href="search.php?q=formal">Formal</a><a href="search.php?q=informal">Informal</a><a href="search.php?q=sunglass">Sunglasses</a>
        </div>
      </div>
      <div class="top-bar-shopping-cart">
        <a class="top-bar-shopping-cart-icon material-icons md-36 unselectable" href="cart.php">
          shopping_cart
        </a>
        <div id="shopping-cart-popup" class="shopping-cart-popup invisible bg-white">
          <div class="shopping-cart-popup-arrow-up"></div>
          <?php include_once "get_cart.php"; ?>
        </div>
      </div>
    </div>
  </div>
</div>