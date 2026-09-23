document.addEventListener('DOMContentLoaded', () => {
    const search = document.getElementById('spell-search');

    if (!search) return;

    const levels = document.querySelectorAll('.spell-level');

    search.addEventListener('input', () => {
        const query = search.value.trim().toLocaleLowerCase('uk');

        levels.forEach(level => {
            const items = level.querySelectorAll('.spell-list__item');

            let visibleCount = 0;

            items.forEach(item => {
                const name = item.querySelector('.spell-list__name')
                    ?.textContent
                    .trim()
                    .toLocaleLowerCase('uk') ?? '';

                const visible = name.includes(query);

                item.hidden = !visible;

                if (visible) {
                    visibleCount++;
                }
            });

            // Приховуємо весь розділ рівня, якщо збігів немає.
            level.hidden = visibleCount === 0;
        });
    });
});
