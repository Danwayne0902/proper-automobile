<template>
    <input
        type="checkbox"
        :value="value"
        :checked="checked"
        @change="updateChecked"
        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
    />
</template>

<script>
export default {
    name: 'Checkbox',

    props: {
        checked: {
            type: [Array, Boolean],
            required: true
        },
        value: {
            default: null
        }
    },

    methods: {
        updateChecked(event) {
            this.$emit('update:checked', event.target.checked);
        }
    }
};
</script>
