document.addEventListener('DOMContentLoaded', () => {
    const search = document.getElementById('item-search');
    const categories = document.querySelectorAll('.items-category');
    const emptyMessage = document.getElementById('items-search-empty');

    if (!search) return;

    search.addEventListener('input', () => {
        const query = search.value.trim().toLocaleLowerCase('uk');

        let totalVisible = 0;

        categories.forEach(category => {
            let categoryVisible = 0;

            category.querySelectorAll('.items-group').forEach(group => {
                let groupVisible = 0;

                group.querySelectorAll('.items-type').forEach(type => {
                    let typeVisible = 0;

                    type.querySelectorAll('.items-list__item').forEach(item => {
                        const name = item
                            .querySelector('.items-list__name')
                            ?.textContent
                            .trim()
                            .toLocaleLowerCase('uk') ?? '';

                        const visible = name.includes(query);

                        item.hidden = !visible;

                        if (visible) {
                            typeVisible++;
                        }
                    });

                    type.hidden = typeVisible === 0;
                    groupVisible += typeVisible;
                });

                group.hidden = groupVisible === 0;
                categoryVisible += groupVisible;
            });

            category.hidden = categoryVisible === 0;
            totalVisible += categoryVisible;
        });

        if (emptyMessage) {
            emptyMessage.hidden = totalVisible !== 0;
        }
    });
});
