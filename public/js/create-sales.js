document.addEventListener('DOMContentLoaded', () => {
    // Show More
    const showMoreBtn = document.getElementById('show-more-btn');
    if (showMoreBtn) {
        showMoreBtn.addEventListener('click', () => {
            document.querySelectorAll('.product-item[style="display: none;"]').forEach((item,
                i) => {
                if (i < 10) item.style.display = 'block';
            });
            if (!document.querySelector('.product-item[style="display: none;"]')) showMoreBtn.style
                .display = 'none';
        });
    }

    updateTotals();
});

function changeQuantity(button, delta) {
    const input = button.parentElement.querySelector('.quantity-input');
    const max = parseInt(input.max);
    let value = parseInt(input.value) || 0;
    value = Math.max(0, Math.min(max, value + delta));
    input.value = value;
    updateTotals(input);
}

function updateTotals(input) {
    const format = (num) => new Intl.NumberFormat('id-ID').format(num);
    let totalProducts = 0,
        totalPrice = 0;

    document.querySelectorAll('.product-item').forEach(item => {
        const qty = parseInt(item.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(item.querySelector('.product-price').value);
        const subtotal = qty * price;
        item.querySelector('.subtotal-display').textContent = "Rp " + format(subtotal);
        if (qty > 0) {
            totalProducts++;
            totalPrice += subtotal;
        }
    });

    document.getElementById('selected-products-count').textContent = totalProducts;
    document.getElementById('total-price').textContent = "Rp " + format(totalPrice);
}
