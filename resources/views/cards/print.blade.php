<!doctype html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Друк карток 3 × 6 см</title>
    <style>
        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            background: #eeeef3;
            color: #252535;
            font: 15px Arial, sans-serif
        }

        main {
            max-width: 1250px;
            margin: auto;
            padding: 26px
        }

        h1 {
            margin: 0 0 10px;
            font-size: 27px
        }

        p {
            line-height: 1.5
        }

        .panel, .editor {
            background: white;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 18px
        }

        .toolbar, .settings {
            display: flex;
            gap: 12px;
            align-items: end;
            flex-wrap: wrap
        }

        button, .file-button {
            border: 0;
            border-radius: 7px;
            background: #5545ac;
            color: white;
            padding: 11px 15px;
            cursor: pointer;
            font: inherit;
            display: inline-block
        }

        button.secondary {
            background: #e9e6f5;
            color: #30265d
        }

        label {
            display: flex;
            flex-direction: column;
            gap: 5px;
            font-size: 13px
        }

        input, select, textarea {
            font: inherit;
            padding: 7px;
            border: 1px solid #ccc;
            border-radius: 5px;
            max-width: 100%
        }

        input[type=range] {
            padding: 0
        }

        input[type=number] {
            width: 85px
        }

        input[type=color] {
            width: 60px;
            height: 35px;
            padding: 2px
        }

        .editors {
            display: grid;
            grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));
            gap: 16px
        }

        .editor {
            margin: 0;
            display: flex;
            gap: 16px;
            align-items: start
        }

        .fields {
            display: grid;
            gap: 9px;
            min-width: 0;
            flex: 1
        }

        .card {
            position: relative;
            width: 30mm;
            height: 60mm;
            flex-shrink: 0;
            overflow: hidden;
            background: white;
            outline: 0.15mm solid #bbb;
            outline-offset: -0.15mm
        }

        .art {
            position: absolute;
            inset: 0;
            overflow: hidden
        }

        .art img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block
        }

        .caption {
            position: absolute;
            left: 0;
            right: 0;
            text-align: center;
            font-weight: bold;
            padding: 0 1.5mm;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
            line-height: 1.12;
            max-height: 100%;
            overflow: hidden
        }

        .back {
            display: flex;
            align-items: center;
            justify-content: center
        }

        .back .caption {
            color: #111 !important;
            background: none !important
        }

        .sheets {
            overflow-x: auto
        }

        .sheet {
            width: 210mm;
            height: 297mm;
            margin: 20px auto;
            background: white;
            position: relative;
            box-shadow: 0 3px 15px #0002
        }

        .grid {
            position: absolute;
            left: 15mm;
            top: 28.5mm;
            display: grid;
            grid-template-columns:repeat(6, 30mm);
            grid-template-rows:repeat(4, 60mm);
            width: 180mm;
            height: 240mm
        }

        .sheet-label {
            position: absolute;
            top: 8mm;
            left: 15mm;
            font-size: 12px;
            color: #777
        }

        .empty {
            color: #777
        }

        .hint {
            font-size: 13px;
            color: #626273
        }

        #status {
            min-height: 20px;
            color: #873616
        }

        a {
            color: #5545ac
        }

        @page {
            size: A4 portrait;
            margin: 0
        }

        @media print {
            html, body {
                margin: 0;
                background: white
            }

            main {
                max-width: none;
                padding: 0
            }

            .ui {
                display: none !important
            }

            .sheets {
                overflow: visible
            }

            .sheet {
                margin: 0;
                box-shadow: none;
                break-after: page;
                page-break-after: always
            }

            .sheet:last-child {
                break-after: auto;
                page-break-after: auto
            }

            .sheet-label {
                display: none
            }

            * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact
            }
        }
    </style>
</head>
<body>
<main>
    <div class="ui"><h1>Друк карток · 3 × 6 см</h1>
        <p>Додай картинки, впиши назви й обери кількість. На звороті кожної картки буде той самий напис.</p>
        <div class="panel">
            <div class="toolbar"><label class="file-button">Додати картинки<input id="images" type="file"
                                                                                  accept="image/*" multiple
                                                                                  hidden></label>
                <button id="blank" class="secondary">Картка без картинки</button>
                <button id="save" class="secondary">Зберегти набір</button>
                <label class="file-button">Відкрити набір<input id="load" type="file" accept=".json,application/json"
                                                                hidden></label>
                <button id="print">Друкувати</button>
            </div>
            <p class="hint">Картинки залишаються у браузері. Збережи набір у файл, щоб відкрити його знову.</p>
            <div class="settings"><label>Друкувати<select id="sides">
                        <option value="both">Лицьові + звороти</option>
                        <option value="front">Тільки лицьові</option>
                        <option value="back">Тільки звороти</option>
                    </select></label><label>Перевертання<select id="flip">
                        <option value="long">По довгому краю</option>
                        <option value="short">По короткому краю</option>
                    </select></label><label>Зміщення звороту X, мм<input id="dx" type="number" min="-10" max="10"
                                                                         step="0.5" value="0"></label><label>Зміщення
                    звороту Y, мм<input id="dy" type="number" min="-10" max="10" step="0.5" value="0"></label><label>Лінії
                    розрізу<select id="marks">
                        <option value="yes">Показувати</option>
                        <option value="no">Приховати</option>
                    </select></label></div>
            <p class="hint">Друк: А4, книжкова орієнтація, масштаб 100%, поля «Немає», колонтитули вимкнені, фонова
                графіка увімкнена. Для двостороннього друку обери у принтері той самий край перевертання. Спочатку
                перевір один аркуш. Додатне X зміщує зворот праворуч, Y — вниз, якщо дивитися на сторінку звороту.</p>
            <div id="status" role="status"></div>
        </div>
        <div id="editors" class="editors"></div>
        <p id="count"></p></div>
    <div id="sheets" class="sheets"></div>
</main>
<script>
    'use strict';
    const $ = id => document.getElementById(id);
    let cards = [];
    let serial = 0;
    const defaults = () => ({
        id: ++serial,
        text: '',
        image: '',
        copies: 1,
        position: 'bottom',
        offset: 2,
        backPosition: 'middle',
        backOffset: 0,
        font: 11,
        color: '#ffffff',
        shade: true,
        zoom: 1,
        x: 50,
        y: 50
    });

    function el(tag, cls, text) {
        const n = document.createElement(tag);
        if (cls) n.className = cls;
        if (text !== undefined) n.textContent = text;
        return n
    }

    function makeCard(c, back = false) {
        const n = el('div', 'card' + (back ? ' back' : ''));
        if ($('marks').value === 'no') n.style.outline = 'none';
        if (!back && c.image) {
            const a = el('div', 'art'), img = el('img');
            img.src = c.image;
            img.alt = '';
            img.style.objectPosition = `${c.x}% ${c.y}%`;
            img.style.transform = `scale(${c.zoom})`;
            img.style.transformOrigin = `${c.x}% ${c.y}%`;
            a.append(img);
            n.append(a)
        }
        const t = el('div', 'caption', c.text);
        t.style.fontSize = c.font + 'pt';
        if (!back) {
            t.style.color = c.color;
            t.style.background = c.shade ? 'rgba(0,0,0,.65)' : 'transparent';
        }
        const position = back ? c.backPosition : c.position;
        const offset = back ? c.backOffset : c.offset;
        if (position === 'top') t.style.top = offset + 'mm'; else if (position === 'middle') {
            t.style.top = '50%';
            t.style.transform = 'translateY(-50%)'
        } else t.style.bottom = offset + 'mm';
        t.style.maxHeight = (position === 'middle' ? 60 : 60 - offset) + 'mm';
        n.append(t);
        return n
    }

    function field(parent, c, key, title, type, opts) {
        const label = el('label', '', title);
        const input = el(type === 'select' ? 'select' : type === 'textarea' ? 'textarea' : 'input');
        if (type !== 'select' && type !== 'textarea') input.type = type;
        if (opts && type === 'select') opts.forEach(([value, text]) => {
            const o = el('option', '', text);
            o.value = value;
            input.append(o)
        }); else if (opts) Object.assign(input, opts);
        if (type === 'checkbox') input.checked = c[key]; else input.value = c[key];
        input.addEventListener('input', () => {
            c[key] = type === 'checkbox' ? input.checked : (type === 'number' || type === 'range' ? Number(input.value) : input.value);
            if (key === 'copies') c[key] = Math.max(1, Math.min(100, Math.floor(c[key] || 1)));
            if (key === 'offset' || key === 'backOffset') c[key] = Math.max(0, Math.min(59, c[key] || 0));
            if (key === 'font') c[key] = Math.max(5, Math.min(24, c[key] || 11));
            const p = parent.closest('.editor').querySelector('.card');
            p.replaceWith(makeCard(c));
            renderSheets()
        });
        label.append(input);
        parent.append(label)
    }

    function renderEditors() {
        const root = $('editors');
        root.replaceChildren();
        cards.forEach(c => {
            const row = el('div', 'editor');
            row.append(makeCard(c));
            const f = el('div', 'fields');
            row.append(f);
            field(f, c, 'text', 'Напис', 'textarea', {rows: 2, maxLength: 160});
            field(f, c, 'copies', 'Копій', 'number', {min: 1, max: 100});
            field(f, c, 'position', 'Лицьова: від якого краю', 'select', [['bottom', 'Від низу'], ['top', 'Від верху'], ['middle', 'По центру']]);
            field(f, c, 'offset', 'Лицьова: відступ, мм', 'number', {min: 0, max: 59, step: 0.5});
            field(f, c, 'backPosition', 'Зворот: від якого краю', 'select', [['bottom', 'Від низу'], ['top', 'Від верху'], ['middle', 'По центру']]);
            field(f, c, 'backOffset', 'Зворот: відступ, мм', 'number', {min: 0, max: 59, step: 0.5});
            f.append(el('span', 'hint', 'Відступ рахується до краю блоку напису. У режимі «По центру» відступ не застосовується.'));
            field(f, c, 'font', 'Розмір тексту, pt', 'number', {min: 5, max: 24});
            field(f, c, 'color', 'Колір тексту', 'color');
            field(f, c, 'shade', 'Темна підкладка', 'checkbox');
            field(f, c, 'zoom', 'Масштаб картинки', 'range', {min: 1, max: 3, step: .05});
            field(f, c, 'x', 'Кадрування по горизонталі', 'range', {min: 0, max: 100});
            field(f, c, 'y', 'Кадрування по вертикалі', 'range', {min: 0, max: 100});
            const del = el('button', 'secondary', 'Видалити');
            del.onclick = () => {
                cards = cards.filter(v => v !== c);
                renderEditors();
                renderSheets()
            };
            f.append(del);
            root.append(row)
        })
    }

    function renderSheets() {
        const expanded = cards.flatMap(c => Array(c.copies).fill(c));
        $('count').textContent = `Карток: ${expanded.length}. Аркушів А4: ${Math.ceil(expanded.length / 24)} (по 24 картки).`;
        $('sheets').replaceChildren();
        for (let start = 0; start < expanded.length; start += 24) {
            const batch = expanded.slice(start, start + 24);
            for (const back of [false, true]) {
                if ($('sides').value === 'front' && back || $('sides').value === 'back' && !back) continue;
                const sheet = el('div', 'sheet');
                sheet.append(el('div', 'sheet-label', `Аркуш ${start / 24 + 1} · ${back ? 'Зворот' : 'Лицьова сторона'}`));
                const grid = el('div', 'grid');
                if (back) grid.style.transform = `translate(${Number($('dx').value) || 0}mm,${Number($('dy').value) || 0}mm)`;
                for (let i = 0; i < 24; i++) {
                    let source = i;
                    if (back) source = $('flip').value === 'long' ? Math.floor(i / 6) * 6 + 5 - i % 6 : (3 - Math.floor(i / 6)) * 6 + i % 6;
                    const c = batch[source];
                    grid.append(c ? makeCard(c, back) : el('div'))
                }
                sheet.append(grid);
                $('sheets').append(sheet)
            }
        }
    }

    function refresh() {
        renderEditors();
        renderSheets()
    }

    const readFile = f => new Promise((resolve, reject) => {
        const r = new FileReader();
        r.onload = () => resolve(r.result);
        r.onerror = () => reject(new Error('Не вдалося прочитати файл'));
        r.readAsDataURL(f)
    });
    $('images').onchange = async e => {
        try {
            for (const file of e.target.files) {
                if (!file.type.startsWith('image/')) continue;
                const image = await readFile(file);
                await new Promise((ok, no) => {
                    const i = new Image();
                    i.onload = ok;
                    i.onerror = no;
                    i.src = image
                });
                cards.push({...defaults(), image, text: file.name.replace(/\.[^.]+$/, '')})
            }
            refresh();
            $('status').textContent = ''
        } catch {
            $('status').textContent = 'Не вдалося відкрити картинку. Спробуй PNG, JPEG або WebP.'
        }
        e.target.value = ''
    };
    $('blank').onclick = () => {
        cards.push(defaults());
        refresh()
    };
    $('save').onclick = () => {
        const settings = Object.fromEntries(['sides', 'flip', 'dx', 'dy', 'marks'].map(id => [id, $(id).value]));
        const b = new Blob([JSON.stringify({version: 1, cards, settings})], {type: 'application/json'});
        const u = URL.createObjectURL(b), a = el('a');
        a.href = u;
        a.download = 'cards-30x60.json';
        a.click();
        setTimeout(() => URL.revokeObjectURL(u), 1000)
    };
    $('load').onchange = async e => {
        try {
            const file = e.target.files[0];
            if (!file) return;
            const data = JSON.parse(await file.text());
            if (data.version !== 1 || !Array.isArray(data.cards) || data.cards.length > 500) throw Error();
            const next = data.cards.map(v => {
                if (typeof v.text !== 'string' || typeof v.image !== 'string' || (v.image && !/^data:image\/(png|jpeg|webp|gif|bmp|avif);base64,/i.test(v.image))) throw Error();
                return {
                    ...defaults(),
                    text: v.text.slice(0, 160),
                    image: v.image,
                    copies: Math.max(1, Math.min(100, Math.floor(Number(v.copies) || 1))),
                    font: Math.max(5, Math.min(24, Number(v.font) || 11)),
                    position: ['top', 'middle', 'bottom'].includes(v.position) ? v.position : 'bottom',
                    offset: Math.max(0, Math.min(59, Number(v.offset ?? 2) || 0)),
                    backPosition: ['top', 'middle', 'bottom'].includes(v.backPosition) ? v.backPosition : 'middle',
                    backOffset: Math.max(0, Math.min(59, Number(v.backOffset) || 0)),
                    color: /^#[0-9a-f]{6}$/i.test(v.color) ? v.color : '#ffffff',
                    shade: !!v.shade,
                    zoom: Math.max(1, Math.min(3, Number(v.zoom) || 1)),
                    x: Math.max(0, Math.min(100, Number(v.x) || 0)),
                    y: Math.max(0, Math.min(100, Number(v.y) || 0))
                }
            });
            cards = next;
            const s = data.settings || {};
            for (const id of ['sides', 'flip', 'marks']) if ([...$(id).options].some(o => o.value === s[id])) $(id).value = s[id];
            for (const id of ['dx', 'dy']) $(id).value = Math.max(-10, Math.min(10, Number(s[id]) || 0));
            refresh();
            $('status').textContent = 'Набір відкрито.'
        } catch {
            $('status').textContent = 'Не вдалося відкрити набір. Обери JSON-файл, збережений цією сторінкою.'
        }
        e.target.value = ''
    };
    $('print').onclick = async () => {
        if (!cards.length) {
            $('status').textContent = 'Додай хоча б одну картку.';
            return
        }
        renderSheets();
        await Promise.all([...$('sheets').querySelectorAll('img')].map(i => i.decode().catch(() => {
        })));
        window.print()
    };
    for (const id of ['sides', 'flip', 'dx', 'dy', 'marks']) $(id).oninput = refresh;
    refresh();
</script>
</body>
</html>
