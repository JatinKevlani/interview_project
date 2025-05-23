document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const hasHobbies = form.querySelector('input[name="hobbies[]"]');
        form.addEventListener('submit', function (e) {
            const password = form.querySelector('[name="password"]');
            const confirmPassword = form.querySelector('[name="confirm_password"]');
            if (password && confirmPassword && password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Passwords do not match.');
                return;
            }
            if (hasHobbies) {
                const hobbies = form.querySelectorAll('input[name="hobbies[]"]:checked');
                if (hobbies.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one hobby.');
                }
            }
        });
    });
});