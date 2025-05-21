<?php include 'app/views/shares/header.php'; ?>

<h1>Danh sách sản phẩm</h1>
<a href="/project1/Product/add" class="btn btn-success mb-2">Thêm sản phẩm mới</a>

<ul class="list-group">
    <?php foreach ($products as $product): ?>
        <li class="list-group-item">
            <!-- Hiển thị hình ảnh nếu có -->
            <?php if (!empty($product->image)): ?>
                <img src="/project1/public/uploads/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>"
                     alt="Ảnh sản phẩm"
                     style="width: 100px; height: auto; float: left; margin-right: 15px;">
            <?php endif; ?>

            <h2>
                <a href="/project1/Product/show/<?php echo $product->id; ?>">
                    <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </h2>
            <p><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></p>
            <p>Giá: <?php echo htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8'); ?>₫</p>
            <p><strong>Danh mục:</strong> <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?></p>

            <a href="/project1/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning">Sửa</a>
            <a href="/project1/Product/delete/<?php echo $product->id; ?>" 
               class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
            <div style="clear: both;"></div>
        </li>
    <?php endforeach; ?>
</ul>

<?php include 'app/views/shares/footer.php'; ?>
