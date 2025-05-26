<?php
include 'includes/db.php';
include 'includes/sidebar.php';

$id = $_GET['id'];
$admin = $conn->query("SELECT * FROM Admin WHERE id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $newPassword = $_POST['password'];
    $profilePath = $admin['profile_picture'];

    if (!empty($_FILES['profile_picture']['name'])) {
        $uploadDir = 'images/admin/';
        $filename = uniqid() . '_' . basename($_FILES['profile_picture']['name']);
        $uploadFile = $uploadDir . $filename;
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile);
        $profilePath = $uploadFile;
    }

    if (!empty($newPassword)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE Admin SET name = ?, email = ?, password = ?, profile_picture = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $email, $hashedPassword, $profilePath, $id);
    } else {
        $stmt = $conn->prepare("UPDATE Admin SET name = ?, email = ?, profile_picture = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $profilePath, $id);
    }

    $stmt->execute();
    header("Location: manage_admins.php");
    exit;
}
?>

<div class="main-content" id="main-content">
    <a href="manage_admins.php" class="btn btn-back">← Back</a>
    <h2>Edit Admin</h2>
    <form method="post" enctype="multipart/form-data" class="admin-form">
        <div class="form-container">
            <div class="form-left">
                <label>Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($admin['name']) ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required>

                <label>New Password (optional)</label>
                <input type="password" name="password">
                
                <button type="submit" class="btn btn-edit">Update Admin</button>
            </div>

            <div class="form-right">
                <label>Profile Picture</label>
                <input type="file" name="profile_picture" id="profile_picture" accept="image/*" onchange="previewImage(event)">
                
                <div class="image-preview" id="imagePreview">
                    <?php if (!empty($admin['profile_picture'])): ?>
                        <img src="<?= htmlspecialchars($admin['profile_picture']) ?>" alt="Profile" id="preview" />
                    <?php else: ?>
                        <img src="images/default-avatar.png" alt="Default" id="preview" />
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .main-content {
        padding: 20px;
    }
    .admin-form {
        max-width: 900px;
    }
    .form-container {
        display: flex;
        gap: 40px;
        align-items: flex-start;
    }
    .form-left {
        flex: 1;
    }
    .form-right {
        width: 250px;
        text-align: center;
    }
    .admin-form label {
        display: block;
        margin: 10px 0 5px;
    }
    .admin-form input[type="text"],
    .admin-form input[type="email"],
    .admin-form input[type="password"] {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
    }
    input[type="file"] {
        display: block;
        margin: 10px auto;
    }
    .image-preview {
        width: 200px;
        height: 200px;
        margin: 10px auto;
        border: 2px dashed #ccc;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fafafa;
    }
    .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .btn {
        padding: 8px 16px;
        border: none;
        color: white;
        font-size: 14px;
        cursor: pointer;
        border-radius: 4px;
        text-decoration: none;
    }
    .btn-edit {
        background-color: #007bff;
    }
    .btn-back {
        background-color: #6c757d;
        margin-bottom: 20px;
        display: inline-block;
    }
    .btn:hover {
        opacity: 0.9;
    }
</style>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];
    if (file) {
        preview.src = URL.createObjectURL(file);
    }
}
</script>
