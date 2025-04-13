function updateFormAction() {
    const form = document.getElementById('confirmForm');
    const isMember = document.querySelector('input[name="is_member"]:checked').value === '1';
    form.action = isMember ? `/sales/process-member` : `/sales/process-transaction`;
    document.getElementById('phoneSection').style.display = isMember ? 'block' : 'none';
}

function formatCurrency(input) {
    let value = input.value.replace(/\D/g, '');
    input.value = 'Rp ' + (parseInt(value) || 0).toLocaleString('id-ID');
    document.getElementById('amount_paid').value = value || 0;
}

document.addEventListener('DOMContentLoaded', updateFormAction);