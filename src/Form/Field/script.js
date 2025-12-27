document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    document.querySelectorAll('.wallkit-field__toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const wrapper = this.closest('.wallkit-field__wrapper');
            const input = wrapper.querySelector('.wallkit-input__field');

            if (input.type === 'password') {
                input.type = 'text';
                this.setAttribute('aria-label', 'Скрыть пароль');
                this.textContent = '🙈';
            } else {
                input.type = 'password';
                this.setAttribute('aria-label', 'Показать пароль');
                this.textContent = '👁️';
            }
        });
    });
});