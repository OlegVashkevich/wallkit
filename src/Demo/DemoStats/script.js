document.addEventListener('DOMContentLoaded', function() {
// Анимация статистики
    const statsItems = document.querySelectorAll('.wallkit-demo-stats__item-value');
    statsItems.forEach(item => {
        const finalValue = parseInt(item.textContent);
        let current = 0;
        const increment = finalValue / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= finalValue) {
                current = finalValue;
                clearInterval(timer);
            }
            item.textContent = Math.round(current).toString();
        }, 20);
    });
});