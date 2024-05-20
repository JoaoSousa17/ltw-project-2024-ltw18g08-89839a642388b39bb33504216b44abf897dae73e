<?php
function drawItemPage($item_id) {
    $item = getItemById($item_id);
    $current_user = getCurrentUser();
    $currency = $current_user ? $current_user['currency'] : 'dollar'; // Valor padrão se o usuário não estiver logado

    $convertedPrice = convertCurrency($item['price'], 'dollar', $currency);
    $formattedPrice = formatCurrency($convertedPrice, $currency);
    ?>
    <div class="item-page-container">
        <div class="item-page-card">
            <h1 class="item-title"><?= htmlspecialchars($item['title']) ?></h1>

            <?php
            $seller_username = getUsernameById($item['seller_id']);
            if ($current_user && ($seller_username == $current_user['username'] || isAdmin($current_user['username']))) {
                ?>
                <h2 class="item-seller">
                    <a href="/../pages/profile.php?id=<?= htmlspecialchars($seller_username) ?>"><?= htmlspecialchars($seller_username) ?></a>
                    <a href="/../pages/specificCategory.php?id=<?= $item['category_id'] ?>"><?= htmlspecialchars(getCategoryById($item['category_id'])['name']) ?></a>
                    <div class="buttons-align">
                        <a href="/../pages/editItem.php?id=<?= $item['item_id'] ?>" class="edit-item-link">Edit Item</a>
                        <a href="/../actions/action_delete_item.php?id=<?= $item['item_id'] ?>" class="delete-item-link">Delete Item</a>
                    </div>
                </h2>
                <?php
            } else {
                ?>
                <h2 class="item-seller">
                    <a href="/../pages/profile.php?id=<?= htmlspecialchars($seller_username) ?>"><?= htmlspecialchars($seller_username) ?></a>
                    <a href="/../pages/specificCategory.php?id=<?= $item['category_id'] ?>"><?= htmlspecialchars(getCategoryById($item['category_id'])['name']) ?></a>
                </h2>
                <?php
            }
            ?>

            <div class="item-photo-container">
                <img id="item-thumb" src="/../database/images/items/originals/<?= htmlspecialchars($item['item_pictures']) ?>.jpg" alt="<?= htmlspecialchars($item['title']) ?>" class="item-photo"><?php
                if (!($current_user && ($seller_username == $current_user['username']))){
                    ?><div class="item-actions">
                        <a href="/../actions/action_add_shopcart.php?id=<?= $item['item_id'] ?>" class="item-action-button">Buy</a>
                        <a href="/../actions/action_add_wishlist.php?id=<?= $item['item_id'] ?>" class="item-action-button">Add to Wishlist</a>
                    </div><?php
                }?>
            </div>
            <div class="item-description-container">
                <div class="item-price"><?= $formattedPrice ?></div>
                <div class="item-post-date"><?= htmlspecialchars(transData($item['post_date'])) ?></div>
                <div class="item-status"><?= htmlspecialchars($item['status']) ?></div>
                <div class="item-description"><?= htmlspecialchars($item['description']) ?></div>
            </div>
        </div>
    </div>
    <?php
}

function drawCreateItemPage($username) {
    if(!$username) header('Location: ../pages/login.php');
    ?>
    <div class="create-item-container">
        <div class="create-item-form">
            <h1 class="create-item-title">Sell Item</h1>
            <form action="/../actions/action_create_item.php" method="post" enctype="multipart/form-data">
                <div class="photo-item-group">
                    <img src="./../database/images/items/originals/not_found.jpg" alt="product-photo" id="product-photo" class="product-photo">
                    <label for="icon-update-button" class="upload-label">Upload new photo</label>
                    <input type='file' id='icon-update-button' name="product-pic" accept=".jpg">
                </div>

                <div class="form-group">
                    <label for="title">Name</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" maxlength="500" required></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" required>
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <?php
                        $categories = getCategories();
                        foreach ($categories as $category) {
                            echo '<option value="' . $category['category_id'] . '">' . $category['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="create-button">Create</button>
            </form>
        </div>
    </div>
<?php }

function drawEditItemPage($item_id, $username) {
    // Verificar se o usuário está logado
    if ($username === NULL) {
        echo "<p>You need to be logged in to edit an item.</p>";
        return;
    }

    // Obter os detalhes do item
    $item = getItemById($item_id);

    // Verificar se o item foi encontrado
    if (!$item) {
        echo "<p>Item not found.</p>";
        return;
    }

    // Verificar se o usuário logado é o vendedor do item ou um administrador
    if ($item['seller_id'] != getUserIdByUsername($username) && !isAdmin($username)) {
        echo "<p>You do not have permission to edit this item.</p>";
        return;
    }

    // Desenhar o formulário de edição do item
    ?>
    <div class="edit-item-container">
        <div class="edit-item-form">
            <h1 class="edit-item-title">Edit Item</h1>
            <form action="/actions/action_edit_item.php?id=<?= htmlspecialchars($item['item_id']) ?>" method="post" enctype="multipart/form-data">
                <div class="form-group-photo-item-group">
                    <img src="/../database/images/items/originals/<?= htmlspecialchars($item['item_pictures']) ?>.jpg" alt="product-photo" id="product-photo" class="product-photo">
                    <label for="icon-update-button" class="upload-label">Upload new photo</label>
                    <input type='file' id='icon-update-button' name="product-pic" accept=".jpg">
                </div>

                <div class="form-group">
                    <label for="title">Name</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($item['title']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" maxlength="500" required><?= htmlspecialchars($item['description']) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" value="<?= htmlspecialchars($item['price']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <?php
                        $categories = getCategories();
                        foreach ($categories as $category) {
                            echo '<option value="' . htmlspecialchars($category['category_id']) . '"' . ($category['category_id'] == $item['category_id'] ? ' selected' : '') . '>' . htmlspecialchars($category['name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="save-button">Save</button>
            </form>
        </div>
    </div>
    <?php
}

function drawSearchResults($search = '', $category = null, $priceRange = null) {
    $categories = getCategories();
    $items = getItemByTitle($search, $category, $priceRange);

    $current_user = getCurrentUser();
    $currency = $current_user ? $current_user['currency'] : 'dollar';
    ?>

    <div class="search-results-container">
        <h1 class="search-results-title">Search Results</h1>

        <form class="search-filters" method="GET" action="../actions/action_search.php">
            <div class="filter-group-wrapper">
                <div class="filter-group">
                    <label for="category">Category:</label>
                    <select name="category" id="category">
                        <option value="">All</option>
                        <?php foreach ($categories as $cat) { ?>
                            <option value="<?= htmlspecialchars($cat['category_id']) ?>" <?= ($cat['category_id'] == $category) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="price">Price:</label>
                    <select name="price" id="price">
                        <option value="">All</option>
                        <option value="0-50" <?= ($priceRange == '0-50') ? 'selected' : '' ?>>$0 - $50</option>
                        <option value="50-100" <?= ($priceRange == '50-100') ? 'selected' : '' ?>>$50 - $100</option>
                        <option value="100-200" <?= ($priceRange == '100-200') ? 'selected' : '' ?>>$100 - $200</option>
                        <option value="200+" <?= ($priceRange == '200+') ? 'selected' : '' ?>>$200+</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="filter-button">Apply Filters</button>
        </form>

        <?php if (empty($items)) { ?>
            <h2 class="no-results-message">Your search for "<span style="color: #e74c3c;"><?= htmlspecialchars($search ?? '') ?></span>" hasn't produced any result!</h2>
        <?php } else { ?>
            <section class="search-results-grid">
                <?php
                foreach ($items as $item) {
                    // Converte o preço para a moeda do usuário
                    try {
                        $convertedPrice = convertCurrency($item['price'], 'dollar', $currency);
                        $formattedPrice = formatCurrency($convertedPrice, $currency);
                    } catch (Exception $e) {
                        $formattedPrice = formatCurrency($item['price'], 'dollar'); // Se houver um erro, use o preço original em dólares
                    }
                    ?>
                    <article class="search-result-item">
                        <img src='/../database/images/items/thumbnails_medium/<?= htmlspecialchars($item['item_pictures']) ?>.jpg' alt='<?= htmlspecialchars($item['title']) ?>' class="search-result-image">
                        <div class="search-result-details">
                            <h2><a href='/../pages/item.php?id=<?= htmlspecialchars($item['item_id']) ?>'><?= htmlspecialchars($item['title']) ?></a></h2>
                            <p class="search-result-price"><?= htmlspecialchars($formattedPrice) ?></p>
                        </div>
                    </article>
                    <?php
                }
                ?>
            </section>
        <?php } ?>
    </div>
    <?php
}

