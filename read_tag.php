<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="jquery.min.js"></script>
    <script>
    function fetchProductData(tagID) {
        $.ajax({
            url: 'fetch_product_data.php',
            type: 'GET',
            data: { id: tagID },
            success: function(response) {
                $('#productTable').html(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    $(document).ready(function() {
        function checkForNewUID() {
            $.ajax({
                url: "UIDContainer.php",
                success: function(response) {
                    var newTagID = response.trim();
                    if (newTagID) {
                        fetchProductData(newTagID);
                    }
                },
                complete: function() {
                    setTimeout(checkForNewUID, 500); 
                }
            });
        }

        $("#getUID").load("UIDContainer.php", function(response) {
            var currentTagID = response.trim();
            fetchProductData(currentTagID);
            checkForNewUID();  
        });
    });
    </script>

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
    <title>Read Tag User Data</title>
</head>
<body>
    <h2 align="center">Portal Server</h2>
    <ul class="topnav">
        <li><a href="home.php">Inicio</a></li>
        <li><a class="active" href="view_products.php">Registros de productos</a></li>
        <li><a href="registration.php">Registrar producto</a></li>
        <li><a href="read_tag.php">Información de producto</a></li>
        <li><a href="associate_tag.php">Imprimir Informacion en etiqueta</a></li>
    </ul>
    <br>
    <div id="getUID" style="display:none;"></div>
    <div id="productTable">
        <p>Esperando a que se escanee una etiqueta...</p>
    </div>
</body>
</html>
