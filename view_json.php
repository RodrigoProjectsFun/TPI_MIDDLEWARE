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
    </style>
    <title>View JSON Data</title>
</head>
<body>
    <h2 align="center">Portal Server</h2>
    <ul class="topnav">
        <li><a href="home.php">Inicio</a></li>
        <li><a href="user_data.php">Registros de productos</a></li>
        <li><a href="registration.php">Registrar producto</a></li>
        <li><a href="read_tag.php">Información del producto</a></li>
        <li><a class="active" href="view_json.php">Ver JSON</a></li>
    </ul>
    <br>
    <div class="container">
        <div class="row">
            <h3>JSON Data</h3>
            <pre>
            <?php
            $json_file = 'session_product.json';
            if (file_exists($json_file)) {
                $json_data = file_get_contents($json_file);
                echo htmlspecialchars($json_data);
            } else {
                echo "No JSON data available.";
            }
            ?>
            </pre>
        </div>
    </div>
</body>
</html>
