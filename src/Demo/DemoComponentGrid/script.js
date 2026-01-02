document.addEventListener('DOMContentLoaded', function() {
    // Фильтрация по статусу
    const filterButtons = document.createElement('div');
    filterButtons.className = 'demo-filters';
    filterButtons.innerHTML = `
        <button class="demo-filter" data-filter="all">Все</button>
        <button class="demo-filter" data-filter="stable">Готовые</button>
        <button class="demo-filter" data-filter="planned">В планах</button>
    `;
    // Вставляем фильтры перед сеткой
    const grid = document.querySelector('.wallkit-demo-component-grid');
    if (grid) {
        grid.parentNode.insertBefore(filterButtons, grid);

        // Стили для фильтров
        const style = document.createElement('style');
        style.textContent = `
            .demo-filters {
                display: flex;
                gap: var(--wk-spacing-2);
                margin-bottom: var(--wk-spacing-6);
                flex-wrap: wrap;
            }

            .demo-filter {
                padding: var(--wk-spacing-2) var(--wk-spacing-4);
                background: var(--wk-white);
                border: 1px solid var(--wk-border);
                font-size: var(--wk-font-size-sm);
                color: var(--wk-medium-gray);
                cursor: pointer;
                transition: all var(--wk-transition-base);
            }

            .demo-filter:hover {
                border-color: var(--wk-accent);
                color: var(--wk-accent);
            }

            .demo-filter[data-active] {
                background: var(--wk-accent);
                color: var(--wk-white);
                border-color: var(--wk-accent);
            }
        `;
        document.head.appendChild(style);

        // Обработка фильтров
        document.querySelectorAll('.demo-filter').forEach(btn => {
            btn.addEventListener('click', function () {
                const filter = this.getAttribute('data-filter');

                // Обновляем активную кнопку
                document.querySelectorAll('.demo-filter').forEach(b => b.removeAttribute('data-active'));
                this.setAttribute('data-active', '');

                // Фильтруем компоненты
                document.querySelectorAll('.wallkit-demo-component-grid__item').forEach(item => {
                    if (filter === 'all') {
                        item.style.display = 'flex';
                    } else {
                        const status = item.getAttribute('data-status');
                        item.style.display = status === filter ? 'flex' : 'none';
                    }
                });

                // Обновляем счетчики групп
                document.querySelectorAll('.wallkit-demo-component-grid__group').forEach(group => {
                    const visibleItems = group.querySelectorAll('.wallkit-demo-component-grid__item[style*="display: flex"], .wallkit-demo-component-grid__item:not([style*="display: none"])');
                    const count = group.querySelector('.wallkit-demo-component-grid__group-count');
                    if (count && visibleItems.length > 0) {
                        count.textContent = visibleItems.length;
                        group.style.display = 'block';
                    } else if (count) {
                        count.textContent = '0';
                        group.style.display = 'none';
                    }
                });
            });
        });

        // Активируем "Все" по умолчанию
        document.querySelector('.demo-filter[data-filter="all"]').setAttribute('data-active', '');
    }
})