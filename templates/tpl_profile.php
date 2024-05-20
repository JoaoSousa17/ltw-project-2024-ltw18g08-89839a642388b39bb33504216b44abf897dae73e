<?php
function drawProfilePage($profileUser, $loggedUser) {
    // Obtenha os dados do perfil
    $profile = getUser($profileUser);

    // Verifica se o perfil foi encontrado
    if (!$profile) {
        echo 'Erro: Usuário não encontrado.';
        return;
    }

    // Determina qual perfil desenhar com base no usuário logado
    $isCurrentUser = $profileUser == $loggedUser;
    $currentUser = getCurrentUser();
    $isAdmin = $currentUser ? $currentUser['is_admin'] : false;

    $editProfileLink = $isCurrentUser ? '<a href="/../pages/editProfile.php" class="profile-action-button">Edit Profile</a>' : '';
    $createItemLink = $isCurrentUser ? '<a href="/../pages/createItem.php" class="profile-action-button">Create Item</a>' : '';
    $deleteAccountLink = $isCurrentUser ? '<a href="/../actions/action_delete_account.php" class="profile-action-button">Delete Account</a>' : '';
    $viewItemsLink = !$isCurrentUser ? '<a href="/../pages/sales.php?user=' . htmlspecialchars($profileUser) . '" class="profile-action-button">View Items for Sale</a>' : '';
    $adminDeleteAccountLink = $isAdmin && !$isCurrentUser ? '<a href="/../actions/action_delete_user_account.php?user=' . htmlspecialchars($profileUser) . '" class="profile-action-button admin-delete-button">Delete User Account</a>' : '';
    ?>

    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-picture">
                <img src="/../database/images/profiles/thumbnails_medium/<?= htmlspecialchars($profile['profile_picture']) ?>.jpg" alt="profile-pic" class="profile-icon">
            </div>
            <div class="profile-details">
                <h1><?= htmlspecialchars($profile['username']) ?></h1>
                <h2>Member since <?= htmlspecialchars(transData($profile['create_date'])) ?></h2>
                <h2>From <?= htmlspecialchars($profile['location']) ?></h2>
            </div>
            <div class="profile-actions">
                <?= $editProfileLink ?>
                <?= $createItemLink ?>
                <?= $deleteAccountLink ?>
                <?= $viewItemsLink ?>
                <?= $adminDeleteAccountLink ?>
            </div>
        </div>
    </div>
    <?php
}

function drawEditProfilePage($User) {
    $profile = getUser($User); ?>
    <div class="edit-profile-container">
        <div class="edit-profile-form">
            <h1 class="edit-profile-title">Edit Profile</h1>
            <form action="/../actions/action_edit_profile.php" method="post" enctype="multipart/form-data">
                <div class="form-group profile-picture-group">
                    <img src="/../database/images/profiles/thumbnails_medium/<?=$profile['profile_picture']?>.jpg" alt="Profile_Pic" id="current-photo" class="profile-picture">
                    <label for="icon-update-button" class="upload-label">Upload new photo</label>
                    <input type="file" id="icon-update-button" name="profile-pic" accept=".jpg">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($profile["email"] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($profile["username"] ?? '') ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                </div>

                <div class="form-group">
                    <label for="location">Location</label>
                    <select id="location" name="location">
                        <?php
                        $locations = [
                            'Portugal' => [
                                'Aveiro', 'Beja', 'Braga', 'Bragança', 'Castelo Branco', 'Coimbra', 'Évora', 'Faro',
                                'Guarda', 'Leiria', 'Lisboa', 'Portalegre', 'Porto', 'Santarém', 'Setúbal', 'Viana do Castelo',
                                'Vila Real', 'Viseu'
                            ],
                            'Açores' => [
                                'Corvo', 'Faial', 'Flores', 'Graciosa', 'Pico', 'Santa Maria', 'São Jorge', 'São Miguel', 'Terceira'
                            ],
                            'Madeira' => [
                                'Calheta', 'Câmara de Lobos', 'Funchal', 'Machico', 'Ponta do Sol', 'Porto Moniz', 'Porto Santo',
                                'Ribeira Brava', 'Santa Cruz', 'Santana', 'São Vicente'
                            ],
                            'Espanha' => [
                                'A Coruña', 'Álava', 'Albacete', 'Alicante', 'Almería', 'Asturias', 'Ávila', 'Badajoz', 'Barcelona',
                                'Burgos', 'Cáceres', 'Cádiz', 'Cantabria', 'Castellón', 'Ciudad Real', 'Córdoba', 'Cuenca', 'Girona',
                                'Granada', 'Guadalajara', 'Guipúzcoa', 'Huelva', 'Huesca', 'Illes Balears', 'Jaén', 'La Rioja', 'Las Palmas',
                                'León', 'Lleida', 'Lugo', 'Madrid', 'Málaga', 'Murcia', 'Navarra', 'Ourense', 'Palencia', 'Pontevedra',
                                'Salamanca', 'Santa Cruz de Tenerife', 'Segovia', 'Sevilla', 'Soria', 'Tarragona', 'Teruel', 'Toledo',
                                'Valencia', 'Valladolid', 'Vizcaya', 'Zamora', 'Zaragoza'
                            ]
                        ];
                        foreach ($locations as $region => $districts) {
                            echo '<optgroup label="' . $region . '">';
                            foreach ($districts as $district) {
                                echo '<option value="' . htmlspecialchars($district) . '"' . ($district === $profile['location'] ? ' selected' : '') . '>' . htmlspecialchars($district) . '</option>';
                            }
                            echo '</optgroup>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="<?= htmlspecialchars($profile["address"] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="postal_code">Postal Code</label>
                    <input type="text" id="postal_code" name="postal_code" value="<?= htmlspecialchars($profile["postal_code"] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="currency">Preferred Currency</label>
                    <select id="currency" name="currency">
                        <option value="euro" <?= ($profile["currency"] ?? '') === 'euro' ? 'selected' : '' ?>>Euro</option>
                        <option value="dollar" <?= ($profile["currency"] ?? '') === 'dollar' ? 'selected' : '' ?>>Dollar</option>
                        <option value="pound" <?= ($profile["currency"] ?? '') === 'pound' ? 'selected' : '' ?>>Pound</option>
                        <option value="real" <?= ($profile["currency"] ?? '') === 'real' ? 'selected' : '' ?>>Real</option>
                        <option value="yen" <?= ($profile["currency"] ?? '') === 'yen' ? 'selected' : '' ?>>Yen</option>
                    </select>
                </div>

                <button type="submit" class="save-button">Save</button>
            </form>
        </div>
    </div>
<?php }
/*
function drawWishlist($username) { ?>
    <div class="wishlist-container">
        <div class="wishlist-card">
            <h1 class="wishlist-title">Wishlist</h1>
            <section class="wishlist-items">
                <?php
                // Retrieve items in the wishlist
                $items = getWishlist($username);

                // Check if the wishlist is empty
                if (!$items || count($items) == 0)  {
                    ?><h2 id = "EmptyWishlist">Your wishlist is empty!</h2>
                    <p>Try adding your first item to your wishlist!</p>
                    <?php
                } else{
                    // Display items in the wishlist
                    foreach ($items as $item) {
                        if (!$item) continue;
                        $itemDetails = getItemById($item);
                        ?>
                        <div class="wishlist-item">
                            <img src="/../database/images/items/thumbnails_medium/<?= $itemDetails['item_pictures'] ?>.jpg" alt="<?= $itemDetails['title'] ?>" class="wishlist-item-image">
                            <div class="wishlist-item-details">
                                <h2 id="wishlist-item-title"><a href="/../pages/item.php?id=<?= $itemDetails['item_id'] ?>"><?= $itemDetails['title'] ?></a></h2>
                                <p class="wishlist-item-price">$<?= $itemDetails['price'] ?></p>
                                <div class="button-container">
                                    <a class="remove-item-button" href="/../actions/action_remove_from_wishlist.php?id=<?= $itemDetails['item_id'] ?>">Remove</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </section>
        </div>
    </div>
<?php }*/
/*
function drawWishlist($username) { ?>
    <div class="wishlist-container">
        <div class="wishlist-card">
            <h1 class="wishlist-title">Wishlist</h1>
            <section class="wishlist-items">
                <?php
                // Retrieve items in the wishlist
                $items = getWishlist($username);

                // Check if the wishlist is empty
                if (!$items || count($items) == 0)  {
                    ?><h2 id="EmptyWishlist">Your wishlist is empty!</h2>
                    <p>Try adding your first item to your wishlist!</p>
                    <?php
                } else {
                    // Display items in the wishlist
                    foreach ($items as $item) {
                        if (!$item) continue;
                        $itemDetails = getItemById($item);
                        ?>
                        <div class="wishlist-item">
                            <img src="/../database/images/items/thumbnails_medium/<?= $itemDetails['item_pictures'] ?>.jpg" alt="<?= $itemDetails['title'] ?>" class="wishlist-item-image">
                            <div class="wishlist-item-details">
                                <h2 id="wishlist-item-title"><a href="/../pages/item.php?id=<?= $itemDetails['item_id'] ?>"><?= $itemDetails['title'] ?></a></h2>
                                <p class="wishlist-item-price">$<?= $itemDetails['price'] ?></p>
                                <div class="button-container">
                                    <a class="remove-item-button" href="/../actions/action_remove_from_wishlist.php?id=<?= $itemDetails['item_id'] ?>">Remove</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </section>
        </div>
    </div>
<?php }*/

function drawWishlist($username) {
    $currentUser = getCurrentUser();
    $currency = $currentUser ? $currentUser['currency'] : 'dollar';
    ?>
    <div class="wishlist-container">
        <div class="wishlist-card">
            <h1 class="wishlist-title">Wishlist</h1>
            <section class="wishlist-items">
                <?php
                $items = getWishlist($username);

                if (!$items || count($items) == 0)  {
                    ?><h2 id="EmptyWishlist">Your wishlist is empty!</h2>
                    <p>Try adding your first item to your wishlist!</p>
                    <?php
                } else {
                    foreach ($items as $item) {
                        if (!$item) continue;
                        $itemDetails = getItemById($item);
                        if (!$itemDetails || $itemDetails['status'] == 'sold') continue;

                        // Verificação de preços e conversão
                        if (isset($itemDetails['price']) && is_numeric($itemDetails['price'])) {
                            $convertedPrice = convertCurrency(floatval($itemDetails['price']), 'dollar', $currency);
                            $formattedPrice = formatCurrency($convertedPrice, $currency);
                        } else {
                            $formattedPrice = "N/A";
                        }
                        ?>
                        <div class="wishlist-item">
                            <img src="/../database/images/items/thumbnails_medium/<?= htmlspecialchars($itemDetails['item_pictures']) ?>.jpg" alt="<?= htmlspecialchars($itemDetails['title']) ?>" class="wishlist-item-image">
                            <div class="wishlist-item-details">
                                <h2 id="wishlist-item-title"><a href="/../pages/item.php?id=<?= htmlspecialchars($itemDetails['item_id']) ?>"><?= htmlspecialchars($itemDetails['title']) ?></a></h2>
                                <p class="wishlist-item-price"><?= $formattedPrice ?></p>
                                <div class="button-container">
                                    <a class="remove-item-button" href="/../actions/action_remove_from_wishlist.php?id=<?= htmlspecialchars($itemDetails['item_id']) ?>">Remove</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </section>
        </div>
    </div>
    <?php
}





function drawMessages($username){

}

