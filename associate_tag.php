<?php
require 'database.php';

if (!empty($_POST)) {
    $product_id = $_POST['product_id'];
    $tag_id = $_POST['tag_id'];

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT COUNT(*) as count FROM tag WHERE tag_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($tag_id));
    $row = $q->fetch(PDO::FETCH_ASSOC);
    $existing_records_count = $row['count'];
    
    if ($existing_records_count > 0) {
        $sql = "UPDATE tag SET product_id = ? WHERE tag_id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($product_id, $tag_id));
    } else {
        $sql = "INSERT INTO tag (tag_id, product_id) VALUES (?, ?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($tag_id, $product_id));
    }
    
    Database::disconnect();
    header("Location: associate_tag.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#getUID").load("UIDContainer.php");
            setInterval(function() {
                $("#getUID").load("UIDContainer.php");
            }, 500);
        });
    </script>
    <style>
        html {
            font-family: Arial;
            display: inline-block;
            margin: 0px auto;
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
    <title>Associate Tag : NodeMCU V3 ESP8266 / ESP12E with MYSQL Database</title>
</head>
<body>
    <h2 align="center">Portal Server</h2>
    <ul class="topnav">
			<li><a href="home.php">Inicio</a></li>
			<li><a class="active" href="user_data.php">Registros de productos</a></li>
			<li><a href="create_product.php">Registrar producto</a></li>
			<li><a href="read_tag.php">Información de producto</a></li>
			<li><a href="associate_tag.php">Imprimir Informacion en etiqueta</a></li>
    </ul>

    <div class="container">
        <br>
        <div class="center" style="margin: 0 auto; width:495px; border-style: solid; border-color: #f2f2f2;">
            <div class="row">
                <h3 align="center">Formulario de asociación de etiqueta</h3>
            </div>
            <br>
            <form class="form-horizontal" action="associate_tag.php" method="post">
                <div class="control-group">
                    <label class="control-label">Producto</label>
                    <div class="controls">
                        <select name="product_id" required>
                            <option value="">Seleccionar producto</option>
                            <?php
                            $pdo = Database::connect();
                            $sql = 'SELECT * FROM product ORDER BY name ASC';
                            foreach ($pdo->query($sql) as $row) {
                                echo '<option value="' . $row['product_id'] . '">' . $row['name'] . '</option>';
                            }
                            Database::disconnect();
                            ?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">UID de la etiqueta</label>
                    <div class="controls">
                        <textarea name="tag_id" id="getUID" placeholder="Por favor, escanea tu etiqueta" rows="1" cols="1" readonly></textarea>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success">Asociar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
