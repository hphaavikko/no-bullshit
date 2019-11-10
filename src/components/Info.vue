<template>

    <div v-bind:class="{ info: true, open: open }">

        <div class="info-overlay"></div>

        <div class="info-panel-container">

            <div class="card bg-dark info-panel">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h2>Info</h2>

                    <Button label="Close" v-bind:onClick="toggleInfo" v-bind:iconBefore="require('../assets/images/icon-close.svg')" className="btn btn-link float-right p-0" labelSrOnly></Button>

                </div>

                <div class="card-body">

                    <p>&copy; 2019 Hape Haavikko</p>

                    <h3>Kootut selitykset</h3>

                    <p>Perkeleen koodihaaste! Näin sen typerän sponsoroidun postauksen Facebookissa enkä voinut vastustaa kiusausta. Tehtävänanto oli kyllä hauska, myönnettäköön.</p>

                    <p>Otin tavoitteeksi suoriutua tehtävästä ilman ulkopuolista sanakirjaa tai muuta järkyttävän isoa datamassaa. Suomen kielen yleisimpien sanojen sanastolla homman olisi varmaan voinut tehdä kyllä. Pienen tutkimisen jälkeen päädyin käyttämään kriteerinä kirjainten esiintymistiheyttä; tieto oli helposti saatavilla ja meni kompaktiin tilaan.</p>

                    <p>Kolmantena ajatuksena kokeilin postata sanat Googlelle ja tarkistaa montako hakutulosta tuli. Tämä olisi ollut kohtuullisen luotettava keino, mutta tuskaisen hidas. Ajatukseni oli käyttää tätä metodia karsimaan &quot;mukasuomea&quot; olevat lauseet pois sen jälkeen, kun kirjainten esiintymistiheyteen perustuva karsinta oli tehty, mutta luovuin siitä sen hitauden vuoksi.</p>

                    <p>Kirjainten esiintymistiheyteen perustuva algoritmi toimii ihan kivasti, joskin viilamalla sen saisi varmasti vielä paremmaksi. Suurin ongelma tässä lienee bullshit-lauseiden pituus; pidemmillä teksteillä homma toimisi varmasti luotettavammin.</p>

                    <p>Eihän tämä millään muotoa täydellinen ratkaisu ole, mutta ihan tolkullisia tuloksia kuitenkin tulee. Läpi pääsevät bullshitit ovat kuitenkin etäisesti suomenkielisen oloisia ja niiden seassa on suomen kielen sanoja.</p>

                    <p><em>hape.haavikko@fakiirimedia.com</em></p>

                    <p class="text-center">
                        <Button label="Sulje" v-bind:onClick="toggleInfo"></Button>
                    </p>

                </div> <!-- /.card-body -->

            </div> <!-- /.card -->

        </div>

    </div>

</template>

<script>
import {eventBus} from '../main.js';
import Button from './Button.vue';

export default {
    name: 'Info',
    components: {
        Button
    },
    data: function() {
        return {
            open: false
        }
    },
    created: function() {
        eventBus.$on('toggleInfo', () => {
            this.toggleInfo();
        });
    },
    methods: {
        toggleInfo: function() {
            this.open = !this.open;
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

.info {
    position: fixed;
    top: 0;
    right: 0;
    height: 0;
    left: 0;
    z-index: 999;
}

.info.open {
    height: auto;
    bottom: 0;
}

.info-overlay {
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

.info.open  .info-overlay {
    visibility: visible;
    opacity: 1;
}

.info-panel-container {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    padding: 15px;
    opacity: 0;
    transition: transform .5s, opacity .5s;
    transform: translateY(-100%);
}

.info.open .info-panel-container {
    opacity: 1;
    transform: translateX(0);
}

.info-panel {
    position: absolute;
    top: 15px;
    bottom: 15px;
    left: 50%;
    width: 700px;
    transform: translateX(-50%);
}

.card-body {
    overflow-y: auto;
}

@media (max-width: 768px) {
    .info-panel {
        left: 15px;
        right: 15px;
        width: auto;
        transform: none;
    }
}

</style>
