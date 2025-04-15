let currentProductId = null;

function openDeleteModal(productId, productName) {
    currentProductId = productId;
    document.getElementById('deleteProductName').textContent = productName;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    currentProductId = null;
}

function deleteProduct() {
    if (!currentProductId) return;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch(`/products/${currentProductId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    
    .then(response => {
        if (response.ok) {
            closeDeleteModal();
            window.location.reload();
        } else {
            alert('Gagal menghapus produk');
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}
