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
                    <h2 id="NoPurchases">You haven't purchased any items yet.</h2>
                    <p>Explore our marketplace and find items to purchase!</p>
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
?>
