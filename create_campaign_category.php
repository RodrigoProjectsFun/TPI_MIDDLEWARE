<?php
require 'database.php';

if (!empty($_POST)) {
    // Get form data
    $campaign_category_name = $_POST['campaign_category_name'];
    $campaign_category_discount = $_POST['campaign_category_discount'];
    $active = isset($_POST['active']) ? 1 : 0;
    $campaign_category_description = $_POST['campaign_category_description'];
    $category_id = $_POST['category_id'];

    // Insert form data into the campaign_category table
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO campaign_category (campaign_category_name, campaign_category_discount, active, campaign_category_description, category_id) VALUES (?, ?, ?, ?, ?)";
    $q = $pdo->prepare($sql);
    $q->execute(array($campaign_category_name, $campaign_category_discount, $active, $campaign_category_description, $category_id));
    Database::disconnect();
    header("Location: view_campaign_categories.php");
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
    <title>Register Campaign Category</title>
</head>
<body>
    <h2 align="center">Portal Server</h2>
    <ul class="topnav">
        <li><a href="home.php">Home</a></li>
        <li><a class="active" href="view_campaign_categories.php">View Campaign Categories</a></li>
        <li><a href="create_campaign_category.php">Register Campaign Category</a></li>
    </ul>

    <div class="container">
        <br>
        <div class="center" style="margin: 0 auto; width:495px; border-style: solid; border-color: #f2f2f2;">
            <div class="row">
                <h3 align="center">Campaign Category Registration Form</h3>
            </div>
            <br>
            <form class="form-horizontal" action="create_campaign_category.php" method="post">
                <div class="control-group">
                    <label class="control-label">Campaign Category Name</label>
                    <div class="controls">
                        <input name="campaign_category_name" type="text" placeholder="" required>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Campaign Category Discount</label>
                    <div class="controls">
                        <input name="campaign_category_discount" type="text" placeholder="" required>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Active</label>
                    <div class="controls">
                        <input name="active" type="checkbox">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Campaign Category Description</label>
                    <div class="controls">
                        <textarea name="campaign_category_description" rows="4" placeholder="" required></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Category</label>
                    <div class="controls">
                        <select name="category_id" required>
                            <?php
                            $pdo = Database::connect();
                            $sql = 'SELECT category_id, category_name FROM category ORDER BY category_name ASC';
                            foreach ($pdo->query($sql) as $row) {
                                echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                            }
                            Database::disconnect();
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success">Register</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
