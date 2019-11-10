<template>
    <div id="app">

        <div class="status-overlay flex-column d-flex justify-content-center align-items-center" v-if="this.status !== 'Ready'">

            <div class="spinner-grow text-primary" role="status">
                <span class="sr-only">{{ this.status }}</span>
            </div>

            <p>{{ this.status }}</p>

        </div> <!-- /.status-overlay -->

        <header class="container-fluid d-flex justify-content-between align-items-center">

            <h1 class="text-hide flex-grow-1">No Bullshit!</h1>

            <div class="buttons">
                <Button
                label="Info"
                v-bind:onClick="toggleInfo"
                v-bind:iconBefore="require('./assets/images/icon-info.svg')"
                className="btn btn-link"
                labelSrOnly></Button>
                <Button
                label="Settings"
                v-bind:onClick="toggleSettings"
                v-bind:iconBefore="require('./assets/images/icon-settings.svg')"
                className="btn btn-link"
                labelSrOnly></Button>
            </div>

        </header>

        <main>
            <Settings v-bind:threshold="this.threshold"></Settings>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <BullshitDisplay title="Bullshit" v-bind:items="bullshit" />
                    </div>
                    <div class="col-sm-6">
                        <BullshitDisplay title="No bullshit" v-bind:items="noBullshit" />
                    </div>
                </div>
            </div> <!-- /.container-fluid -->

            <Info></Info>

        </main>

    </div>
</template>

<script>
import {eventBus} from './main.js';
import axios from 'axios';
import BullshitDisplay from './components/BullshitDisplay.vue';
import Button from './components/Button.vue';
import Info from './components/Info.vue';
import Settings from './components/Settings.vue';
import characterFreq from './assets/data/characterFreq.json';

export default {
    name: 'App',
    components: {
        BullshitDisplay,
        Button,
        Info,
        Settings
    },
    data: function() {
        return {
            status: 'Loading',
            alphabets: 'abcdefghijklmnopqrstuvwxyzåäö'.split(''),
            allBullshits: [],
            bullshit: [],
            noBullshit: [],
            threshold: 0.0125,
            sortOrder: 'difference-asc',
            googleCheck: true
        }
    },
    created: function() {

        eventBus.$on('setThreshold', (newThreshold) => {
            this.setThreshold(newThreshold);
        });

        eventBus.$on('setOrder', (newOrder) => {
            this.setOrder(newOrder);
        });

    },
    mounted: function() {
        // Go get some bullshit!
        this.getBullshit();
    },
    methods: {
        getBullshit: function() {
            this.status = 'Loading';

            axios.get(process.env.VUE_APP_PUBLIC_PATH + 'get-bullshit.php')
            .then((response) => {
                this.allBullshits = response.data.bullshits;
                this.processBullshit();
            })
            .catch((error) => {
                console.error(error);
            });
        },
        processBullshit: function() {
            this.status = 'Processing';
            this.bullshit = this.allBullshits.slice();
            this.noBullshit = [];

            //this.bullshit.unshift({message: 'Tähän voi laitella suomenkielisiä lauseita jos haluaa testailla jee jee. Olisi hyvä olla vähän pidempi lause niin kirjainten esiintymistiheyteen perustuva algoritmi toimisi luotettavammin.'});

            for (let i = 0; i < this.bullshit.length; i++)
            {
                var str = this.bullshit[i].message;

                for (let j = 0; j < this.alphabets.length; j++)
                {
                    var decrypted = this.decryptCaesar(str, j);
                    var difference = this.calculateDifference(decrypted, 'fi');

                    if (difference <= this.threshold)
                    {
                        //console.log('no bullshit: ' + decrypted);
                        this.bullshit.splice(i, 1);
                        this.noBullshit.push({
                            message: decrypted,
                            difference: difference
                        });
                    }
                }
            }

            // Sort
            switch (this.sortOrder) {
                case 'difference-asc':
                    this.noBullshit = this.noBullshit.sort((a, b) => { return a.difference - b.difference;});
                    break;
                case 'difference-desc':
                    this.noBullshit = this.noBullshit.sort((a, b) => { return b.difference - a.difference;});
                    break;
                case 'alphabet-asc':
                    this.noBullshit = this.noBullshit.sort((a, b) => { return a.message.toLowerCase() > b.message.toLowerCase()});
                    break;
                case 'alphabet-desc':
                    this.noBullshit = this.noBullshit.sort((a, b) => { return a.message.toLowerCase() < b.message.toLowerCase()});
                    break;
            }

            /*if (this.googleCheck)
            {
                for (let i = 0; i < this.noBullshit.length; i++)
                {
                    var words = this.noBullshit[i].message.split(" ");

                    for (let j = 0; j < words.length; j++)
                    {
                        this.getGoogleResults(words[j]);
                    }
                }
            }*/

            this.status = 'Ready';
        },
        /**
         * @param   {string}    str
         * @param   {string}    lan
         * @returns {number}
         */
        calculateDifference: function(str, lan) {
            var difference = 0;

            for (let i = 0; i < characterFreq[lan].length; i++) {
                var char = characterFreq[lan][i].char;
                var expectedFreq = characterFreq[lan][i].freq / 100;
                var freq = this.getCharFreq(char, str);

                if (freq > 0)
                {
                    difference += (freq - expectedFreq)**2;
                }
                else
                {
                    difference += (expectedFreq)**2;
                }
                
            }

            return difference;
        },
        /**
         * @param   {string}    str
         * @param   {string}    lan
         * @returns {string}
         */
        decryptCaesar: function(str, key) {

            var decrypted = '';

            for (let i = 0; i < str.length; i++)
            {
                var charIndex = this.alphabets.indexOf(str.charAt(i).toLowerCase());
                var replaceIndex = charIndex + key;

                if (charIndex > -1)
                {
                    if (replaceIndex >= this.alphabets.length)
                    {
                        replaceIndex = replaceIndex - this.alphabets.length;
                    }

                    if (this.isUpperCase(str.charAt(i)))
                    {
                        decrypted += this.alphabets[replaceIndex].toUpperCase();
                    }
                    else
                    {
                        decrypted += this.alphabets[replaceIndex];
                    }
                }
                else
                {
                    decrypted += str.charAt(i);
                }
            }

            return decrypted;
        },
        /*getGoogleResults: function(str) {
            console.log(str);

            axios.get(process.env.VUE_APP_PUBLIC_PATH + 'get-google-results.php?q=' + str)
            .then((response) => {
                var el = document.createElement('html');
                el.innerHTML = response.data;
                //console.log(response.data);
                //var resultStats = el.getElementById('resultStats');
                var resultStats = el.querySelector('#resultStats');

                if (resultStats)
                {
                    console.log(str + ': ' + resultStats.innerHTML);
                }
                else
                {
                    console.log(str + ': ei tuloksia');
                }

            })
            .catch((error) => {
                console.error(error);
            });
        },*/
        /**
         * @param   {string}    char
         * @param   {string}    str
         * @returns {number}
         */
        getCharCount: function(char, str) {
            return str.toLowerCase().split(char).length - 1
        },
        /**
         * @param   {string}    char
         * @param   {string}    str
         * @returns {number}
         */
        getCharFreq: function(char, str) {
            // Remove whitespaces
            str = str.replace(new RegExp(' ', 'g'), '');
            // Get character count
            var count = this.getCharCount(char, str);
            // Calculate character frequency in string
            return count / str.length;
        },
        isUpperCase: function(str) {
            return str === str.toUpperCase();
        },
        setThreshold: function(newThreshold) {
            this.threshold = newThreshold;
            this.processBullshit();
        },
        setOrder: function(newOrder) {
            this.sortOrder = newOrder;
            this.processBullshit();
        },
        toggleInfo: function() {
            eventBus.$emit('toggleInfo');
        },
        toggleSettings: function() {
            eventBus.$emit('toggleSettings');
        }
    }
}

</script>

<style scoped>
header {
    height: 10vh;
}
header > h1 {
    height: 7.5vh;
    background-image: url('./assets/images/no-bullshit.svg');
    background-repeat: no-repeat;
    background-position: left center;
    background-size: contain;
    margin: 0;
}
header .btn {
    padding: 0;
}
.status-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, .75);
    font-size: .8rem;
    z-index: 9999;
}
.status-overlay .progress {
    width: 200px;
    height: 10px;
    margin-bottom: 22px;
}
</style>
