<?php

function drawRecipe($transaction_id)
{
    $transaction = getTransaction($transaction_id);
    if (!$transaction) {
        echo 'Transaction not found.';
        return;
    }

    $currentUser = getCurrentUser();
    $currency = $currentUser ? $currentUser['currency'] : 'dollar';

    $sellers = separateSellers($transaction['seller_id']);
    if (!$sellers) {
        echo 'No sellers found.';
        return;
    }

    $items = separateItems($transaction['item_id']);
    if (!$items) {
        echo 'No items found.';
        return;
    }
    ?>

    <div class="recipe-container">
        <div class="recipe-card">
            <section class="recipe-items-container">
                <?php foreach ($items as $item): 
                    $itemDetails = getItemById($item);
                    if (!$itemDetails) continue;
                    $sellerDetails = getSellerByItemId($item);
                    if (is_numeric($itemDetails['price'])) {
                        $convertedPrice = convertCurrency(floatval($itemDetails['price']), 'dollar', $currency);
                        $formattedPrice = formatCurrency($convertedPrice, $currency);
                    } else {
                        $formattedPrice = "N/A";
                    }
                    ?>
                    <div class="recipe-item">
                        <img src='/../database/images/items/thumbnails_medium/<?= htmlspecialchars($itemDetails['item_pictures']) ?>.jpg' alt='<?= htmlspecialchars($itemDetails['title']) ?>' class="recipe-item-image">
                        <div class="recipe-item-details">
                            <p class="recipe-item-title"><a href='/../pages/item.php?id=<?= htmlspecialchars($itemDetails['item_id']) ?>'><?= htmlspecialchars($itemDetails['title']) ?></a></p>
                            <p class="recipe-item-price"><?= $formattedPrice ?></p>
                            <p class="recipe-item-seller"><a href='/../pages/profile.php?id=<?= htmlspecialchars($sellerDetails['username']) ?>'><?= htmlspecialchars($sellerDetails['username']) ?></a></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
            <aside class="purchase-info">
                <h2>Recipe</h2>
                <p>Thank you for your purchase!</p>
                <p>Here is the recipe:</p>
                <h2>Shipping Address</h2>
                <p><?= htmlspecialchars($transaction['address']) ?></p>
                <h2>Total Price</h2>
                <p>$<?= htmlspecialchars($transaction['total_price']) ?></p>
            </aside>
        </div>
        <h2><a class="continue-shop" href="/../pages/mainPage.php">Continue Shopping!</a></h2>
    </div>
    <?php
}



function drawHistory($username)
{ ?>
<main id="history">

</main>

<?php
}