<template>
    <img v-bind:src="src" alt="" />
</template>

<script>
import SVGInjector from 'svg-injector';

export default {
    name: 'SVGIcon',
    props: {
        src: String
    },
    data: function() {
        return {

        }
    },
    mounted: function() {

        // Elements to inject SVG icons into
        var injectIcons = this.$el;

        var injectorOptions = {
            evalScripts: false,
            each: function() {
                // Callback after each SVG is injected
                //console.log('SVG injected: ' + svg.getAttribute('id'));
            }
        };

        // Inject SVG icons
        SVGInjector(injectIcons, injectorOptions, function() {
            //console.log('Total injections: ' + count);
        });
    }
}
</script>

<style scoped>

</style>
