<?php
function drawPageTitle($option, $category_id = null) {
    
    $currentUser = getCurrentUser();
    $isAdmin = isset($currentUser['is_admin']) ? $currentUser['is_admin'] : false;

    if ($option == 0): ?>
        <div class="page-indicator">
            <h1>Categories</h1>
        </div>
    <?php elseif ($option == 1 && $category_id !== null): ?>
        <div class="page-indicator">
            <h1><?= getCategoryById($category_id)['name'] ?></h1>
            <?php if ($isAdmin): ?>
                <div class="separar">
                    <a href="/../actions/action_delete_category.php?id=<?= htmlspecialchars($categoryId) ?>" class="category-delete-button">Delete Category</a>
                </div>    
            <?php endif; ?>
        </div>
    <?php endif;
}

function drawCategories() {
    $currentUser = getCurrentUser();
    $isAdmin = isset($currentUser['is_admin']) ? $currentUser['is_admin'] : false;

    ?>
    <section class="background">
        <div id="categories-container">
            <?php
            // Call the function to retrieve all categories
            $categories = getCategories();

            // Loop through each category and generate a grid item
            foreach ($categories as $category) {
                echo '<div class="category">';
                echo '<img class="categories-icon" src="' . htmlspecialchars($category['icon_path']) . '" alt="' . htmlspecialchars($category['name']) . '">';
                echo '<a href="/../pages/specificCategory.php?id=' . htmlspecialchars($category['category_id']) . '">' . htmlspecialchars($category['name']) . '</a>';
                if ($isAdmin) {
                    echo '<div class="category-delete-container">';
                    echo '<a href="/../actions/action_delete_category.php?id=' . htmlspecialchars($category['category_id']) . '" class="category-delete-button">Delete Category</a>';
                    echo '</div>';
                }
                echo '</div>';
            }
            ?>
        </div>

        <?php if ($isAdmin): ?>
            <div class="add-category-container">
                <a href="/../pages/addCategory.php" class="category-delete-button">Add New Category</a>
            </div>
        <?php endif; ?>
    </section>
    <?php
}


function drawSpecificCategory($categoryId) {
    $category = getCategoryById($categoryId);
    if (!$category) {
        echo 'Category not found.';
        return;
    }

    $currentUser = getCurrentUser();
    $currency = $currentUser ? $currentUser['currency'] : 'dollar';
    $isAdmin = isset($currentUser['is_admin']) ? $currentUser['is_admin'] : false;
    ?>
    <div class="category-items-container">
        <h2 class="category-title">Items in <?= htmlspecialchars($category['name']) ?></h2>
        <div class="category-items-grid">
            <?php
            $items = getItemsByCategory($categoryId);
            if (empty($items)) {
                echo "<p>No items found in this category.</p>";
            } else {
                foreach ($items as $item) {
                    if (is_numeric($item['price'])) {
                        $convertedPrice = convertCurrency(floatval($item['price']), 'dollar', $currency);
                        $formattedPrice = formatCurrency($convertedPrice, $currency);
                    } else {
                        $formattedPrice = "N/A";
                    }
                    ?>
                    <div class="category-item">
                        <img src="/../database/images/items/thumbnails_medium/<?= htmlspecialchars($item['item_pictures']) ?>.jpg" alt="<?= htmlspecialchars($item['title']) ?>" class="category-item-image">
                        <div class="category-item-details">
                            <h3 id="category-item-title"><a href="/../pages/item.php?id=<?= htmlspecialchars($item['item_id']) ?>"><?= htmlspecialchars($item['title']) ?></a></h3>
                            <p class="category-item-price"><?= $formattedPrice ?></p>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <?php
}


function drawCategoriesAddForm(){ ?>
    <section class="background">
    <div class="add-category-form-container">
        <h2>Add New Category</h2>
        <form action="/../actions/action_add_category.php" method="post" enctype="multipart/form-data">
            <label for="name">Category Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="icon">Category Icon:</label>
            <input type="file" id="icon" name="icon" accept="image/*" required>
            
            <input type="submit" value="Add Category">
        </form>
    </div>
</section> <?php
}


