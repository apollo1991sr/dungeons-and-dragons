@php
    $facts = collect();
    foreach ([
        'itemCategory' => 'Категорія',
        'itemClass' => 'Клас',
        'itemType' => 'Тип',
    ] as $relation => $label) {
        $classification = $item->{$relation};

        if ($classification) {
            $facts->push([
                'label' => $label,
                'value' => $classification->label(),
                'icon' => $classification->icon(),
            ]);
        }
    }
    if ($item->rarity) $facts->push(['label' => 'Рідкісність', 'value' => $item->rarity->label(), 'icon' => 'rarity']);
    if ($item->proficiency) $facts->push(['label' => 'Спеціалізація', 'value' => $item->proficiency, 'icon' => 'proficiency']);
    if ($item->weight !== null) $facts->push(['label' => 'Вага', 'value' => ($item->weight + 0) . ' кг', 'icon' => 'weight']);
    if ($item->price !== null) $facts->push(['label' => 'Ціна', 'value' => (string) ($item->price + 0), 'icon' => 'price']);
    if ($item->action) $facts->push(['label' => 'Активація', 'value' => $item->action->label(), 'icon' => $item->action->icon()]);
    if ($item->single_use) $facts->push(['label' => 'Використання', 'value' => 'Одноразове', 'icon' => 'single-use']);

    $damageRows = collect();
    $effects = collect();
    foreach ($item->damage ?? [] as $damage) {
        if (!empty($damage['value'])) $damageRows->push(['prefix' => '', 'value' => $damage['value'], 'icon' => $damage['icon'] ?? null]);
    }
    foreach ($item->stats ?? [] as $stat) {
        if (empty($stat['value'])) continue;
        $label = trim($stat['label'] ?? '');
        if (preg_match('/^Шкода(?=\s|:|\+|$)/ui', $label)) {
            $prefix = trim(preg_replace('/^Шкода\s*:?\s*/ui', '', $label));
            $damageRows->push(['prefix' => $prefix, 'value' => $stat['value'], 'icon' => $stat['icon'] ?? null]);
        } else {
            $effects->push(['label' => $label, 'value' => $stat['value'], 'icon' => $stat['icon'] ?? null]);
        }
    }
    foreach ($item->attributes ?? [] as $attribute) {
        $value = $attribute['html'] ?? $attribute['value'] ?? null;
        if (!empty($value)) $effects->push(['label' => $attribute['label'] ?? '', 'value' => $value, 'icon' => $attribute['icon'] ?? null]);
    }
@endphp

<article class="item-card pi-theme-{{ $item->rarity?->value ?? 'common' }}" aria-label="{{ $item->name }}">
    <header class="item-card__header">
        <h1>{{ $item->name }}</h1>
    </header>

    @if($item->image)
        <figure class="item-card__art pi-image">
            <img src="{{ $item->imageUrlFor(276) }}" srcset="{{ $item->imageSrcset(276) }}" alt="{{ $item->name }}">
        </figure>
    @endif

    <div class="item-card__summary">
        @if($facts->isNotEmpty())
            <dl class="item-card__facts">
                @foreach($facts as $fact)
                    <div class="item-card__fact">
                        <dt>{{ $fact['label'] }}</dt>
                        <dd>
                            @if($fact['icon'])<span class="icon {{ $fact['icon'] }}" aria-hidden="true"></span>@endif
                            {{ $fact['value'] }}
                        </dd>
                    </div>
                @endforeach
            </dl>
        @endif
        @if($damageRows->isNotEmpty() || $effects->isNotEmpty())
            <dl class="item-card__stats">
                @foreach($damageRows as $damage)
                    <div class="item-card__fact item-card__stat">
                        <dt>{{ $loop->first ? 'Шкода' : '' }}</dt>
                        <dd>
                            @if($damage['prefix'])<span>{{ $damage['prefix'] }}</span> @endif
                            @if($damage['icon'])<span class="icon {{ $damage['icon'] }}" aria-hidden="true"></span> @endif
                            {!! $damage['value'] !!}
                        </dd>
                    </div>
                @endforeach
                @foreach($effects as $effect)
                    <div class="item-card__fact item-card__stat">
                        <dt>{{ $effect['label'] }}</dt>
                        <dd>
                            @if($effect['icon'])<span class="icon {{ $effect['icon'] }}" aria-hidden="true"></span> @endif
                            @if(trim(strip_tags($effect['value'])) !== $effect['label'] || !$effect['icon']){!! $effect['value'] !!}@endif
                        </dd>
                    </div>
                @endforeach
            </dl>
        @endif
    </div>

    <div class="item-card__body">
        @foreach($item->content ?? [] as $block)
            @if(!empty($block['title']) || !empty($block['html']))
                <section class="item-card__section">
                    @if(!empty($block['title']))<h2>{{ $block['title'] }}</h2>@endif
                    {!! $block['html'] ?? '' !!}
                </section>
            @endif
        @endforeach
    </div>
</article>
