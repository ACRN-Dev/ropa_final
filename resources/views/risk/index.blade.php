@extends('layouts.admin')

@section('title', 'Admin | Risk Processing Activities')

@section('content')

@php
    $allRisks   = \App\Models\EnterpriseRisk::with(['owner', 'ropa'])->whereNull('deleted_at')->get();
    $low        = $allRisks->where('risk_level', 'low');
    $medium     = $allRisks->where('risk_level', 'medium');
    $high       = $allRisks->where('risk_level', 'high');
    $critical   = $allRisks->where('risk_level', 'critical');

    $buckets = [
        'low'      => ['label' => 'Low Risk',      'color' => 'green',  'icon' => 'shield',         'items' => $low,      'score' => '1–5'],
        'medium'   => ['label' => 'Medium Risk',   'color' => 'yellow', 'icon' => 'alert-circle',   'items' => $medium,   'score' => '6–11'],
        'high'     => ['label' => 'High Risk',     'color' => 'orange', 'icon' => 'alert-triangle',  'items' => $high,     'score' => '12–19'],
        'critical' => ['label' => 'Critical Risk', 'color' => 'red',    'icon' => 'zap',             'items' => $critical, 'score' => '20–25'],
    ];

    $colorMap = [
        'green'  => ['bg' => '#f0fdf4', 'border' => '#86efac', 'badge' => '#16a34a', 'badgebg' => '#dcfce7', 'header' => '#15803d', 'pill' => '#bbf7d0'],
        'yellow' => ['bg' => '#fefce8', 'border' => '#fde047', 'badge' => '#ca8a04', 'badgebg' => '#fef9c3', 'header' => '#a16207', 'pill' => '#fef08a'],
        'orange' => ['bg' => '#fff7ed', 'border' => '#fdba74', 'badge' => '#ea580c', 'badgebg' => '#ffedd5', 'header' => '#c2410c', 'pill' => '#fed7aa'],
        'red'    => ['bg' => '#fef2f2', 'border' => '#fca5a5', 'badge' => '#dc2626', 'badgebg' => '#fee2e2', 'header' => '#b91c1c', 'pill' => '#fecaca'],
    ];
@endphp

<!-- ── PAGE HEADER ── -->
<div class="hidden md:flex items-center justify-between mb-5 lg:mb-6">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 leading-tight">Risk Activities</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Drag and drop risks between buckets to reclassify them.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('risk-register.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600
                  text-gray-700 dark:text-gray-300 hover:bg-gray-50 font-semibold rounded-lg transition text-sm shadow-sm">
            <i data-feather="list" class="w-4 h-4"></i>
            Risk Register
        </a>
        <a href="{{ route('risk-register.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600
                  text-white font-semibold rounded-lg transition text-sm shadow-sm">
            <i data-feather="plus" class="w-4 h-4"></i>
            New Risk
        </a>
    </div>
</div>

<!-- Mobile header -->
<div class="md:hidden mb-4">
    <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">Risk Activities</h1>
    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Drag risks between buckets to reclassify.</p>
</div>

<!-- ── STAT CARDS ── -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border-l-4 border-green-500 shadow-sm flex items-center justify-between gap-2">
        <div><p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Low</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $low->count() }}</p></div>
        <i data-feather="shield" class="w-7 h-7 text-green-500 flex-shrink-0"></i>
    </div>
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border-l-4 border-yellow-400 shadow-sm flex items-center justify-between gap-2">
        <div><p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Medium</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $medium->count() }}</p></div>
        <i data-feather="alert-circle" class="w-7 h-7 text-yellow-500 flex-shrink-0"></i>
    </div>
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border-l-4 border-orange-500 shadow-sm flex items-center justify-between gap-2">
        <div><p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">High</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $high->count() }}</p></div>
        <i data-feather="alert-triangle" class="w-7 h-7 text-orange-500 flex-shrink-0"></i>
    </div>
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border-l-4 border-red-500 shadow-sm flex items-center justify-between gap-2">
        <div><p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Critical</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $critical->count() }}</p></div>
        <i data-feather="zap" class="w-7 h-7 text-red-500 flex-shrink-0"></i>
    </div>
</div>

<!-- ── PIPELINE PROGRESS BAR (like the screenshot) ── -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-4 mb-6">
    <div class="flex items-center gap-2 mb-3">
        <i data-feather="activity" class="w-4 h-4 text-orange-500"></i>
        <span class="text-sm font-bold text-gray-700 dark:text-gray-200">Risk Distribution</span>
        <span class="ml-auto text-xs text-gray-400">Total: {{ $allRisks->count() }} risks</span>
    </div>
    <div class="flex items-stretch gap-1 h-8 rounded-lg overflow-hidden">
        @foreach(['low'=>[$low->count(),'#22c55e'],'medium'=>[$medium->count(),'#eab308'],'high'=>[$high->count(),'#f97316'],'critical'=>[$critical->count(),'#ef4444']] as $level => [$count, $color])
            @if($count > 0)
                @php $pct = $allRisks->count() > 0 ? round(($count / $allRisks->count()) * 100) : 0; @endphp
                <div class="flex items-center justify-center text-white text-xs font-bold transition-all"
                     style="width:{{ $pct }}%; background:{{ $color }}; min-width: {{ $count > 0 ? '32px' : '0' }}">
                    {{ $count > 0 ? $count : '' }}
                </div>
            @endif
        @endforeach
        @if($allRisks->count() === 0)
            <div class="flex-1 bg-gray-100 flex items-center justify-center text-xs text-gray-400">No risks yet</div>
        @endif
    </div>
    <div class="flex items-center gap-4 mt-2">
        @foreach(['Low'=>'#22c55e','Medium'=>'#eab308','High'=>'#f97316','Critical'=>'#ef4444'] as $label => $color)
            <span class="flex items-center gap-1.5 text-xs text-gray-500">
                <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:{{ $color }}"></span>
                {{ $label }}
            </span>
        @endforeach
    </div>
</div>

<!-- ── BULK ACTION BAR (appears when items selected) ── -->
<div id="bulkBar"
     class="hidden sticky top-2 z-30 mb-4 bg-white dark:bg-gray-800 border border-orange-300 rounded-xl shadow-lg px-4 py-3
            flex items-center gap-3 flex-wrap">
    <span class="text-sm font-bold text-orange-600">
        <span id="selectedCount">0</span> risk(s) selected
    </span>
    <span class="text-gray-300">|</span>
    <span class="text-xs text-gray-500 font-semibold">Move to:</span>
    @foreach(['low'=>'Low','medium'=>'Medium','high'=>'High','critical'=>'Critical'] as $level => $label)
        <button onclick="bulkMove('{{ $level }}')"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition border
                       {{ $level === 'low' ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100' :
                          ($level === 'medium' ? 'bg-yellow-50 text-yellow-700 border-yellow-200 hover:bg-yellow-100' :
                          ($level === 'high' ? 'bg-orange-50 text-orange-700 border-orange-200 hover:bg-orange-100' :
                          'bg-red-50 text-red-700 border-red-200 hover:bg-red-100')) }}">
            {{ $label }}
        </button>
    @endforeach
    <button onclick="clearSelection()"
            class="ml-auto inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-gray-500
                   bg-gray-100 hover:bg-gray-200 rounded-lg transition">
        <i data-feather="x" class="w-3 h-3"></i> Clear
    </button>
</div>

<!-- ── BUCKETS ── -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4" id="bucketsGrid">
    @foreach($buckets as $level => $bucket)
    @php $c = $colorMap[$bucket['color']]; @endphp
    <div class="bucket-column flex flex-col rounded-xl border-2 overflow-hidden shadow-sm"
         data-level="{{ $level }}"
         style="border-color: {{ $c['border'] }}; background: {{ $c['bg'] }};">

        <!-- Bucket header -->
        <div class="px-4 py-3 flex items-center justify-between flex-shrink-0"
             style="background: {{ $c['header'] }};">
            <div class="flex items-center gap-2">
                <i data-feather="{{ $bucket['icon'] }}" class="w-4 h-4 text-white flex-shrink-0"></i>
                <div>
                    <p class="text-sm font-bold text-white leading-tight">{{ $bucket['label'] }}</p>
                    <p class="text-xs text-white/70">Score: {{ $bucket['score'] }}</p>
                </div>
            </div>
            <span class="bucket-count text-white font-bold text-lg leading-none"
                  id="count-{{ $level }}">{{ $bucket['items']->count() }}</span>
        </div>

        <!-- Drop zone -->
        <div class="bucket-drop flex-1 p-2 space-y-2 min-h-[200px] transition-colors"
             data-level="{{ $level }}"
             ondragover="handleDragOver(event)"
             ondragleave="handleDragLeave(event)"
             ondrop="handleDrop(event, '{{ $level }}')">

            @forelse($bucket['items'] as $risk)
            <div class="risk-card group relative bg-white dark:bg-gray-800 rounded-lg border shadow-sm
                        cursor-grab active:cursor-grabbing select-none transition-all
                        hover:shadow-md hover:-translate-y-0.5"
                 draggable="true"
                 data-id="{{ $risk->id }}"
                 data-level="{{ $level }}"
                 ondragstart="handleDragStart(event)"
                 ondragend="handleDragEnd(event)"
                 style="border-color: {{ $c['border'] }};">

                <!-- Checkbox + drag handle row -->
                <div class="flex items-center gap-2 px-3 pt-2.5 pb-0">
                    <input type="checkbox"
                           class="risk-checkbox w-3.5 h-3.5 rounded accent-orange-500 flex-shrink-0 cursor-pointer"
                           data-id="{{ $risk->id }}"
                           data-level="{{ $level }}"
                           onchange="handleCheckboxChange()"
                           onclick="event.stopPropagation()">
                    <span class="text-xs font-mono text-gray-400 flex-1 truncate">{{ $risk->risk_id }}</span>
                    <i data-feather="more-vertical" class="w-3.5 h-3.5 text-gray-300 group-hover:text-gray-400 flex-shrink-0"></i>
                </div>

                <!-- Card body -->
                <div class="px-3 pb-3 pt-1.5">
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 leading-tight mb-1.5 line-clamp-2">
                        {{ $risk->title }}
                    </p>

                    @if($risk->department)
                        <p class="text-xs text-gray-500 mb-2 flex items-center gap-1">
                            <i data-feather="briefcase" class="w-3 h-3 flex-shrink-0"></i>
                            {{ $risk->department }}
                        </p>
                    @endif

                    <div class="flex flex-wrap gap-1 mb-2">
                        <!-- Status badge -->
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold"
                              style="background: {{ $c['badgebg'] }}; color: {{ $c['badge'] }};">
                            {{ ucfirst(str_replace('_', ' ', $risk->status ?? 'open')) }}
                        </span>
                        <!-- Score badge -->
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-600">
                            Score: {{ $risk->inherent_risk_score ?? '—' }}
                        </span>
                    </div>

                    <!-- Owner + actions -->
                    <div class="flex items-center justify-between mt-1">
                        @if($risk->owner)
                            <div class="flex items-center gap-1.5">
                                <div class="w-5 h-5 rounded-full bg-gradient-to-br from-orange-400 to-orange-600
                                            flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($risk->owner->name, 0, 1)) }}
                                </div>
                                <span class="text-xs text-gray-500 truncate max-w-[80px]">{{ $risk->owner->name }}</span>
                            </div>
                        @else
                            <span class="text-xs text-gray-300">Unassigned</span>
                        @endif
                        <div class="flex items-center gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('risk-register.show', $risk->id) }}"
                               class="p-1 text-blue-500 hover:bg-blue-50 rounded transition"
                               onclick="event.stopPropagation()" title="View">
                                <i data-feather="eye" class="w-3.5 h-3.5"></i>
                            </a>
                            <a href="{{ route('risk-register.edit', $risk->id) }}"
                               class="p-1 text-orange-500 hover:bg-orange-50 rounded transition"
                               onclick="event.stopPropagation()" title="Edit">
                                <i data-feather="edit-2" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-placeholder flex flex-col items-center justify-center py-10 text-center">
                <i data-feather="{{ $bucket['icon'] }}" class="w-8 h-8 mb-2" style="color: {{ $c['border'] }}"></i>
                <p class="text-xs font-semibold" style="color: {{ $c['badge'] }}">No {{ strtolower($bucket['label']) }} items</p>
                <p class="text-xs text-gray-400 mt-0.5">Drop cards here</p>
            </div>
            @endforelse
        </div>
    </div>
    @endforeach
</div>

<!-- ── PROCESSING ACTIVITIES TABLE ── -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mt-6">
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-4 lg:px-6 py-3 lg:py-4">
        <h2 class="text-base lg:text-lg font-bold text-white flex items-center gap-2">
            <i data-feather="database" class="w-5 h-5 flex-shrink-0"></i>
            Processing Activities
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Organisation</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">Department</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Processing Activities</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">Risk Level</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse(\App\Models\Ropa::with('enterpriseRisks')->latest()->take(20)->get() as $ropa)
                @php
                    $rl = $ropa->risk_level;
                    $rlStyle = match($rl) {
                        'critical' => ['bg-red-100 text-red-700 border-red-200',    'zap'],
                        'high'     => ['bg-orange-100 text-orange-700 border-orange-200', 'alert-triangle'],
                        'medium'   => ['bg-yellow-100 text-yellow-700 border-yellow-200', 'alert-circle'],
                        'low'      => ['bg-green-100 text-green-700 border-green-200',   'shield'],
                        default    => ['bg-gray-100 text-gray-500 border-gray-200',      'minus'],
                    };
                    $sc = match($ropa->status) {
                        'Reviewed','Approved' => 'bg-green-100 text-green-700 border-green-200',
                        'Rejected'            => 'bg-red-100 text-red-700 border-red-200',
                        default               => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                    };
                    $activities = is_array($ropa->processes) ? implode(', ', $ropa->processes) : ($ropa->processes ?? '—');
                @endphp
                <tr class="hover:bg-orange-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="flex-shrink-0 w-7 h-7 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg
                                        flex items-center justify-center text-white text-xs font-bold">
                                <i data-feather="briefcase" class="w-3.5 h-3.5"></i>
                            </div>
                            <span class="font-semibold text-xs text-gray-900 dark:text-gray-100">{{ $ropa->organisation_name ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        <span class="text-xs text-gray-600 dark:text-gray-300">{{ $ropa->department ?? '—' }}</span>
                    </td>
                    <td class="px-4 py-3 max-w-xs">
                        <span class="text-xs text-gray-700 dark:text-gray-300 line-clamp-2" title="{{ $activities }}">{{ $activities }}</span>
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold border {{ $rlStyle[0] }}">
                            <i data-feather="{{ $rlStyle[1] }}" class="w-3 h-3"></i>
                            {{ ucfirst($rl) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold border {{ $sc }}">
                            {{ $ropa->status ?? 'Pending' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('ropa.show', $ropa->id) }}"
                               class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View">
                                <i data-feather="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('ropa.edit', $ropa->id) }}"
                               class="p-1.5 text-orange-500 hover:bg-orange-50 rounded-lg transition" title="Edit">
                                <i data-feather="edit-2" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-16 text-center">
                        <i data-feather="inbox" class="w-10 h-10 text-gray-300 mx-auto mb-2"></i>
                        <p class="text-sm text-gray-400">No processing activities found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600 flex items-center justify-between">
        <p class="text-xs text-gray-500">Showing latest 20 ROPA records</p>
        <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-orange-500 hover:underline">View all →</a>
    </div>
</div>

<style>
    .bucket-drop.drag-over {
        background: rgba(249, 115, 22, 0.08) !important;
        outline: 2px dashed #f97316;
        outline-offset: -2px;
    }
    .risk-card.dragging { opacity: 0.4; transform: scale(0.97); }
    .risk-card.selected { outline: 2px solid #f97316; outline-offset: 1px; }
    .line-clamp-2 { display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden; }
</style>

<script>
    feather.replace();

    var CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ── Drag state ──────────────────────────────────────────────
    var draggedId    = null;
    var draggedLevel = null;
    var draggedEl    = null;

    function handleDragStart(e) {
        draggedId    = e.currentTarget.dataset.id;
        draggedLevel = e.currentTarget.dataset.level;
        draggedEl    = e.currentTarget;
        e.currentTarget.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', draggedId);
    }

    function handleDragEnd(e) {
        e.currentTarget.classList.remove('dragging');
        document.querySelectorAll('.bucket-drop').forEach(function(z) { z.classList.remove('drag-over'); });
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        e.currentTarget.classList.add('drag-over');
    }

    function handleDragLeave(e) {
        // Only remove if leaving the drop zone itself, not a child
        if (!e.currentTarget.contains(e.relatedTarget)) {
            e.currentTarget.classList.remove('drag-over');
        }
    }

    function handleDrop(e, newLevel) {
        e.preventDefault();
        e.currentTarget.classList.remove('drag-over');

        if (!draggedId || newLevel === draggedLevel) return;

        moveRisk(draggedId, newLevel, function() {
            // Move the card DOM element to the new bucket
            var drop = document.querySelector('.bucket-drop[data-level="' + newLevel + '"]');
            if (drop && draggedEl) {
                // Remove empty placeholder if present
                var placeholder = drop.querySelector('.empty-placeholder');
                if (placeholder) placeholder.remove();

                draggedEl.dataset.level = newLevel;
                drop.appendChild(draggedEl);

                // Add empty placeholder to old bucket if now empty
                var oldDrop = document.querySelector('.bucket-drop[data-level="' + draggedLevel + '"]');
                if (oldDrop && oldDrop.querySelectorAll('.risk-card').length === 0) {
                    addEmptyPlaceholder(oldDrop, draggedLevel);
                }

                draggedLevel = newLevel;
                updateCounts();
                feather.replace();
            }
        });
    }

    // ── Multi-select ─────────────────────────────────────────────
    var selectedIds = new Set();

    function handleCheckboxChange() {
        selectedIds.clear();
        document.querySelectorAll('.risk-checkbox:checked').forEach(function(cb) {
            selectedIds.add(cb.dataset.id);
        });
        // Highlight selected cards
        document.querySelectorAll('.risk-card').forEach(function(card) {
            if (selectedIds.has(card.dataset.id)) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });
        // Show/hide bulk bar
        var bar = document.getElementById('bulkBar');
        var countEl = document.getElementById('selectedCount');
        if (selectedIds.size > 0) {
            bar.classList.remove('hidden');
            bar.classList.add('flex');
            countEl.textContent = selectedIds.size;
        } else {
            bar.classList.add('hidden');
            bar.classList.remove('flex');
        }
    }

    function clearSelection() {
        document.querySelectorAll('.risk-checkbox:checked').forEach(function(cb) { cb.checked = false; });
        document.querySelectorAll('.risk-card.selected').forEach(function(c) { c.classList.remove('selected'); });
        selectedIds.clear();
        document.getElementById('bulkBar').classList.add('hidden');
        document.getElementById('bulkBar').classList.remove('flex');
    }

    function bulkMove(newLevel) {
        if (selectedIds.size === 0) return;

        var ids = Array.from(selectedIds);
        var moved = 0;

        ids.forEach(function(id) {
            var card = document.querySelector('.risk-card[data-id="' + id + '"]');
            if (!card || card.dataset.level === newLevel) { moved++; checkDone(); return; }

            moveRisk(id, newLevel, function() {
                var oldLevel  = card.dataset.level;
                var drop      = document.querySelector('.bucket-drop[data-level="' + newLevel + '"]');
                var oldDrop   = document.querySelector('.bucket-drop[data-level="' + oldLevel + '"]');

                if (drop) {
                    var placeholder = drop.querySelector('.empty-placeholder');
                    if (placeholder) placeholder.remove();
                    card.dataset.level = newLevel;
                    var cb = card.querySelector('.risk-checkbox');
                    if (cb) cb.dataset.level = newLevel;
                    drop.appendChild(card);
                }

                if (oldDrop && oldDrop.querySelectorAll('.risk-card').length === 0) {
                    addEmptyPlaceholder(oldDrop, oldLevel);
                }

                moved++;
                checkDone();
            });
        });

        function checkDone() {
            if (moved === ids.length) {
                updateCounts();
                clearSelection();
                feather.replace();
            }
        }
    }

    // ── API call ─────────────────────────────────────────────────
    function moveRisk(id, newLevel, callback) {
        fetch('/risk-register/' + id, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ risk_level: newLevel, _partial: true })
        })
        .then(function(res) {
            if (!res.ok) throw new Error('Server error: ' + res.status);
            return res.json();
        })
        .then(function() { if (callback) callback(); })
        .catch(function(err) {
            console.error('Move failed:', err);
            showToast('Failed to move risk. Please try again.', 'error');
        });
    }

    // ── Helpers ──────────────────────────────────────────────────
    function updateCounts() {
        ['low','medium','high','critical'].forEach(function(level) {
            var drop  = document.querySelector('.bucket-drop[data-level="' + level + '"]');
            var count = drop ? drop.querySelectorAll('.risk-card').length : 0;
            var el    = document.getElementById('count-' + level);
            if (el) el.textContent = count;
        });
    }

    var emptyIcons = { low:'shield', medium:'alert-circle', high:'alert-triangle', critical:'zap' };
    var emptyLabels = { low:'No low risk items', medium:'No medium risk items', high:'No high risk items', critical:'No critical risk items' };

    function addEmptyPlaceholder(drop, level) {
        var div = document.createElement('div');
        div.className = 'empty-placeholder flex flex-col items-center justify-center py-10 text-center';
        div.innerHTML = '<p class="text-xs font-semibold text-gray-400">' + (emptyLabels[level] || 'Empty') + '</p>'
                      + '<p class="text-xs text-gray-300 mt-0.5">Drop cards here</p>';
        drop.appendChild(div);
    }

    function showToast(msg, type) {
        var t = document.createElement('div');
        t.className = 'fixed bottom-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-sm font-semibold text-white transition-all '
                    + (type === 'error' ? 'bg-red-500' : 'bg-green-500');
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(function() { t.remove(); }, 3500);
    }
</script>

@endsection