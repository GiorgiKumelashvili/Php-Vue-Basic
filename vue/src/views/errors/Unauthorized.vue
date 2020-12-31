<template>
    <v-card
        elevation="24"
        class="d-flex flex-column align-center mx-auto my-14"
        max-width="450"
    >
        <v-card-title v-text="401" class="text-h1" />
        <v-card-subtitle
            v-text="'Woah Unauthorized access !!!'"
            class="text-h5 mt-2 red--text"
        />

        <v-card-subtitle
            v-text="'Access is only available to registered users'"
            class="text-h6"
        />
        <v-btn
            class="purple white--text my-4"
            @click="$router.push({ name: 'auth', params: { authname: 'login' } })"
        >
            <v-icon class="pr-2">mdi-robber</v-icon>
            Go Back !
        </v-btn>
    </v-card>
</template>
