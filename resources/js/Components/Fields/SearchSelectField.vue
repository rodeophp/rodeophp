<script setup>
import { computed, inject, onUnmounted, ref, watch } from 'vue';

const props = defineProps({ field: Object });
const model = defineModel();

const optionsBase = inject('saddleOptionsBase');

const options = ref([...(props.field.options ?? [])]);
const results = ref([]);
const search = ref('');
const open = ref(false);

const selectedLabel = computed(() => {
    const match = options.value.find((option) => option.value === model.value);
    return match ? match.label : null;
});

let timer;
watch(search, (value) => {
    clearTimeout(timer);
    timer = setTimeout(() => fetchOptions(value), 300);
});
onUnmounted(() => clearTimeout(timer));

async function fetchOptions(term = '') {
    const response = await fetch(`${optionsBase}/${props.field.name}?search=${encodeURIComponent(term)}`, {
        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    });
    if (!response.ok) return;
    results.value = (await response.json()).options;
    open.value = true;
}

function select(option) {
    model.value = option.value;
    options.value = [option];
    search.value = '';
    open.value = false;
}

function clear() {
    model.value = null;
    open.value = false;
}
</script>

<template>
    <div class="relative w-full max-w-lg" @keydown.esc="open = false">
        <div
            v-if="model != null && selectedLabel !== null"
            class="flex items-center justify-between rounded-lg border border-line-2 bg-bg px-3 py-2 text-sm"
        >
            <span>{{ selectedLabel }}</span>
            <button type="button" class="text-ink-3 hover:text-ink" aria-label="Clear selection" @click="clear">&times;</button>
        </div>
        <input
            v-else
            v-model="search"
            type="text"
            :placeholder="field.placeholder ?? 'Search…'"
            class="w-full rounded-lg border border-line-2 bg-bg px-3 py-2 text-sm"
            @focus="fetchOptions(search)"
        />
        <ul
            v-if="open && model == null"
            class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-lg border border-line bg-bg py-1 text-sm shadow-lg"
        >
            <li v-for="option in results" :key="option.value">
                <button type="button" class="w-full px-3 py-1.5 text-left hover:bg-surface" @click="select(option)">{{ option.label }}</button>
            </li>
            <li v-if="!results.length" class="px-3 py-1.5 text-ink-3">No matches</li>
        </ul>
    </div>
</template>
