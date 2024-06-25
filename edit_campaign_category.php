<?php
require 'database.php';

$id = null;
if (!empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

if (null == $id) {
    header("Location: view_campaign_categories.php");
}

if (!empty($_POST)) {
    $campaign_category_name = $_POST['campaign_category_name'];
    $campaign_category_discount = $_POST['campaign_category_discount'];
    $active = isset($_POST['active']) ? 1 : 0;
    $campaign_category_description = $_POST['campaign_category_description'];
    $category_id = $_POST['category_id'];

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE campaign_category SET campaign_category_name = ?, campaign_category_discount = ?, active = ?, campaign_category_description = ?, category_id = ? WHERE campaign_category_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($campaign_category_name, $campaign_category_discount, $active, $campaign_category_description, $category_id, $id));
    Database::disconnect();
    header("Location: view_campaign_categories.php");
} else {
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM campaign_category WHERE campaign_category_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));
    $data = $q->fetch(PDO::FETCH_ASSOC);

    // Fetch categories from the category table
    $sql = "SELECT category_id, category_name FROM category ORDER BY category_name ASC";
    $q = $pdo->prepare($sql);
    $q->execute();
    $categories = $q->fetchAll(PDO::FETCH_ASSOC);

    Database::disconnect();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <style>
        html {
            font-family: Arial;
            display: inline-block;
            margin: 0px auto;
        }

        textarea {
            resize: none;
        }

        ul.topnav {
            list-style-type: none;
            margin: auto;
            padding: 0;
            overflow: hidden;
            background-color: #4CAF50;
            width: 70%;
        }

        ul.topnav li {
            float: left;
        }

        ul.topnav li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        ul.topnav li a:hover:not(.active) {
            background-color: #3e8e41;
        }

        ul.topnav li a.active {
            background-color: #333;
        }

        ul.topnav li.right {
            float: right;
        }

        @media screen and (max-width: 600px) {
            ul.topnav li.right,
            ul.topnav li {
                float: none;
            }
        }
    </style>
    <title>Edit Campaign Category</title>
</head>
<body>
    <h2 align="center">Portal Server</h2>
    <ul class="topnav">
        <li><a href="home.php">Home</a></li>
        <li><a class="active" href="view_campaign_categories.php">Campaign Categories</a></li>
        <li><a href="create_campaign_category.php">Register Campaign Category</a></li>
    </ul>

    <div class="container">
        <br>
        <div class="center" style="margin: 0 auto; width:495px; border-style: solid; border-color: #f2f2f2;">
            <div class="row">
                <h3 align="center">Edit Campaign Category</h3>
            </div>
            <br>
            <form class="form-horizontal" action="edit_campaign_category.php?id=<?php echo $id?>" method="post">
                <div class="control-group">
                    <label class="control-label">Campaign Category Name</label>
                    <div class="controls">
                        <input name="campaign_category_name" type="text" value="<?php echo htmlspecialchars($data['campaign_category_name']); ?>" required>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Campaign Category Discount</label>
                    <div class="controls">
                        <input name="campaign_category_discount" type="text" value="<?php echo htmlspecialchars($data['campaign_category_discount']); ?>" required>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Active</label>
                    <div class="controls">
                        <input name="active" type="checkbox" <?php echo ($data['active'] == 1) ? 'checked' : ''; ?>>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Campaign Category Description</label>
                    <div class="controls">
                        <textarea name="campaign_category_description" rows="4" required><?php echo htmlspecialchars($data['campaign_category_description']); ?></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Category</label>
                    <div class="controls">
                        <select name="category_id" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['category_id']; ?>" <?php echo ($data['category_id'] == $category['category_id']) ? 'selected' : ''; ?>><?php echo $category['category_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
