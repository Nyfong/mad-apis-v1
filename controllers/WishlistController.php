<?php

//include 'config/database.php';


class WishlistController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // GET: /api/wishlist?user_id=1
    public function index($user_id) {
        $query = "SELECT * FROM wishlist WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        $wishlist = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($wishlist);
    }

    // POST: /api/wishlist
    // Body: { "user_id": 1, "product_id": 10 }
    public function add() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['user_id']) || !isset($data['product_id'])) {
            http_response_code(400);
            echo json_encode(["message" => "user_id and product_id are required"]);
            return;
        }

        $query = "INSERT INTO wishlist (user_id, product_id) VALUES (:user_id, :product_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':product_id', $data['product_id']);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["message" => "Added to wishlist"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to add to wishlist"]);
        }
    }

    // DELETE: /api/wishlist
    // Body: { "user_id": 1, "product_id": 10 }
    public function remove() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['user_id']) || !isset($data['product_id'])) {
            http_response_code(400);
            echo json_encode(["message" => "user_id and product_id are required"]);
            return;
        }

        $query = "DELETE FROM wishlist WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':product_id', $data['product_id']);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["message" => "Removed from wishlist"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to remove from wishlist"]);
        }
    }
}
