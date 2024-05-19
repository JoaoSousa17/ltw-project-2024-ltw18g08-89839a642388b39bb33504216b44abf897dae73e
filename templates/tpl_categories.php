<?php
function drawPageTitle($option, $category_id = null) { 
    if ($option == 0): ?>
        <div class="page-indicator">
            <h1>Categories</h1>
        </div>
    <?php elseif ($option == 1 && $category_id !== null): ?>
        <div class="page-indicator">
            <h1><?= getCategoryById($category_id)['name'] ?></h1>
        </div>
    <?php endif;
}

function drawCategories() {
    ?>
    <section class="background">
        <div id="categories-container">
            <?php
            // Call the function to retrieve all categories
            $categories = getCategories();

            // Loop through each category and generate a grid item
            foreach ($categories as $category) {
                echo '<div class="category">';
                echo '<img class="categories-icon" src="' . $category['icon_path'] . '" alt="' . $category['name'] . '">';
                echo '<a href="/../pages/specificCategory.php?id=' . $category['category_id'] . '">' . $category['name'] . '</a>';
                echo '</div>';
            }
            ?>
        </div>
    </section>
    <?php
}

function drawSpecificCategory($category_id) { ?>
    
    <div class="category-items-container">
        <section class="category-items-grid">
            <?php
            // Fetch items from the database using the getItemsByCategory function
            $items = getItemsByCategory($category_id);
            if (empty($items)) {
                echo "<p>No items found in this category.</p>";
            } else {
                foreach ($items as $item) {
                    ?>
                    <div class="category-item">
                        <img src="/../database/images/items/thumbnails_medium/<?= $item['item_pictures'] ?>.jpg" alt="<?= $item['title'] ?>" class="category-item-image">
                        <div class="category-item-details">
                            <h2 id="category-item-title"><a href="/../pages/item.php?id=<?= $item['item_id'] ?>"><?= $item['title'] ?></a></h2>
                            <p class="category-item-price">$<?= $item['price'] ?></p>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </section>
    </div>
<?php }
