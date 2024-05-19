<?php
function drawMainPageBanner(){ ?>
    <div class="banner">
        <img src="../images/Banner_2.png" alt="Banner" class="banner-image">
    </div>
<?php
}

function drawMainPageArticlesSection() {
    $current_user = getCurrentUser();
    $currency = $current_user ? $current_user['currency'] : 'dollar';

    ?>
    <div class="articles-section">
        <article class="product-grid">
            <?php 
            $items = getItems(15); // Fetching 15 items, change the parameter as needed
            foreach ($items as $item) {
                // Verifique se o preço é numérico antes de converter
                if (is_numeric($item['price'])) {
                    $convertedPrice = convertCurrency(floatval($item['price']), 'dollar', $currency);
                    $formattedPrice = formatCurrency($convertedPrice, $currency);
                } else {
                    $formattedPrice = "N/A"; // Ou outro tratamento adequado para preços não numéricos
                }
                ?>
                <section class="product-container">
                    <img class="product-view" src="/../database/images/items/thumbnails_medium/<?= htmlspecialchars($item['item_pictures']) ?>.jpg" alt="<?= htmlspecialchars($item['title']) ?>">
                    <div class="product-info">
                        <h3><a href="/../pages/item.php?id=<?= $item['item_id'] ?>"><?= htmlspecialchars($item['title']) ?></a></h3>
                        <p><?= $formattedPrice ?></p>
                    </div>
                </section>
            <?php } ?>
        </article>
    </div>
    <?php
}

function drawSearchBar($current_search) {
    if(!isset($current_search))
        $current_search = "";
    ?>
    <div class="search-bar">
        <form action="/../actions/action_search.php" method="get">
            <input type="text" name="search" placeholder="What are you looking for?" value="<?= $current_search ?>">
            <button type="submit">Search</button>
        </form>
    </div>
    <?php
}
?>