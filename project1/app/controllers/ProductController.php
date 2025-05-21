<?php

require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class ProductController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    public function index()
    {
        $products = $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function add()
    {
        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
    }

    public function save()
    {
        $productModel = new ProductModel($this->db);
        $categoryModel = new CategoryModel($this->db);

        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? '';
        $category_id = $_POST['category_id'] ?? '';
        $imagePath = '';

        // Xử lý upload ảnh
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploadDir = 'public/uploads/';
            $fileName = basename($_FILES['image']['name']);
            $targetFile = $uploadDir . time() . '_' . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = $targetFile;
            }
        }

        $result = $productModel->addProduct($name, $description, $price, $category_id, $imagePath);

        if ($result === true) {
            header('Location: /project1/Product');
            exit;
        } else {
            $categories = $categoryModel->getCategories();
            $errors = $result;
            include 'app/views/product/add.php';
        }
    }

    public function edit($id)
    {
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();

        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function update()
    {
        $productModel = new ProductModel($this->db);
        $categoryModel = new CategoryModel($this->db);

        $id = $_POST['id'] ?? '';
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? '';
        $category_id = $_POST['category_id'] ?? '';
        $imagePath = '';

        // Lấy ảnh hiện tại của sản phẩm để giữ nếu không upload ảnh mới
        $product = $productModel->getProductById($id);
        $imagePath = $product->image;

        // Upload ảnh mới nếu có
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploadDir = 'public/uploads/';
            $fileName = basename($_FILES['image']['name']);
            $targetFile = $uploadDir . time() . '_' . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = $targetFile;
            }
        }

        $result = $productModel->updateProduct($id, $name, $description, $price, $category_id, $imagePath);

        if ($result === true) {
            header('Location: /project1/Product');
            exit;
        } else {
            $categories = $categoryModel->getCategories();
            $errors = ['Lỗi khi cập nhật sản phẩm'];
            $product = $productModel->getProductById($id);
            include 'app/views/product/edit.php';
        }
    }

    public function delete($id)
    {
        if ($this->productModel->deleteProduct($id)) {
            header('Location: /project1/Product');
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
        exit();
    }
}
