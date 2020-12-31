<template>
    <v-app>
        <!-- Main -->
        <v-main v-if="!brokenUrl && $store.state.authorized">
            <Navigation />
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </v-main>

        <!-- Auth -->
        <v-main
            v-else-if="!brokenUrl && !$store.state.authorized && isAuth"
            class="grad"
        >
            <router-view></router-view>
        </v-main>

        <!-- Unauthorized or Broken -->
        <v-main v-else class="grad">
            <ErrorPage />
        </v-main>
    </v-app>
</template>

<script>
import _404 from "@/views/errors/_404";
import Navigation from "@/components/Fragments/Navigation";

export default {
    name: "App",

    components: {
        Navigation,
        ErrorPage: _404,
    },

    created() {
        if (this.$cookies.get("_refreshToken")) {
            this.$store.state.authorized = true;
        }

        if (this.$route.name !== "auth" && !this.$store.state.authorized) {
            this.$router.push({ name: "auth", params: { authname: "login" } });
        }
    },

    computed: {
        brokenUrl() {
            let l = this.$router.resolve({ name: this.$route.name });
            return l.location.name === "404";
        },

        isAuth() {
            return this.$route.name === "auth";
        }
    }
};
</script>
