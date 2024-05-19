<?php
function drawItemsOnSell($username) {
    
    if (!$username) {
        echo 'Erro: Usuário não especificado.';
        exit;
    }

    $user_id = getUserIdByUsername($username);

    // Função que obtém os itens à venda por usuário
    $items = getItemsByUserId($user_id);

    // Log para depuração
    error_log('Items: ' . print_r($items, true));
    // Obter a moeda preferida do usuário
    $current_user = getCurrentUser();
    if ($current_user === null) {
        $currency = 'dollar'; // Valor padrão se o usuário não estiver logado
    } else {
        $currency = $current_user['currency'];
    }
    ?>

    <div class="items-on-sell-container">
        <h1>Items on Sale by <a href="/../pages/profile.php?id=<?= htmlspecialchars($username) ?>"><?= htmlspecialchars($username) ?></a></h1>
        <?php if (empty($items)): ?>
            <p>No items for sale.</p>
        <?php else: ?>
            <div class="items-grid">
                <?php foreach ($items as $item): 
                    $convertedPrice = convertCurrency($item['price'], 'dollar', $currency);
                    $formattedPrice = formatCurrency($convertedPrice, $currency);
                    ?>
                    <div class="item-card">
                        <img src="/../database/images/items/thumbnails_medium/<?= htmlspecialchars($item['item_pictures']) ?>.jpg" alt="<?= htmlspecialchars($item['title']) ?>" class="item-image">
                        <h2><a href="/../pages/item.php?id=<?= htmlspecialchars($item['item_id']) ?>"><?= htmlspecialchars($item['title']) ?></a></h2>
                        <p class="item-price"><?= $formattedPrice ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php
}

function drawBoughtItems($username) {
    $user = getUser($username);
    if (!$user) {
        echo 'User not found.';
        return;
    }

    $user_id = $user['user_id'];
    $transactions = getBoughtItems($user_id);

    ?>
    <div class="bought-items-container">
        <div class="bought-items-card">
            <h1 class="bought-items-title">Purchased Items</h1>
            <section class="bought-items-list">
                <?php
                if (empty($transactions)) {
                    ?>
                    <h2 class="NoPurchases">You haven't purchased any items yet.</h2>
                    <h3 class= "NoPurchases">Explore our marketplace and find items to purchase!</h3>
                    <?php
                } else {
                    foreach ($transactions as $transaction) {
                        $items = separateItems($transaction['item_id']);
                        foreach ($items as $item_id) {
                            $item = getItemById($item_id);
                            ?>
                            <div class="bought-item">
                                <img src="/../database/images/items/thumbnails_medium/<?= htmlspecialchars($item['item_pictures']) ?>.jpg" alt="<?= htmlspecialchars($item['title']) ?>" class="bought-item-image">
                                <div class="bought-item-details">
                                    <h2><a href="/../pages/item.php?id=<?= htmlspecialchars($item['item_id']) ?>"><?= htmlspecialchars($item['title']) ?></a></h2>
                                    <p class="bought-item-price">$<?= htmlspecialchars($item['price']) ?></p>
                                    <p class="bought-item-transaction-date">Purchased on: <?= htmlspecialchars(transData($transaction['transaction_date'])) ?></p>
                                </div>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
            </section>
        </div>
    </div>
    <?php
}

function drawSoldItems($username) {
    $user = getUser($username);
    if (!$user) {
        echo 'User not found.';
        return;
    }

    $soldItems = getSoldItems($user['user_id']);
    ?>

    <div class="sold-items-container">
        <div class="sold-items-card">
            <h1 class="sold-items-title">Sold Items</h1>
            <section class="sold-items-list">
                <?php if (empty($soldItems)): ?>
                    <h2>You haven't sold any items yet!</h2>
                <?php else: ?>
                    <?php foreach ($soldItems as $transaction): ?>
                        <?php
                        $items = separateItems($transaction['item_id']);
                        foreach ($items as $item_id):
                            $itemDetails = getItemById($item_id);
                            if (!$itemDetails) continue;
                            ?>
                            <div class="sold-item">
                                <img src="/../database/images/items/thumbnails_medium/<?= htmlspecialchars($itemDetails['item_pictures']) ?>.jpg" alt="<?= htmlspecialchars($itemDetails['title']) ?>" class="sold-item-image">
                                <div class="sold-item-details">
                                    <h2><a href="/../pages/item.php?id=<?= htmlspecialchars($itemDetails['item_id']) ?>"><?= htmlspecialchars($itemDetails['title']) ?></a></h2>
                                    <p><span class= "bold">Sold to: </span><?= htmlspecialchars($transaction['name']) ?></p>
                                    <p><span class= "bold">Shipping Address: </span><?= htmlspecialchars($transaction['address']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </div>
    </div>
    <?php
}
?>
