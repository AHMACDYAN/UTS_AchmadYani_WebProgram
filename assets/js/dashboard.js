// Dashboard functionality
function showProductForm() {
    const form = document.getElementById('productForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

// Add product form
const addProductForm = document.getElementById('addProductForm');
if (addProductForm) {
    addProductForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'create');
        
        fetch('../processes/product_crud.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menambah produk');
        });
    });
}

// Delete product
function deleteProduct(productId) {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('product_id', productId);
        
        fetch('../processes/product_crud.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus produk');
        });
    }
}

// Edit product (basic implementation)
function editProduct(productId) {
    alert('Fitur edit produk akan datang! Product ID: ' + productId);
    // Implementasi edit bisa ditambahkan later
}