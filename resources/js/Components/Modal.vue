<template>
    <div>
        <dialog
            class="z-50 m-0 min-h-full min-w-full overflow-y-auto bg-transparent backdrop:bg-transparent"
            ref="dialog"
        >
            <div
                class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0"
                scroll-region
            >
                <transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-show="show"
                        class="fixed inset-0 transform transition-all"
                        @click="close"
                    >
                        <div
                            class="absolute inset-0 bg-gray-500 opacity-75"
                        />
                    </div>
                </transition>

                <transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div
                        v-show="show"
                        class="mb-6 transform overflow-hidden rounded-lg bg-white shadow-xl transition-all sm:mx-auto sm:w-full"
                        :class="maxWidthClass"
                    >
                        <slot v-if="showSlot" />
                    </div>
                </transition>
            </div>
        </dialog>
    </div>
</template>

<script>
export default {
    name: 'Modal',

    props: {
        show: {
            type: Boolean,
            default: false
        },
        maxWidth: {
            type: String,
            default: '2xl'
        },
        closeable: {
            type: Boolean,
            default: true
        }
    },

    data() {
        return {
            showSlot: this.show
        };
    },

    computed: {
        maxWidthClass() {
            return {
                sm: 'sm:max-w-sm',
                md: 'sm:max-w-md',
                lg: 'sm:max-w-lg',
                xl: 'sm:max-w-xl',
                '2xl': 'sm:max-w-2xl'
            }[this.maxWidth];
        }
    },

    watch: {
        show(newVal) {
            if (newVal) {
                document.body.style.overflow = 'hidden';
                this.showSlot = true;

                this.$nextTick(() => {
                    if (this.$refs.dialog && this.$refs.dialog.showModal) {
                        this.$refs.dialog.showModal();
                    }
                });
            } else {
                document.body.style.overflow = '';

                setTimeout(() => {
                    if (this.$refs.dialog && this.$refs.dialog.close) {
                        this.$refs.dialog.close();
                    }
                    this.showSlot = false;
                }, 200);
            }
        }
    },

    mounted() {
        document.addEventListener('keydown', this.closeOnEscape);
    },

    beforeDestroy() {
        document.removeEventListener('keydown', this.closeOnEscape);
        document.body.style.overflow = '';
    },

    methods: {
        close() {
            if (this.closeable) {
                this.$emit('close');
            }
        },

        closeOnEscape(e) {
            if (e.key === 'Escape') {
                e.preventDefault();

                if (this.show) {
                    this.close();
                }
            }
        }
    }
};
</script>
