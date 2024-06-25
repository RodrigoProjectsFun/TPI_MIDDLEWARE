<?php
require 'database.php';

// Fetch campaign categories from the database
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM campaign_category";
$q = $pdo->prepare($sql);
$q->execute();
$campaign_categories = $q->fetchAll(PDO::FETCH_ASSOC);
Database::disconnect();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        html {
            font-family: Arial;
            display: inline-block;
            margin: 0px auto;
            text-align: center;
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

        .table {
            margin: auto;
            width: 90%;
        }

        thead {
            color: #FFFFFF;
        }
    </style>
    <title>View Campaign Categories</title>
    <script>
        function toggleActiveStatus(campaignCategoryId) {
            var checkbox = document.getElementById('active-checkbox-' + campaignCategoryId);
            var currentStatus = checkbox.checked ? 1 : 0;
            $.ajax({
                type: 'POST',
                url: 'toggle_active_status.php',
                data: {
                    campaign_category_id: campaignCategoryId,
                    current_status: currentStatus
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.new_status !== undefined) {
                        document.getElementById('active-status-' + campaignCategoryId).innerHTML = result.new_status ? 'Active' : 'Inactive';
                        checkbox.checked = result.new_status;
                    } else {
                        console.error('Failed to update status');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + error);
                }
            });
        }
    </script>
</head>
<body>
    <h2>Portal Server</h2>
    <ul class="topnav">
        <li><a href="home.php">Home</a></li>
        <li><a href="view_campaign_categories.php" class="active">View Campaign Categories</a></li>
        <li><a href="registration.php">Register Product</a></li>
        <li><a href="read_tag.php">Product Information</a></li>
        <li><a href="associate_tag.php">Print Information on Tag</a></li>
    </ul>
    <br>
    <div class="container">
        <div class="row">
            <h3>Campaign Categories</h3>
        </div>
        <div class="row">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr bgcolor="#10a0c5">
                        <th>Campaign Category Name</th>
                        <th>Discount</th>
                        <th>Description</th>
                        <th>Category ID</th>
                        <th>Active Status</th>
                        <th>Toggle Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($campaign_categories as $category): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($category['campaign_category_name']); ?></td>
                            <td><?php echo htmlspecialchars($category['campaign_category_discount']); ?></td>
                            <td><?php echo htmlspecialchars($category['campaign_category_description']); ?></td>
                            <td><?php echo htmlspecialchars($category['category_id']); ?></td>
                            <td id="active-status-<?php echo $category['campaign_category_id']; ?>"><?php echo $category['active'] ? 'Active' : 'Inactive'; ?></td>
                            <td>
                                <input type="checkbox" id="active-checkbox-<?php echo $category['campaign_category_id']; ?>" <?php echo $category['active'] ? 'checked' : ''; ?> onclick="toggleActiveStatus(<?php echo $category['campaign_category_id']; ?>)">
                            </td>
                            <td>
                                <a class="btn btn-success" href="edit_campaign_category.php?id=<?php echo $category['campaign_category_id']; ?>">Edit</a>
                                <a class="btn btn-danger" href="delete_campaign_category.php?id=<?php echo $category['campaign_category_id']; ?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
