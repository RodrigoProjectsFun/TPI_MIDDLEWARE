<?php
require 'database.php';
require 'vendor/autoload.php';

use Kreait\Firebase\Factory;
use Google\Cloud\Firestore\FirestoreClient;

function logMessage($message) {
    file_put_contents('log.txt', date('Y-m-d H:i:s') . " - " . $message . PHP_EOL, FILE_APPEND);
}

function checkSessionAndFetchProduct($UIDresult, $deviceID) {
    // Connect to the database
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch session information based on the UIDresult
    $sql = "SELECT * FROM sessions WHERE uid = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($UIDresult));
    $sessionData = $q->fetch(PDO::FETCH_ASSOC);

    if ($sessionData) {
        // Log the session details
        logMessage("Session data: " . print_r($sessionData, true));

        // Fetch product information based on the tag UID
        $tag_id = $sessionData['tag_id'];
        $sql = "SELECT product.product_id, product.name, product.description as product_description, product.price as product_price, tag.tag_id 
                FROM product
                JOIN tag ON product.product_id = tag.product_id
                WHERE tag.tag_id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($tag_id));
        $data = $q->fetch(PDO::FETCH_ASSOC);

        if ($data && isset($data['product_id'])) {
            // Check if there is an active discount for any of the product's categories
            $sql = "SELECT cc.campaign_category_discount 
                    FROM campaign_category cc
                    JOIN product_category pc ON cc.category_id = pc.category_category_id
                    WHERE pc.product_product_id = ? AND cc.active = 1 AND cc.campaign_category_discount > 0";
            $q = $pdo->prepare($sql);
            $q->execute(array($data['product_id']));
            $discount = $q->fetch(PDO::FETCH_ASSOC);

            // Determine if there is an offer and the discount value
            $hasOffer = $discount ? 'true' : 'false';
            $offerValue = $discount ? $discount['campaign_category_discount'] : 0;

            // Fetch categories associated with the product
            $sql = "SELECT category.category_name
                    FROM category
                    JOIN product_category ON category.category_id = product_category.category_category_id
                    WHERE product_category.product_product_id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($data['product_id']));
            $categories = $q->fetchAll(PDO::FETCH_COLUMN);

            // Initialize Firebase
            $factory = (new Factory)
                ->withServiceAccount(__DIR__ . '/tp-smartkart-7dc93-firebase-adminsdk-53o47-799cd5e3ff.json');
            $firestore = $factory->createFirestore();
            $collection = $firestore->database()->collection('scanned_product');

            // Check if document with the same tag_id already exists
            $query = $collection->where('tag_id', '==', $tag_id)->limit(1);
            $documents = $query->documents();

            if ($documents->isEmpty()) {
                // Document does not exist, create a new one
                $firestoreData = [
                    'name' => $data['name'],
                    'product_description' => $data['product_description'],
                    'product_price' => $data['product_price'],
                    'tag_id' => $data['tag_id'],
                    'deviceID' => $deviceID,
                    'categories' => implode(', ', $categories),
                    'offer' => $hasOffer,
                    'discount_value' => $offerValue,
                    'active' => true
                ];
                $document = $collection->add($firestoreData);
            } else {
                // Document exists, update the active status
                foreach ($documents as $document) {
                    $currentData = $document->data();
                    $newStatus = !$currentData['active'];
                    $document->reference()->update([
                        ['path' => 'active', 'value' => $newStatus]
                    ]);
                }
            }

            // Return the product data for further processing
            return $data;
        }
    }

    Database::disconnect();
    return null;
}

if (isset($_GET["UIDresult"]) && isset($_GET["deviceID"])) {
    $UIDresult = $_GET["UIDresult"];
    $deviceID = $_GET["deviceID"];
    
    $productData = checkSessionAndFetchProduct($UIDresult, $deviceID);

    if ($productData) {
        // Output the table
        echo "<table width='452' border='0' align='center' cellpadding='5' cellspacing='0'>";
        echo "<tr><td width='113' align='left' class='lf'>Nombre del producto</td><td style='font-weight:bold'>:</td><td align='left'>" . htmlspecialchars($productData['name']) . "</td></tr>";
        echo "<tr bgcolor='#f2f2f2'><td align='left' class='lf'>Descripción del producto</td><td style='font-weight:bold'>:</td><td align='left'>" . htmlspecialchars($productData['product_description']) . "</td></tr>";
        echo "<tr><td align='left' class='lf'>Precio del producto</td><td style='font-weight:bold'>:</td><td align='left'>" . htmlspecialchars($productData['product_price']) . "</td></tr>";
        echo "<tr bgcolor='#f2f2f2'><td align='left' class='lf'>UID de la etiqueta</td><td style='font-weight:bold'>:</td><td align='left'>" . htmlspecialchars($productData['tag_id']) . "</td></tr>";
        echo "<tr><td align='left' class='lf'>ID del dispositivo</td><td style='font-weight:bold'>:</td><td align='left'>" . htmlspecialchars($deviceID) . "</td></tr>";
        echo "<tr bgcolor='#f2f2f2'><td align='left' class='lf'>Categorías del producto</td><td style='font-weight:bold'>:</td><td align='left'>" . htmlspecialchars(implode(', ', $categories)) . "</td></tr>";
        echo "<tr><td align='left' class='lf'>Oferta</td><td style='font-weight:bold'>:</td><td align='left'>" . htmlspecialchars($hasOffer) . "</td></tr>";
        if ($hasOffer === 'true') {
            echo "<tr bgcolor='#f2f2f2'><td align='left' class='lf'>Valor de la oferta</td><td style='font-weight:bold'>:</td><td align='left'>" . htmlspecialchars($offerValue) . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No se encontró información del producto para esta etiqueta.</p>";
    }
} else {
    logMessage("UIDresult or deviceID key not found in GET data.");
    echo "UIDresult or deviceID key not found in GET data.";
}
?>
