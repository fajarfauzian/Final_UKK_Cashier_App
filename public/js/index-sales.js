document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.show-details').forEach(btn => {
        btn.addEventListener('click', () => {
            fetch(btn.dataset.url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    document.getElementById('modal-content').innerHTML = html;
                    document.getElementById('saleDetailModal').classList.remove(
                        'hidden');
                })
                .catch(() => alert('Gagal memuat detail penjualan'));
        });
    });
});

function openDeleteModal(id) {
    document.getElementById('deleteModal' + id).classList.remove('hidden');
}

function closeDeleteModal(id) {
    document.getElementById('deleteModal' + id).classList.add('hidden');
}