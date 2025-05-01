<?php
class ProductController {
    private $db;
    private $product;

    public function __construct() {
        require_once 'config/database.php';
        require_once 'models/Product.php';
        $database = new Database();
        $this->db = $database->getConnection();
        $this->product = new Product($this->db);
    }

    public function index() {
        $stmt = $this->product->read();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($products);
    }
    
    

    public function show($id) {
        $this->product->id = $id;
        $this->product->readOne();
        header('Content-Type: application/json');
        if ($this->product->name !== null) {
            echo json_encode([
                'id' => $this->product->id,
                'name' => $this->product->name,
                'description' => $this->product->description,
                'price' => $this->product->price,
                'stock' => $this->product->stock,
                'created_at' => $this->product->created_at,
                'updated_at' => $this->product->updated_at
            ]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Product not found"]);
        }
    }

    public function create() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"));
        
        if (!empty($data->name) && !empty($data->price) && !empty($data->stock)) {
            $this->product->name = $data->name;
            $this->product->description = $data->description ?? '';
            $this->product->price = $data->price;
            $this->product->stock = $data->stock;
            $this->product->created_at = date('Y-m-d H:i:s');

            if ($this->product->create()) {
                http_response_code(201);
                echo json_encode(["message" => "Product created"]);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "Unable to create product"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Incomplete data"]);
        }
    }

    public function update($id) {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"));
        
        $this->product->id = $id;
        
        if (!empty($data->name) && !empty($data->price) && !empty($data->stock)) {
            $this->product->name = $data->name;
            $this->product->description = $data->description ?? '';
            $this->product->price = $data->price;
            $this->product->stock = $data->stock;
            $this->product->updated_at = date('Y-m-d H:i:s');

            if ($this->product->update()) {
                http_response_code(200);
                echo json_encode(["message" => "Product updated"]);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "Unable to update product"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Incomplete data"]);
        }
    }

    public function delete($id) {
        header('Content-Type: application/json');
        $this->product->id = $id;
        
        if ($this->product->delete()) {
            http_response_code(200);
            echo json_encode(["message" => "Product deleted"]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "Unable to delete product"]);
        }
    }
}
?>