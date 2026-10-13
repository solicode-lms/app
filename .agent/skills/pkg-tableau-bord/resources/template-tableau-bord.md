# Template Composant Tableau de Bord (V2 Tailwind / Alpine.js)

## Modèle KPI Stat Card
```html
<div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $titre }}</span>
        <span class="p-2 rounded-lg {{ $badgeColorClass }}">
            {{ $iconeSvg }}
        </span>
    </div>
    <div class="mt-3 flex items-baseline gap-2">
        <span class="text-2xl font-bold text-slate-900">{{ $valeur }}</span>
        <span class="text-xs font-medium {{ $tendanceColorClass }}">{{ $tendance }}</span>
    </div>
    <p class="mt-1 text-xs text-slate-500">{{ $description }}</p>
</div>
```

## Modèle File d'Attente FIFO (Live Coding)
```html
<div x-data="{ items: [] }" class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-semibold text-slate-900">File d'Attente Live Coding (Prototype)</h3>
        <span class="text-xs bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-full font-medium" x-text="items.length + ' en attente'"></span>
    </div>
    <ul class="divide-y divide-slate-100">
        <template x-for="(item, index) in items" :key="item.id">
            <li class="py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs font-bold" x-text="index + 1"></span>
                    <span class="text-sm font-medium text-slate-900" x-text="item.apprenant_nom"></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-500" x-text="item.timer"></span>
                    <button @click="appeler(item.id)" class="px-3 py-1 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700">Appeler</button>
                </div>
            </li>
        </template>
    </ul>
</div>
```
