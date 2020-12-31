<template>
    <div>
        <h1>Home</h1>

        <v-btn @click="temp">go to temp</v-btn>

        <v-btn>
            <router-link
                :to="{
                    name: 'about',
                    params: { id: 123 },
                    query: { x: 'haha' },
                }"
            >
                go to about /123
            </router-link>
        </v-btn>
    </div>
</template>

<script>
import Vue from "vue";
import Axios from "axios";
import Back from '../../global/Back';

export default {
    name: "Home",
    methods: {
        temp: async function () {
            let d = await Back.Service(1);
            console.log(d);
        },
    },
};
</script>