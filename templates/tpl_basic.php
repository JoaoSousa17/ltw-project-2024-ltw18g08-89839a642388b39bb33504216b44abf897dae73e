<?php
function drawHeader($username, $userImage = '/path/to/default_image.png') { ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Fit Finds</title>
        <link rel="stylesheet" href="../css/generic.css">
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/profile.css">
        <link rel="stylesheet" href="../css/auth.css">
        <link rel="stylesheet" href="../css/mainpage.css">
        <link rel="stylesheet" href="../css/categories.css">
        <link rel="stylesheet" href="../css/create_item.css">
        <link rel="stylesheet" href="../css/item.css">
        <link rel="stylesheet" href="../css/itemsSellingAndBought.css">
        <link rel="stylesheet" href="../css/shopCart.css">
        <link rel="stylesheet" href="../css/recipe.css">
        <script src="/../javascript/basic.js"></script>
        <script src="/../javascript/auth.js"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    </head>

    <body>
    <header class="header">
        <div id="left-section">
            <a href="../pages/mainPage.php">
                <img class="header-icon" src="/../database/images/generic/Fit_Finds_Icon.png" alt="Icon">
            </a>
            <nav id="menu">
                <a href="../pages/mainPage.php">Home</a>
                <a href="../pages/categories.php">Categories</a>
                <a href="../pages/shopCart.php">Cart</a>
            </nav>
        </div>
        <div id="search-section">
            <form action="../pages/search.php" method="get" class="search-form">
                <input type="text" placeholder="Search for products..." name="search" class="search-input">
                <button type="submit" class="search-button"><img src="../images/Lupa.png" alt="Search"></button>
            </form>
        </div>
        <div id="userContainer">
            <div id="userButton">
                <?php if ($username): 
                    $profile = getUser($username); ?>
                    <img src="/../database/images/profiles/thumbnails_medium/<?= $profile['profile_picture'] ?>.jpg" alt="profile-pic" class="user-photo" onclick="toggleDropdown()">
                <?php else: ?>
                    <img src="../database/images/profiles/originals/default.jpg" alt="User" class="user-photo" onclick="toggleDropdown()">
                <?php endif; ?>
                <ul class="dropdown-content" id="userDropdownContent">
                    <?php if ($username): ?>
                        <li><a href="../pages/profile.php?id=<?= $username ?>">Profile</a></li>
                        <li><a href="../pages/wishlist.php">Wishlist</a></li>
                        <li><a href="../pages/purchases.php">Purchases</a></li>
                        <li><a href="../pages/sales.php">Items on Sell</a></li>
                        <li><a href="../pages/sales.php">Items Sold</a></li>
                        <li><a href="../pages/messages_list.php">Messages</a></li>
                        <li><a href="../actions/action_logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="../pages/login.php">Login</a></li>
                        <li><a href="../pages/register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </header>

    <script>
    function toggleDropdown() {
        var dropdown = document.getElementById("userDropdownContent");
        dropdown.classList.toggle("show");
    }

    window.onclick = function(event) {
        if (!event.target.matches('.user-photo')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
    </script>
<?php }

function drawFooter() {
    ?>
    <footer>
        <p>&copy; <?= date('Y') ?> Fit Finds. All rights reserved.</p>
    </footer>
    </body>
    </html>
    <?php
}
?>