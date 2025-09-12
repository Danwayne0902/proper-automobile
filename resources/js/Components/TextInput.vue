<template>
    <input
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        :value="value"
        @input="updateValue"
        ref="input"
    />
</template>

<script>
export default {
    name: 'TextInput',

    props: {
        value: {
            type: String,
            default: ''
        }
    },

    mounted() {
        if (this.$refs.input && this.$refs.input.hasAttribute('autofocus')) {
            this.$refs.input.focus();
        }
    },

    methods: {
        updateValue(event) {
            this.$emit('input', event.target.value);
        },

        focus() {
            if (this.$refs.input) {
                this.$refs.input.focus();
            }
        }
    }
};
</script>
