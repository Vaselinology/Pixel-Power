<?php
include 'includes/db.php';
include 'includes/sidebar.php';

// Delete message if requested
if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    $conn->query("DELETE FROM Message WHERE id = $deleteId");
    header("Location: view_messages.php?type=" . urlencode($_GET['type'] ?? 'feedback'));
    exit;
}

// Get message type
$type = isset($_GET['type']) ? $_GET['type'] : 'feedback';
$stmt = $conn->prepare("SELECT * FROM Message WHERE type = ? ORDER BY date_sent DESC");
$stmt->bind_param("s", $type);
$stmt->execute();
$messages = $stmt->get_result();
?>

<div class="main-content" id="main-content">
    <h2>View Messages</h2>

    <div class="message-tabs">
        <a href="?type=feedback" class="<?= $type === 'feedback' ? 'active' : '' ?>">Feedback</a>
        <a href="?type=partnership" class="<?= $type === 'partnership' ? 'active' : '' ?>">Partnership</a>
    </div>

    <table class="message-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Content</th>
                <th>Date Sent</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $messages->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['subject']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['content'])) ?></td>
                    <td><?= $row['date_sent'] ?></td>
                    <td>
                        <a href="?type=<?= $type ?>&delete=<?= $row['id'] ?>"
                           onclick="return confirm('Are you sure you want to delete this message?');"
                           class="delete-btn">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<style>
    .message-tabs {
        margin-bottom: 20px;
    }

    .message-tabs a {
        display: inline-block;
        padding: 10px 20px;
        margin-right: 10px;
        background-color: #ddd;
        color: #333;
        text-decoration: none;
        border-radius: 4px;
    }

    .message-tabs a.active {
        background-color: #007bff;
        color: white;
    }

    .message-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .message-table th,
    .message-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        text-align: left;
    }

    .message-table th {
        background-color: #f7f7f7;
        font-weight: bold;
    }

    .message-table tr:hover {
        background-color: #f1f1f1;
    }

    .delete-btn {
        background-color: #dc3545;
        color: white;
        padding: 6px 12px;
        border-radius: 4px;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .delete-btn:hover {
        background-color: #c82333;
    }

</style>