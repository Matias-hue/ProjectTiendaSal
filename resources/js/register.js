document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registerForm');
    const phone = document.getElementById('phone');

    form.addEventListener('submit', e => {
        if (!/^(\+\d{1,3}[- ]?)?\d{7,12}$/.test(phone.value)) {
            e.preventDefault();
            alert('Ingrese un teléfono válido (ej. +541234567890 o 1234567890).');
            phone.focus();
        }
    });
});