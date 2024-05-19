<?php/*
include_once(__DIR__ .'/../templates/tpl_basic.php');
include_once (__DIR__ . '/../templates/tpl_item.php');
include_once (__DIR__ . '/../templates/tpl_profile.php');
include_once(__DIR__ . '/../utils/session.php');
include_once (__DIR__ . '/../database/item.php');
include_once (__DIR__ . '/../database/user.php');

$username = $_SESSION['username'] ?? NULL;

if (!$username) {
    header('Location: ../pages/login.php');
}

drawHeader($username);
drawWishlist($username);
drawFooter();*/
?>

<?php
include_once(__DIR__ . '/../templates/tpl_basic.php');
include_once(__DIR__ . '/../templates/tpl_item.php');
include_once(__DIR__ . '/../utils/session.php');
include_once(__DIR__ . '/../database/item.php');
include_once(__DIR__ . '/../database/user.php');

$username = $_SESSION['username'] ?? NULL;

if (!$username) {
    header('Location: ../pages/login.php');
    exit;
}

drawHeader($username);
drawWishlist($username);
drawFooter();
?>

<?php
function drawWishlist($username) { ?>
    <div class="wishlist-container">
        <div class="wishlist-card">
            <h1 class="wishlist-title">Wishlist</h1>
            <section class="wishlist-items">
                <?php
                // Retrieve items in the wishlist
                $items = getWishlist($username);

                // Check if the wishlist is empty
                if (!$items || count($items) == 0)  {
                    ?><h2 id="EmptyWishlist">Your wishlist is empty!</h2>
                    <p>Try adding your first item to your wishlist!</p>
                    <?php
                } else {
                    // Display items in the wishlist
                    foreach ($items as $item) {
                        if (!$item) continue;
                        $itemDetails = getItemById($item);
                        ?>
                        <div class="wishlist-item">
                            <img src="/../database/images/items/thumbnails_medium/<?= $itemDetails['item_pictures'] ?>.jpg" alt="<?= $itemDetails['title'] ?>" class="wishlist-item-image">
                            <div class="wishlist-item-details">
                                <h2 id="wishlist-item-title"><a href="/../pages/item.php?id=<?= $itemDetails['item_id'] ?>"><?= $itemDetails['title'] ?></a></h2>
                                <p class="wishlist-item-price">$<?= $itemDetails['price'] ?></p>
                                <div class="button-container">
                                    <a class="remove-item-button" href="/../actions/action_remove_from_wishlist.php?id=<?= $itemDetails['item_id'] ?>">Remove</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </section>
        </div>
    </div>
<?php }
