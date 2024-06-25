<?php
require 'database.php';
require 'vendor/autoload.php';

use Kreait\Firebase\Factory;
use Google\Cloud\Firestore\FirestoreClient;

function logMessage($message) {
    file_put_contents('log.txt', date('Y-m-d H:i:s') . " - " . $message . PHP_EOL, FILE_APPEND);
}

if (isset($_POST["UIDresult"]) && isset($_POST["deviceID"])) {
    $UIDresult = $_POST["UIDresult"];
    $deviceID = $_POST["deviceID"];
    logMessage("Received UIDresult: " . $UIDresult . " from device: " . $deviceID);

    if (file_put_contents('UIDContainer.php', "<?php\n\$UIDresult = '" . $UIDresult . "';\necho \$UIDresult;" . " ?>") !== false) {
        logMessage("Successfully wrote to UIDContainer.php");
    } else {
        logMessage("Failed to write to UIDContainer.php");
    }

    $Write = "<?php\n";
    $Write .= "\$deviceID = '" . $deviceID . "';\n";
    $Write .= "echo \$deviceID;\n";

    if (file_put_contents('UUIDContainer.php', $Write) !== false) {
        logMessage("Successfully wrote to UUIDContainer.php");
    } else {
        logMessage("Failed to write to UUIDContainer.php");
    }

    if (!empty($UIDresult)) {
        fetchProductAndSendToFirestore($UIDresult, $deviceID);
    }

    echo $deviceID; 
} else {
    logMessage("UIDresult or deviceID key not found in POST data.");
    echo "UIDresult or deviceID key not found in POST data.";
}

function fetchProductAndSendToFirestore($tag_uid, $deviceID) {
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT product.product_id, product.name, product.description as product_description, product.price as product_price, tag.tag_id 
            FROM product
            JOIN tag ON product.product_id = tag.product_id
            WHERE tag.tag_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($tag_uid));
    $data = $q->fetch(PDO::FETCH_ASSOC);

    Database::disconnect();

    if ($data) {
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

        // Check if document with the same tag_uid already exists
        $query = $collection->where('tag_id', '=', $tag_uid)->documents();
        if ($query->size() > 0) {
            foreach ($query as $document) {
                if ($document->exists()) {
                    $currentData = $document->data();
                    $newStatus = !$currentData['active'];
                    $document->reference()->update([
                        ['path' => 'active', 'value' => $newStatus]
                    ]);
                    logMessage("Updated existing document with tag_id: " . $tag_uid);
                }
            }
            echo "Product status updated in Firestore.";
        } else {
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
            $collection->add($firestoreData);
            echo "Product added to Firestore.";
        }
    } else {
        echo "<p>No se encontró información del producto para esta etiqueta.</p>";
    }
}
?>
