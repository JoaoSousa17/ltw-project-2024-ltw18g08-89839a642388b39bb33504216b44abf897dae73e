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
?>