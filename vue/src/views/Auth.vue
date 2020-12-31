<template>
    <div>
        <Authorization v-if="routeName === 'login' || routeName === 'register'"/>
        <ErrorPage v-else />
    </div>
</template>

<script>
/**
 * !Routes
 * [ login, register ]
 */
import Authorization from "@/components/auth/Authorization";
import _404 from "@/views/errors/_404";

export default {
    components: {
        ErrorPage: _404,
        Authorization
    },

    computed: {
        routeName() {
            return this.$route.params.authname;
        }
    }
};
</script>
