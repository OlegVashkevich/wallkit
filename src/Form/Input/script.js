document.addEventListener('click', e => {
    // Ищем ближайшую кнопку toggle от точки клика
    const toggleBtn = e.target.closest('.wallkit-input__toggle-password');
    if (!toggleBtn) return;
console.log(toggleBtn);
    const input = toggleBtn.closest('.wallkit-input').querySelector('input');
    if (!input) return;

    // Переключаем тип
    input.type = input.type === 'password' ? 'text' : 'password';

    // Меняем иконку
    toggleBtn.textContent = input.type === 'password' ? '👁️' : '🙈';
});