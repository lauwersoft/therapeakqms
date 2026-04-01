@php
    $isEditPage = $isEditPage ?? false;
    $metaFormAction = route('documents.update-meta');
@endphp

@if($meta['id'])
<div x-data="{ showMeta: false }" class="bg-white border border-gray-200 rounded-lg shadow-sm">
    <div class="px-4 sm:px-6 py-4 sm:py-5">
        <div class="flex items-start justify-between gap-4">
            {{-- 2-column metadata --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-xs flex-1 min-w-0">
                {{-- Left column --}}
                <div class="space-y-1.5">
                    <div class="flex items-center gap-2">
                        <span class="font-mono font-semibold text-sm px-1.5 py-0.5 rounded shrink-0 whitespace-nowrap {{ \App\Services\DocumentMetadata::typeColor($meta['type'] ?? '') }}">{{ $meta['id'] }}</span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-medium
                            {{ $meta['status'] === 'draft' ? 'bg-gray-100 text-gray-500' : '' }}
                            {{ $meta['status'] === 'in_review' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $meta['status'] === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $meta['status'] === 'obsolete' ? 'bg-red-100 text-red-600' : '' }}">{{ \App\Services\DocumentMetadata::STATUSES[$meta['status']] ?? ucfirst($meta['status']) }}</span>
                        @if($meta['version'])
                            <span class="text-gray-400">v{{ $meta['version'] }}</span>
                        @endif
                        @foreach(\App\Services\DocumentMetadata::normalizeCategory($meta['category'] ?? []) as $cat)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium {{ \App\Services\DocumentMetadata::categoryColor($cat) }}">{{ \App\Services\DocumentMetadata::categoryLabel($cat) }}</span>
                        @endforeach
                    </div>
                    @if($meta['author'])
                        <div class="text-gray-400"><span class="text-gray-500 font-medium">Author:</span> {{ $meta['author'] }}</div>
                    @endif
                    @if($meta['effective_date'])
                        <div class="text-gray-400"><span class="text-gray-500 font-medium">Effective:</span> {{ $meta['effective_date'] }}</div>
                    @endif
                    @if(isset($formSubmissions))
                        <div class="text-gray-400"><span class="text-gray-500 font-medium">Submissions:</span> {{ $formSubmissions ? $formSubmissions->count() : 0 }}</div>
                    @endif
                </div>
                {{-- Right column --}}
                <div class="space-y-1.5">
                    @if(!empty($meta['iso_refs']))
                        <div class="text-gray-400"><span class="text-gray-500 font-medium">ISO 13485:</span>
                            @foreach($meta['iso_refs'] as $ref)
                                <a href="{{ route('references.show', 'iso-13485') }}#{{ \Illuminate\Support\Str::slug($ref) }}" class="text-blue-500 hover:text-blue-700 underline decoration-blue-300 hover:decoration-blue-500">Clause {{ $ref }}</a>{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </div>
                    @endif
                    @if(!empty($meta['mdr_refs']))
                        <div class="text-gray-400"><span class="text-gray-500 font-medium">EU MDR:</span>
                            @foreach($meta['mdr_refs'] as $ref)
                                @php
                                    // Strip paragraph references like "(9)" for anchor — articles aren't split by paragraph
                                    $mdrAnchor = preg_replace('/\([\d]+\)/', '', $ref);
                                    $mdrAnchor = \Illuminate\Support\Str::slug(trim($mdrAnchor));
                                @endphp
                                <a href="{{ route('references.show', 'eu-mdr') }}#{{ $mdrAnchor }}" class="text-blue-500 hover:text-blue-700 underline decoration-blue-300 hover:decoration-blue-500">{{ $ref }}</a>{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </div>
                    @endif
                    @if($lastEdit ?? null)
                        <div class="text-gray-400">
                            <span class="text-gray-500 font-medium">Last edit:</span>
                            <a href="{{ route('documents.revision', $lastEdit['hash']) }}" class="text-blue-500 hover:text-blue-700 underline decoration-blue-300 hover:decoration-blue-500">{{ $lastEdit['name'] }}, {{ usertime($lastEdit['date'])->diffForHumans() }}</a>
                        </div>
                    @endif
                    @php
                        $commentCount = collect($docComments ?? [])->where('resolved', false)->count();
                        $requiredCount = collect($docComments ?? [])->where('type', 'required_change')->where('resolved', false)->count();
                    @endphp
                    @if($commentCount > 0)
                        <div class="text-gray-400">
                            <span class="text-gray-500 font-medium">Comments:</span>
                            <span class="{{ $requiredCount > 0 ? 'text-red-500 font-medium' : '' }}">{{ $commentCount }} open{{ $requiredCount > 0 ? " ({$requiredCount} required)" : '' }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Properties button --}}
            @if($canEdit ?? false)
                <div class="shrink-0">
                    <button type="button" @click="showMeta = !showMeta"
                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs rounded-md border transition-colors"
                            :class="showMeta ? 'bg-blue-50 border-blue-200 text-blue-600' : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50'">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Properties
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Editable properties panel --}}
    @php
        // Dynamically build reference suggestions from ALL files in qms/references/
        $refSources = [];
        $refDir = base_path('qms/references');
        if (is_dir($refDir)) {
            foreach (glob($refDir . '/*.md') as $refFile) {
                $filename = pathinfo($refFile, PATHINFO_FILENAME);
                $refContent = file_get_contents($refFile);
                $refTitle = $filename;
                if (preg_match('/^#\s+(.+)$/m', $refContent, $tm)) {
                    $refTitle = preg_replace('/\[\^\d+\]/', '', trim($tm[1]));
                }
                // Extract H2 and H3 headings as sections
                $sections = [];
                preg_match_all('/^#{2,3}\s+(.+)$/m', $refContent, $hm);
                foreach ($hm[1] as $heading) {
                    $heading = trim(strip_tags($heading));
                    if (strtolower($heading) === 'footnotes' || strtolower($heading) === 'recitals') continue;
                    $sections[] = $heading;
                }
                // Map to frontmatter field
                $field = 'other_refs';
                if (str_starts_with($filename, 'iso-13485')) $field = 'iso_refs';
                elseif (str_starts_with($filename, 'eu-mdr')) $field = 'mdr_refs';

                $refSources[] = [
                    'filename' => $filename,
                    'title' => $refTitle,
                    'field' => $field,
                    'sections' => $sections,
                ];
            }
            // Sort: ISO first, EU MDR, then MDCG, then others
            usort($refSources, function($a, $b) {
                $order = ['iso' => 0, 'eu-' => 1, 'mdcg' => 2];
                $oa = 3; $ob = 3;
                foreach ($order as $prefix => $val) {
                    if (str_starts_with($a['filename'], $prefix)) $oa = $val;
                    if (str_starts_with($b['filename'], $prefix)) $ob = $val;
                }
                return $oa !== $ob ? $oa <=> $ob : strnatcmp($a['filename'], $b['filename']);
            });
        }

        // Build existing refs as tags for the picker
        $existingRefs = [];
        foreach ($meta['iso_refs'] ?? [] as $ref) {
            $existingRefs[] = ['source' => 'iso-13485', 'section' => $ref, 'field' => 'iso_refs'];
        }
        foreach ($meta['mdr_refs'] ?? [] as $ref) {
            $existingRefs[] = ['source' => 'eu-mdr', 'section' => $ref, 'field' => 'mdr_refs'];
        }
    @endphp
    <div x-show="showMeta" x-cloak class="px-4 sm:px-6 py-4 border-t border-gray-100 bg-gray-50/50">
        <form method="POST" action="{{ $metaFormAction }}" x-data="refPicker()">
            @csrf
            @method('PUT')
            <input type="hidden" name="path" value="{{ $currentPath }}">
            <input type="hidden" name="redirect" value="{{ $isEditPage ? 'edit' : 'show' }}">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                    <select name="meta_status" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                        @foreach(\App\Services\DocumentMetadata::STATUSES as $key => $label)
                            <option value="{{ $key }}" {{ $meta['status'] === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Category</label>
                    @php $currentCats = \App\Services\DocumentMetadata::normalizeCategory($meta['category'] ?? []); @endphp
                    <div class="flex flex-wrap gap-2">
                        @foreach(\App\Services\DocumentMetadata::CATEGORIES as $key => $label)
                            <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                <input type="checkbox" name="meta_category[]" value="{{ $key }}"
                                       {{ in_array($key, $currentCats) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Version</label>
                    <input type="text" name="meta_version" value="{{ $meta['version'] }}"
                           class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Effective date</label>
                    <input type="date" name="meta_effective_date" value="{{ $meta['effective_date'] }}"
                           class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Author</label>
                    <input type="text" name="meta_author" value="{{ $meta['author'] }}"
                           class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            {{-- Regulatory References --}}
            <div class="mt-3">
                <label class="block text-xs font-medium text-gray-500 mb-1">Regulatory References</label>

                {{-- Current tags --}}
                <div class="flex flex-wrap gap-1.5 mb-2" x-show="refs.length > 0">
                    <template x-for="(ref, i) in refs" :key="i">
                        <span class="inline-flex items-center gap-1 pl-2 pr-1 py-0.5 text-xs rounded-full"
                              :class="ref.field === 'iso_refs' ? 'bg-blue-50 text-blue-700' : ref.field === 'mdr_refs' ? 'bg-emerald-50 text-emerald-700' : 'bg-purple-50 text-purple-700'">
                            <span class="font-medium" x-text="ref.source.replace('iso-13485','ISO 13485').replace('iso-14971','ISO 14971').replace('eu-mdr','EU MDR').replace(/^mdcg-/,'MDCG ')"></span>
                            <template x-if="ref.section">
                                <span class="text-gray-500" x-text="': ' + ref.section"></span>
                            </template>
                            <button type="button" @click.stop="refs.splice(i, 1)" class="ml-0.5 p-0.5 rounded-full hover:bg-gray-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </span>
                    </template>
                </div>

                {{-- Two-step picker: inline row --}}
                <div class="flex flex-wrap items-center gap-2">
                    <select x-model="pickerSource" @change="pickerSection = ''; pickerSearch = ''; pickerOpen = false; $nextTick(() => { if ($refs.sectionInput) $refs.sectionInput.focus(); })"
                            class="border-gray-300 rounded-md text-xs py-1.5 focus:ring-blue-500 focus:border-blue-500 flex-1 min-w-[160px]">
                        <option value="">Select reference...</option>
                        @foreach($refSources as $src)
                            <option value="{{ $src['filename'] }}">{{ \Illuminate\Support\Str::limit($src['title'], 50) }}</option>
                        @endforeach
                    </select>

                    <div class="relative flex-1 min-w-[160px]" x-show="pickerSource" x-cloak>
                        <input type="text" x-model="pickerSearch" x-ref="sectionInput"
                               @focus="pickerOpen = true" @click.stop
                               @keydown.enter.prevent="addFromPicker(); pickerSearch = ''; pickerOpen = false"
                               @keydown.escape="pickerOpen = false"
                               placeholder="Search sections..."
                               class="w-full border-gray-300 rounded-md text-xs py-1.5 focus:ring-blue-500 focus:border-blue-500">

                        <div x-show="pickerOpen && filteredSections.length > 0" x-cloak @click.outside="pickerOpen = false"
                             class="absolute z-50 left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                            <template x-for="section in filteredSections" :key="section">
                                <button type="button" @click="addRef(pickerSource, section); pickerSearch = ''; pickerOpen = false"
                                        class="w-full text-left px-3 py-2 text-xs hover:bg-gray-50 border-b border-gray-50 last:border-0 truncate"
                                        x-text="section">
                                </button>
                            </template>
                        </div>
                    </div>

                    <button type="button" x-show="pickerSource" x-cloak
                            @click="addRef(pickerSource, ''); pickerSource = ''"
                            class="px-2.5 py-1.5 text-xs bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200 whitespace-nowrap shrink-0">
                        + Whole doc
                    </button>
                </div>
            </div>

            {{-- Hidden inputs: map refs back to frontmatter fields --}}
            <template x-for="ref in refs.filter(r => r.field === 'iso_refs')" :key="'iso-' + ref.section">
                <input type="hidden" name="meta_iso_refs[]" :value="ref.section">
            </template>
            <template x-for="ref in refs.filter(r => r.field === 'mdr_refs')" :key="'mdr-' + ref.section">
                <input type="hidden" name="meta_mdr_refs[]" :value="ref.section">
            </template>

            <div class="flex justify-end mt-3">
                <button type="submit" class="px-3 py-1.5 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700">Save properties</button>
            </div>
        </form>

        <script>
            function refPicker() {
                var sources = @json($refSources);
                var sourcesMap = {};
                sources.forEach(function(s) { sourcesMap[s.filename] = s; });

                return {
                    refs: @json($existingRefs),
                    pickerSource: '',
                    pickerSection: '',
                    pickerSearch: '',
                    pickerOpen: false,
                    sourcesMap: sourcesMap,

                    get filteredSections() {
                        var src = this.sourcesMap[this.pickerSource];
                        if (!src) return [];
                        var q = this.pickerSearch.toLowerCase();
                        var existing = this.refs.filter(function(r) { return r.source === src.filename; }).map(function(r) { return r.section; });
                        return src.sections.filter(function(s) {
                            if (existing.indexOf(s) !== -1) return false;
                            if (!q) return true;
                            return s.toLowerCase().indexOf(q) !== -1;
                        }).slice(0, 20);
                    },

                    addRef(source, section) {
                        var src = this.sourcesMap[source];
                        if (!src) return;
                        // Check duplicate
                        var exists = this.refs.some(function(r) { return r.source === source && r.section === section; });
                        if (exists) return;
                        this.refs.push({ source: source, section: section, field: src.field });
                    },

                    addFromPicker() {
                        if (this.pickerSearch.trim()) {
                            this.addRef(this.pickerSource, this.pickerSearch.trim());
                        }
                    },
                };
            }
        </script>
    </div>
</div>
@endif
