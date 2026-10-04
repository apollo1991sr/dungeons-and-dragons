(() => {
    'use strict';
    const form = document.querySelector('#item-print-form');
    if (!form) return;
    const rows = document.querySelector('#item-print-rows');
    const template = document.querySelector('#item-print-row-template');
    const printButton = document.querySelector('#item-print-button');
    const status = document.querySelector('#item-print-status');
    const warnings = document.querySelector('#item-print-warnings');
    const addButton = document.querySelector('#item-print-add');
    let dirty = false;
    const renumber = () => {
        [...rows.children].forEach((row, index) => {
            row.querySelectorAll('[data-field]').forEach(input => {
                input.name = `rows[${index}][${input.dataset.field}]`;
            });
            row.querySelector('[data-remove]').disabled = rows.children.length === 1;
        });
        addButton.disabled = rows.children.length >= 50;
    };
    const changed = () => {
        dirty = true;
        printButton.disabled = true;
        status.textContent = 'Список змінено. Натисніть «Сформувати картки», щоб оновити аркуші.';
    };
    addButton.addEventListener('click', () => {
        if (rows.children.length >= 50) return;
        rows.append(template.content.cloneNode(true));
        renumber();
        changed();
        rows.lastElementChild.querySelector('input').focus();
    });
    rows.addEventListener('click', event => {
        const button = event.target.closest('[data-remove]');
        if (!button || rows.children.length === 1) return;
        button.closest('.item-print-row').remove();
        renumber();
        changed();
    });
    rows.addEventListener('input', event => {
        if (!event.target.matches('.item-print-search')) {
            changed();
            return;
        }
        const select = event.target.closest('.item-print-row').querySelector('select');
        // Keep the selection, even when it doesn't match the new search.
        const term = event.target.value.trim().toLocaleLowerCase('uk');
        const source = template.content.querySelector('select');
        const current = select.value;
        const options = [...source.options].filter(option => !option.value || option.value === current || option.textContent.toLocaleLowerCase('uk').includes(term));
        select.replaceChildren(...options.map(option => option.cloneNode(true)));
        select.value = current;
    });
    rows.addEventListener('change', event => {
        if (event.target.matches('[data-field]')) changed();
    });
    form.addEventListener('submit', event => {
        renumber();
        const total = [...rows.querySelectorAll('[data-field="quantity"]')].reduce((sum, input) => sum + Number(input.value), 0);
        if (total > 200) {
            event.preventDefault();
            status.textContent = 'За один раз можна надрукувати до 200 карток.';
        }
    });
    const images = [...document.querySelectorAll('.item-print-sheets img')];
    const ready = Promise.all([
        document.fonts ? document.fonts.ready : Promise.resolve(),
        ...images.map(image => image.complete ? Promise.resolve() : new Promise(resolve => {
            image.addEventListener('load', resolve, { once: true });
            image.addEventListener('error', resolve, { once: true });
        })),
    ]);
    const checkCards = () => {
        const overflowing = new Set();
        document.querySelectorAll('.item-print-slot').forEach(slot => {
            const card = slot.querySelector('.item-card');
            const overflow = card.scrollHeight > card.clientHeight + 2 || card.scrollWidth > card.clientWidth + 2;
            slot.toggleAttribute('data-overflow', overflow);
            if (overflow) overflowing.add(slot.dataset.name);
        });
        const messages = [];
        if (overflowing.size) messages.push(`Текст не вміщується у картку: ${[...overflowing].join(', ')}. Скоротіть вміст або змініть оформлення перед друком.`);
        if (images.some(image => !image.complete || image.naturalWidth === 0)) messages.push('Деякі зображення ще не завантажилися або недоступні. Перевірте їх перед друком.');
        warnings.textContent = messages.join(' ');
        warnings.hidden = messages.length === 0;
        return messages.length === 0;
    };
    ready.then(checkCards);
    window.addEventListener('beforeprint', checkCards);
    printButton.addEventListener('click', async () => {
        printButton.disabled = true;
        status.textContent = 'Підготовка зображень і шрифтів…';
        // Avoid hanging forever if an external image server doesn't respond.
        let timer;
        await Promise.race([ready, new Promise(resolve => { timer = setTimeout(resolve, 15000); })]);
        clearTimeout(timer);
        if (dirty) return;
        printButton.disabled = false;
        status.textContent = 'Попередній перегляд сформовано.';
        if (!checkCards() && !window.confirm('Є попередження щодо вмісту карток. Усе одно друкувати?')) return;
        window.print();
    });
    renumber();
})();
