<template>

    <div v-bind:class="{ settings: true, open: open }">

        <div class="settings-overlay"></div>

        <div class="card bg-dark settings-panel">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h2>Settings</h2>

                <Button label="Close" v-bind:onClick="toggleSettings" v-bind:iconBefore="require('../assets/images/icon-close.svg')" className="btn btn-link float-right p-0 mr-2" labelSrOnly></Button>

            </div>

            <div class="card-body">

                <div class="form-group">
                    <label for="order">No bullshits sort order</label><br />
                    <select id="order" v-on:change="setOrder($event)" class="form-control">
                        <option value="difference-asc">By difference (asc)</option>
                        <option value="difference-desc">By difference (desc)</option>
                        <option value="alphabet-asc">Alphabetically (asc)</option>
                        <option value="alphabet-desc">Alphabetically (desc)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="threshold">Difference threshold</label><br />
                    <input type="number" v-on:change="setThreshold($event)" id="threshold" min="0" step="0.0001" class="form-control" v-bind:placeholder="this.defaults.threshold" v-bind:value="threshold" />
                </div>

            </div> <!-- /.card-body -->

        </div> <!-- /.card -->

    </div>

</template>

<script>
import {eventBus} from '../main.js';
import Button from './Button.vue';

export default {
    name: 'Settings',
    components: {
        Button
    },
    props: {
        threshold: Number
    },
    data: function() {
        return {
            open: false,
            defaults: {
                threshold: this.threshold
            }
        }
    },
    created: function() {
        //eventBus.$emit('setThreshold', this.defaults.threshold);
        //console.log('threshold is ' + this.threshold);
        //console.log('default threshold is ' + this.defaults.threshold);

        eventBus.$on('toggleSettings', () => {
            //console.log('set threshold: ' + newThreshold);
            this.toggleSettings();
        });
    },
    methods: {
        toggleSettings: function() {
            this.open = !this.open;
        },
        setOrder: function(e) {
            eventBus.$emit('setOrder', e.target.value);
        },
        setThreshold: function(e) {
            var newThreshold = parseFloat(e.target.value);
            if (! newThreshold) {
                newThreshold = this.defaults.threshold;
            }
            eventBus.$emit('setThreshold', newThreshold);
        }
    }
}
</script>

<style scoped>

h2 {
    font-size: 1.25rem;
    font-weight: bold;
    line-height: 1.25rem;
    margin: 0;
}

.settings {
    position: fixed;
    z-index: 999;
}

.settings > .btn {
    position: fixed;
    top: 15px;
    right: 0;
}

.settings-overlay {
    visibility: hidden;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-color: rgba(0, 0, 0, .75);
    opacity: 0;
    transition: opacity .5s;
}

.settings.open > .settings-overlay {
    visibility: visible;
    opacity: 1;
}

.settings-panel {
    position: fixed;
    top: 10px;
    right: -325px;
    bottom: 10px;
    width: 325px;
    transition: right .5s;
}

.settings.open > .settings-panel {
    right: -5px;
}

.card-header {
    padding: .5rem .5rem .5rem .75rem;
}

</style>
