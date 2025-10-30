<?php
// File: views/products.php
require_once '../includes/header.php';

// Cek login tanpa Auth class
if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Handle product operations
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    
    if (isset($_POST['add_product'])) {
        $name = sanitize($_POST['name']);
        $description = sanitize($_POST['description']);
        $price = $_POST['price'];
        $stock_quantity = $_POST['stock_quantity'];
        $category = sanitize($_POST['category']);
        $sku = sanitize($_POST['sku']);
        
        $query = "INSERT INTO products (user_id, name, description, price, stock_quantity, category, sku) 
                  VALUES (:user_id, :name, :description, :price, :stock_quantity, :category, :sku)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock_quantity', $stock_quantity);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':sku', $sku);
        
        if ($stmt->execute()) {
            $message = 'Produk berhasil ditambahkan!';
            $message_type = 'success';
        } else {
            $message = 'Gagal menambahkan produk. Silakan coba lagi.';
            $message_type = 'danger';
        }
    }
    
    if (isset($_POST['edit_product'])) {
        $product_id = $_POST['product_id'];
        $name = sanitize($_POST['name']);
        $description = sanitize($_POST['description']);
        $price = $_POST['price'];
        $stock_quantity = $_POST['stock_quantity'];
        $category = sanitize($_POST['category']);
        
        $query = "UPDATE products SET name = :name, description = :description, price = :price, 
                  stock_quantity = :stock_quantity, category = :category 
                  WHERE id = :id AND user_id = :user_id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock_quantity', $stock_quantity);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':id', $product_id);
        $stmt->bindParam(':user_id', $user_id);
        
        if ($stmt->execute()) {
            $message = 'Produk berhasil diperbarui!';
            $message_type = 'success';
        } else {
            $message = 'Gagal memperbarui produk. Silakan coba lagi.';
            $message_type = 'danger';
        }
    }
    
    if (isset($_POST['delete_product'])) {
        $product_id = $_POST['product_id'];
        
        $query = "DELETE FROM products WHERE id = :id AND user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $product_id);
        $stmt->bindParam(':user_id', $user_id);
        
        if ($stmt->execute()) {
            $message = 'Produk berhasil dihapus!';
            $message_type = 'success';
        } else {
            $message = 'Gagal menghapus produk. Silakan coba lagi.';
            $message_type = 'danger';
        }
    }
}

// Get all products
$database = new Database();
$db = $database->getConnection();
$query = "SELECT * FROM products WHERE user_id = :user_id ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Manajemen Produk</h2>
            
            <!-- Message Alert -->
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <div class="row">
                <!-- Add Product Form -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Tambah Produk Baru</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Produk</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="price" class="form-label">Harga (Rp)</label>
                                        <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="stock_quantity" class="form-label">Stok</label>
                                        <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="category" class="form-label">Kategori</label>
                                        <input type="text" class="form-control" id="category" name="category">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="sku" class="form-label">SKU</label>
                                        <input type="text" class="form-control" id="sku" name="sku">
                                    </div>
                                </div>
                                
                                <button type="submit" name="add_product" class="btn btn-primary w-100">
                                    <i class="fas fa-plus me-2"></i>Tambah Produk
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Products List -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Daftar Produk</h5>
                        </div>
                        <div class="card-body">
                            <?php if (count($products) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Nama</th>
                                                <th>Harga</th>
                                                <th>Stok</th>
                                                <th>Kategori</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($products as $product): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                                    <?php if ($product['sku']): ?>
                                                        <br><small class="text-muted">SKU: <?php echo htmlspecialchars($product['sku']); ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>Rp <?php echo number_format($product['price'], 2); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo $product['stock_quantity'] > 0 ? 'success' : 'danger'; ?>">
                                                        <?php echo $product['stock_quantity']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($product['category']): ?>
                                                        <span class="badge bg-info"><?php echo htmlspecialchars($product['category']); ?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-warning" 
                                                            data-bs-toggle="modal" data-bs-target="#editProductModal"
                                                            onclick="editProduct(<?php echo $product['id']; ?>, '<?php echo addslashes($product['name']); ?>', '<?php echo addslashes($product['description']); ?>', <?php echo $product['price']; ?>, <?php echo $product['stock_quantity']; ?>, '<?php echo addslashes($product['category']); ?>')">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    
                                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                        <button type="submit" name="delete_product" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <h5>Belum ada produk</h5>
                                    <p class="text-muted">Tambahkan produk pertama Anda menggunakan form di samping.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="edit_product_id">
                    
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_price" class="form-label">Harga (Rp)</label>
                            <input type="number" class="form-control" id="edit_price" name="price" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_stock_quantity" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="edit_stock_quantity" name="stock_quantity" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_category" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="edit_category" name="category">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="edit_product" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editProduct(id, name, description, price, stock, category) {
    document.getElementById('edit_product_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_price').value = price;
    document.getElementById('edit_stock_quantity').value = stock;
    document.getElementById('edit_category').value = category;
}
</script>

<?php require_once '../includes/footer.php'; ?>