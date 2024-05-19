<?php

function drawRecipe( $transaction_id)
{
    $transaction = getTransaction($transaction_id);
    $sellers = separateSellers($transaction['seller_id']);
    $items = separateItems($transaction['item_id']);
    ?>

    <main class="recipe">
            <section class='recipe-items-container'>

        <?php foreach ($items as $item) {
            if (!$item)
                continue;
            else ?>
                <section class='recipe-items'>
                <img src='/../database/images/items/thumbnails_medium/<?= getItemById($item)['item_pictures'] ?>.jpg'
            alt='<?= getItemById($item)['title'] ?>'>

            <p><a href='/../pages/item.php?id=<?= getItemById($item)['item_id'] ?>'><?= getItemById($item)['title'] ?></a></p>
            <p>$<?= getItemById($item)['price'] ?></p>
            <p><a href='/../pages/profile.php?id=<?= getSellerByItemId($item)['username'] ?>'><?= getSellerByItemId($item)['username'] ?></a></p>
            </section>
        <?php } ?>

            </section>
                <aside class="purchase-info">
                        <h2>Recipe</h2>
                        <p>Thank you for your purchase!</p>
                        <p>Here is the recipe:</p>
                        <h2>Shipping Address</h2>
                        <p><?= $transaction['address'] ?></p>


                    <h2>Total Price</h2>
                    <p>$<?= $transaction['total_price'] ?></p>
                </aside>

    </main>
    <h2><a class="continue-shop" href="/../pages/mainPage.php">Continue Shopping!</a></h2>
    <?php
}

function drawHistory($username)
{ ?>
<main id="history">

</main>

<?php
}