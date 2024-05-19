<?php
function drawMessagesList($username) { ?>
    <section id="messages-list">
        <h1>Messages</h1>
        <div id="messages-container">
            <?php
            include_once(__DIR__ . '/../database/messages.php');
            include_once(__DIR__ . '/../database/user.php');
            include_once(__DIR__ . '/../database/item.php');

            $messages = getMessages($username);
            foreach ($messages as $message) {
                $sender = getUser($message['from_user_id']);
                $receiver = getUser($message['to_user_id']);
                $item = getItem($message['item_id']);
                ?>
                <div class="message">
                    <div class="message-header">
                        <h2><?= htmlspecialchars($item['title']) ?></h2>
                        <h3><?= htmlspecialchars($message['send_date']) ?></h3>
                    </div>
                    <div class="message-content">
                        <p><?= htmlspecialchars($message['message']) ?></p>
                    </div>
                    <div class="message-footer">
                        <h3>From: <?= htmlspecialchars($sender['username']) ?></h3>
                        <h3>To: <?= htmlspecialchars($receiver['username']) ?></h3>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
<?php } ?>


<?php
function drawMessages($username, $otherUser) {
    include_once(__DIR__ . '/../database/messages.php');
    include_once(__DIR__ . '/../database/user.php');
    include_once(__DIR__ . '/../database/item.php');

    $messages = getMessagesBetweenUsers($username, $otherUser);
    foreach ($messages as $message) {
        $sender = getUser($message['from_user_id']);
        $receiver = getUser($message['to_user_id']);
        $item = getItem($message['item_id']);
        ?>
        <div class="message">
            <div class="message-header">
                <h2><?= htmlspecialchars($item['title']) ?></h2>
                <h3><?= htmlspecialchars($message['send_date']) ?></h3>
            </div>
            <div class="message-content">
                <p><?= htmlspecialchars($message['message']) ?></p>
            </div>
            <div class="message-footer">
                <h3>From: <?= htmlspecialchars($sender['username']) ?></h3>
                <h3>To: <?= htmlspecialchars($receiver['username']) ?></h3>
            </div>
        </div>
    <?php }
}
?>
