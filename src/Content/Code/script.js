document.addEventListener('DOMContentLoaded', function() {
    // Копирование кода
    document.querySelectorAll('.wallkit-code__copy-button').forEach(button => {
        button.addEventListener('click', function(e) {
            const codeBlock = this.closest('.wallkit-code');
            const code = codeBlock.querySelector('code').textContent;
            const originalText = this.textContent;
            const copiedText = this.dataset.copiedText || 'Скопировано!';

            // Копируем в буфер обмена
            navigator.clipboard.writeText(code).then(() => {
                // Показываем подтверждение
                this.textContent = copiedText;
                this.disabled = true;

                setTimeout(() => {
                    this.textContent = originalText;
                    this.disabled = false;
                }, 2000);
            }).catch(err => {
                console.error('Ошибка копирования:', err);
            });
        });
    });
});