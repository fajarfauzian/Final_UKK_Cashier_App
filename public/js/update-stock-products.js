let currentProductId = null;

function openStockModal(productId, currentStock, productName) {
    currentProductId = productId;
    document.getElementById('updateStockForm').action = 'products/' + productId + '/save-stock';
    document.getElementById('stockInput').value = currentStock;
    document.getElementById('productName').textContent = productName;
    document.getElementById('updateStockModal').classList.remove('hidden');
}

function closeStockModal() {
    document.getElementById('updateStockModal').classList.add('hidden');
    currentProductId = null;
}

document.getElementById('saveStockButton').addEventListener('click', () => {
    document.getElementById('updateStockForm').submit();
});