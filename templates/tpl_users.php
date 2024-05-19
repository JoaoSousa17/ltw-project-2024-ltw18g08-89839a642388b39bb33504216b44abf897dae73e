<?php
function drawAllUsers() {
    $users = getAllUsers();
    $currentUser = getCurrentUser();
    $isAdmin = $currentUser['is_admin'] ?? false;
    ?>
    <div class="users-container">
        <h1>All Users</h1>
        <table class="users-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Admin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= $user['is_admin'] ? 'Yes' : 'No' ?></td>
                        <td>
                            <?php if ($isAdmin && !$user['is_admin']): ?>
                                <a class="action-button promote-button" href="/../actions/action_promote_user.php?id=<?= $user['user_id'] ?>">Promote to Admin</a>
                            <?php endif; ?>
                            <?php if ($isAdmin): ?>
                                <a class="action-button delete-button" href="/../actions/action_delete_user.php?id=<?= $user['user_id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete User</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}
?>
