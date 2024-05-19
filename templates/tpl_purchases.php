<?php
function drawShopCart($username) {
    $shopcartItems = getShopCart($username);
    $current_user = getUser($username);
    $currency = $current_user['currency'] ?? 'dollar'; // Moeda padrão se não estiver definida
    ?>
    <div class="shopping-cart-container">
        <div class="shopping-cart-card">
            <h1 class="shopping-cart-title">Shopping Cart</h1>
            <section class="shopping-cart-items">
                <?php
                if (!$shopcartItems || count($shopcartItems) == 0) {
                    ?>
                    <h2 id="EmptyWishlist">Your shopping cart is empty!</h2>
                    <p>Try adding your first item to your shopping cart!</p>
                    <?php
                } else {
                    $totalPrice = 0;
                    $totalShipping = 0;
                    $buyerLocation = getUserLocation($username);

                    foreach ($shopcartItems as $itemId) {
                        $itemDetails = getItemById($itemId);
                        if (!$itemDetails) continue;

                        $sellerLocation = getUserLocation(getUsernameById($itemDetails['seller_id']));
                        $shippingCost = calculateShippingCost($sellerLocation, $buyerLocation);

                        // Converter os preços e os custos de envio
                        $convertedPrice = convertCurrency($itemDetails['price'], 'dollar', $currency);
                        $formattedPrice = formatCurrency($convertedPrice, $currency);

                        $convertedShippingCost = convertCurrency($shippingCost, 'dollar', $currency);
                        $formattedShippingCost = formatCurrency($convertedShippingCost, $currency);

                        $totalPrice += $convertedPrice;
                        $totalShipping += $convertedShippingCost;
                        ?>
                        <div class="shopping-cart-item">
                            <img src="/../database/images/items/thumbnails_medium/<?= htmlspecialchars($itemDetails['item_pictures']) ?>.jpg" alt="<?= htmlspecialchars($itemDetails['title']) ?>" class="shopping-cart-item-image">
                            <div class="shopping-cart-item-details">
                                <h2><a href="/../pages/item.php?id=<?= htmlspecialchars($itemDetails['item_id']) ?>"><?= htmlspecialchars($itemDetails['title']) ?></a></h2>
                                <p class="shopping-cart-item-price"><?= $formattedPrice ?></p>
                                <p class="shopping-cart-item-shipping">Shipping: <?= $formattedShippingCost ?></p>
                                <div class="button-container">
                                    <a class="remove-item-button" href="/../actions/action_remove_from_shopcart.php?id=<?= htmlspecialchars($itemDetails['item_id']) ?>">Remove</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    $grandTotal = $totalPrice + $totalShipping;
                    $formattedTotalPrice = formatCurrency($totalPrice, $currency);
                    $formattedTotalShipping = formatCurrency($totalShipping, $currency);
                    $formattedGrandTotal = formatCurrency($grandTotal, $currency);
                    ?>
                    <div class="shopping-cart-total">
                        Subtotal: <?= $formattedTotalPrice ?><br>
                        Shipping: <?= $formattedTotalShipping ?><br>
                        Total: <?= $formattedGrandTotal ?>
                    </div>
                    <div class="shopping-cart-checkout">
                        <a id="checkout-button" href="/../pages/payment.php">Checkout</a>
                    </div>
                    <?php
                }
                ?>
            </section>
        </div>
    </div>
    <?php
}


function drawPayment() { ?>
    <div class="payment-container">
        <div class="payment-form">
            <h1 class="payment-title">Payment</h1>
            <form action="/../actions/action_checkout.php" method="post">
                <div class="form-group">
                    <label for="card-number">Card Number</label>
                    <input type="text" id="card-number" name="card-number" required>
                </div>

                <div class="form-group">
                    <label for="expiration-date">Expiration Date</label>
                    <input type="text" id="expiration-date" name="expiration-date" required>
                </div>

                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" required>
                </div>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" required>
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" required>
                </div>

                <div class="form-group">
                    <label for="zip-code">Zip Code</label>
                    <input type="text" id="zip-code" name="zip-code" required>
                </div>

                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" required>
                </div>

                <button type="submit" class="pay-button">Pay</button>
            </form>
        </div>
    </div>
<?php }
?>