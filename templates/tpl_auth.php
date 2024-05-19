<?php
function drawLogin() { ?>
    <div class="login-container">
        <div class="login-form">
            <h1 class="login-title">Login</h1>
            <form action="/../actions/action_login.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required placeholder="Username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Password">
                    <div class="show-password">
                        <input type="checkbox" id="show-password">
                        <label for="show-password" id="labelPassword">Show Password</label>
                    </div>
                </div>
                <button type="submit" class="login-button">Login</button>
            </form>
            <p class="signup-prompt">Don't have an account? <a href="../pages/register.php">Register</a></p>
        </div>
    </div>
<?php }


function drawRegister() { ?>
    <div class="register-container">
        <div class="register-form">
            <h1 class="register-title">Register</h1>
            <section class="options-auth">
                <p>Already have an account? <a href="../pages/login.php">Login</a></p>
            </section>
            <form action="/../actions/action_register.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required placeholder="Username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Password">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm Password">
                    <span id="password-match-msg"></span>
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
                    <label class="edit-form" for="address">Address:</label>
                    <input type="text" id="address" name="address" required placeholder="Address">
                </div>
                <div class="form-group">
                    <label class="edit-form" for="postal_code">Postal Code:</label>
                    <input type="text" id="postal_code" name="postal_code" required placeholder="Postal Code">
                </div>
                <div class="form-group">
                    <label class="edit-form" for="currency">Preferred Currency:</label>
                    <select id="currency" name="currency" required>
                        <option value="euro">Euro</option>
                        <option value="dollar">Dollar</option>
                        <option value="pound">Pound</option>
                        <option value="real">Real</option>
                        <option value="yen">Yen</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="profile-pic">Profile Picture</label>
                    <input type="file" id="profile-pic" name="profile-pic" accept=".jpg">
                </div>
                <button type="submit" class="register-button">Register</button>
            </form>
        </div>
    </div>
<?php }