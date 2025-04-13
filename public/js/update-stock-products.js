let currentProductId;

function openStockModal(id, stock, name) {
    currentProductId = id;
    const form = document.getElementById("updateStockForm");
    form.action = `products/${id}/save-stock`;
    document.getElementById("stockInput").value = stock;
    document.getElementById("productName").textContent = name;

    const modal = document.getElementById("updateStockModal");
    modal.classList.remove("hidden");
    modal.classList.add("flex");
}

function closeStockModal() {
    const modal = document.getElementById("updateStockModal");
    modal.classList.add("hidden");
    modal.classList.remove("flex");
    currentProductId = null;
}
