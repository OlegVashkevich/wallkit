document.addEventListener('DOMContentLoaded', function() {
    // Навигация по группам
    document.querySelectorAll('.wallkit-demo-sidebar__nav-item').forEach(link => {
        link.addEventListener('click', function (e) {
            const href = this.getAttribute('href');

            if (href.startsWith('#')) {
                e.preventDefault();
                const targetId = href.substring(1);

                if (targetId === 'components') {
                    // Прокрутка к началу
                    window.scrollTo({top: 0, behavior: 'smooth'});
                } else {
                    // Поиск группы
                    const groupElement = document.querySelector(`[data-group="${targetId}"]`);
                    if (groupElement) {
                        groupElement.scrollIntoView({behavior: 'smooth', block: 'start'});
                    }
                }

                // Обновляем активную ссылку
                document.querySelectorAll('.wallkit-demo-sidebar__nav-item')
                    .forEach(a => a.classList.remove('wallkit-demo-sidebar__nav-item--active'));
                this.classList.add('wallkit-demo-component-grid__item-status--active');
            }
            // Внешние ссылки открываются как обычно
        });
    })
})