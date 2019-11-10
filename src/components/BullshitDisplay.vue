<template>
    <div class="card bg-dark">
        <div class="card-header">
            {{ title }} <span v-if="this.items">({{ this.items.length }})</span>
        </div>
        <div class="card-body">
            <ul v-if="this.items">
                <li v-for="(item, index) in this.items" v-bind:key="index">
                    {{ item.message }}<br />
                    <span v-if="item.difference">
                        (Difference: {{ item.difference.toFixed(5) }})
                    </span>
                </li>
            </ul>

        </div>
    </div>
</template>

<script>

export default {
    name: 'BullshitDisplay',
    props: {
        title: String,
        items: Array
    },
    data: function() {
        return {

        }
    }
}
</script>

<style scoped>
ul {
    margin: 0;
    padding: 0;
}
li {
    list-style-type: none;
    margin-bottom: 1rem;
}
.card {
    height: 87.5vh;
    color: #a8b5c3;
    /*background-color: #2d3035;
    color: #8a8d93;*/

    /*background-color: #36404a;
    border-color: #36404a;
    color: #a8b5c3;*/

    /*background-color: #191c20;
    color: #c2c2c2;*/
}
.card-header {
    /*background-color: rgba(0, 0, 0, .1);*/
    font-size: 1.25rem;
    font-weight: bold;
}
.card-body {
    /*background-color: #2d3035;*/
    /*background-color: #36404a;*/
    /*background-color: #191c20;*/
    font-size: .9rem;
    overflow-y: scroll;
}

@media (max-width: 577px) {
    .card {
        height: 41.25vh;
        margin-bottom: 2.5vh
    }
}
@media (max-height: 480px) {
    
}

</style>
