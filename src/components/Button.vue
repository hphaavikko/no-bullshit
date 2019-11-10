<template>
    <button v-bind:type="type" v-bind:class="className" v-on:click="onClick">
        <SVGIcon v-if="iconBefore" v-bind:src="iconBefore" class="icon-before"></SVGIcon>
        <span v-bind:class="{ 'sr-only': labelSrOnly }">{{ label }}</span>
        <SVGIcon v-if="iconAfter" v-bind:src="iconAfter" class="icon-after"></SVGIcon>
    </button>
</template>

<script>
import SVGIcon from './SVGIcon.vue';

export default {
    name: 'Button',
    components: {
        SVGIcon
    },
    props: {
        type: {
            type: String,
            default: 'button'
        },
        label: String,
        labelSrOnly: Boolean,
        iconBefore: String,
        iconAfter: String,
        className: {
            type: String,
            default: 'btn btn-primary'
        },
        onClick: {
            type: Function,
            default: function() {

            }
        }
    },
    data: function() {
        return {

        }
    },
    methods: {

    }
}
</script>

<style scoped>

</style>
