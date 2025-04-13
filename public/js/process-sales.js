document.getElementById('customer_name').addEventListener('input', function() {
    const customerName = this.value.trim();
    const usePointsCheckbox = document.getElementById('use_points');
    const pointsMessage = document.getElementById('points-message');
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!customerName) {
        usePointsCheckbox.disabled = true;
        pointsMessage.textContent = 'Anda belum bisa memakai poin di pembelian pertama.';
        pointsMessage.className = 'text-xs mt-1 text-gray-500';
        return;
    }

    // Kirim AJAX request untuk cek apakah nama pelanggan sudah ada
    fetch(`/sales/check-membership-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token, // Tambahkan CSRF token secara eksplisit
            },
            body: JSON.stringify({
                customer_name: customerName,
                check_only: true,
            }),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(
                    `Network response was not ok: ${response.status} ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            if (data.hasPreviousPurchase) {
                usePointsCheckbox.disabled = false;
                pointsMessage.textContent = 'Poin dapat digunakan untuk pembelian ini.';
                pointsMessage.className = 'text-xs mt-1 text-green-500';
            } else {
                usePointsCheckbox.disabled = true;
                pointsMessage.textContent = 'Anda belum bisa memakai poin di pembelian pertama.';
                pointsMessage.className = 'text-xs mt-1 text-gray-500';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            usePointsCheckbox.disabled = true;
            pointsMessage.textContent = 'Terjadi kesalahan saat memeriksa status poin: ' + error
            .message;
            pointsMessage.className = 'text-xs mt-1 text-red-500';
        });
});