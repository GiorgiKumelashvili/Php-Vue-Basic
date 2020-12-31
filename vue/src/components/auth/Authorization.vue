<template>
    <div :class="{ 'mx-5': isMobile }">
        <v-card
            :loading="loading"
            class="mx-auto my-12 d-flex flex-column xxxxx mx-4"
            max-width="500"
            elevation="24"
            rounded="lg"
            shaped
        >
            <!-- Progress line -->
            <template slot="progress">
                <v-progress-linear
                    color="deep-purple"
                    height="10"
                    indeterminate
                />
            </template>

            <!-- Title -->
            <v-card-title
                class="align-self-center text-h4"
                v-text="routeName"
            />

            <!-- Form Inputs -->
            <v-form ref="form" class="pa-6" v-model="valid" lazy-validation>
                <v-text-field
                    v-if="$route.params.authname === 'register'"
                    v-model="userCredentials.username"
                    :rules="InputRules.usernameRules"
                    label="Username"
                    required
                />

                <v-text-field
                    v-model="userCredentials.email"
                    :rules="InputRules.emailRules"
                    label="Email"
                    required
                />

                <v-text-field
                    v-model="userCredentials.password"
                    :rules="InputRules.passwordRules"
                    :type="showPswd ? 'text' : 'password'"
                    :append-icon="showPswd ? 'mdi-eye' : 'mdi-eye-off'"
                    label="Password"
                    hint="At least 8 characters"
                    @click:append="showPswd = !showPswd"
                />
            </v-form>

            <!-- Button -->
            <v-btn
                :disabled="!valid"
                @click="Authorize()"
                v-text="routeName"
                color="purple white--text"
                class="mx-6"
            />

            <v-divider class="mx-4" />

            <!-- Side text -->
            <v-card-text class="d-flex flex-column mt-4">
                <div class="subtitle-1 align-self-center">
                    Or {{ $route.params.authname }} with
                </div>
            </v-card-text>

            <!-- 3rd party services -->
            <v-card-text class="d-flex justify-center pt-1">
                <v-btn
                    class="mx-2 align-self-center"
                    color="indigo darken-3"
                    fab
                    small
                >
                    <v-icon class="white--text headline">mdi-facebook</v-icon>
                </v-btn>

                <v-btn class="mx-2" color="light-blue darken-1" fab small>
                    <v-icon class="white--text headline">mdi-twitter</v-icon>
                </v-btn>

                <v-btn class="mx-2" color="deep-orange accent-3" fab small>
                    <v-icon class="white--text headline">mdi-google</v-icon>
                </v-btn>
            </v-card-text>

            <v-card-text class="d-flex flex-column pt-0">
                <div class="subtitle-1 align-self-center">
                    {{ routeName === "Login" ? "Need" : "Already have" }} an account ?
                    <router-link
                        :to="{
                            name: 'auth',
                            params: {authname:routeName === 'Login' ? 'register' : 'login'},
                        }"
                        class="text-decoration-none blue--text text--accent-2 pointer"
                        v-text="routeName === 'Login' ? 'Sign up' : 'Log in'"
                    />
                </div>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
import Axios from "axios";
import Vue from "vue";
import Back from "@/global/Back";

export default {
    data: () => ({
        valid: true,
        showPswd: false,
        loading: false,
        response: null,

        userCredentials: {
            username: "",
            email: "",
            password: "",
        },

        InputRules: {
            usernameRules: [
                (v) => !!v || "Username is required",
                (v) => (v && v.length >= 5 && v.length <= 30) || "Username must be between 5 and 25 characters",
                (v) => /^[a-zA-Z0-9]+$/.test(v) || "Username must be valid"
            ],

            emailRules: [
                (v) => !!v || "Email is required",
                (v) => /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(v) || "Email must be valid"
            ],

            passwordRules: [
                (v) => !!v || "Password is required",
                (v) => v.length >= 8 || "Min 8 characters",
                (v) => v.length <= 30 || "Max 30 characters",
                (v) => /.*[0-9]/.test(v) || "Password must contain at least 1 numeric character"
            ]
        }
    }),

    created() {
        if (this.$store.state.authorized) {
            this.$router.push({ name: "dashboard" });
        }
    },

    methods: {
        Authorize: async function () {
            // First validate form
            if (!this.$refs.form.validate()) {
                console.log("Error :( ");
                return;
            }

            // Start loading
            this.loading = true;

            // Copy user credentials
            let FinalUserCredentials = Object.assign({}, this.userCredentials);

            // Remove username for login
            if (this.routeName === "Login") {
                delete FinalUserCredentials.username;
            }

            // Get response from server
            let response = await Back.Auth("auth", {
                type: this.routeName.toLowerCase(),
                data: FinalUserCredentials,
            });

            // Set response
            Vue.set(this, "response", response);

            // Check if authorization was success
            if (response.statuscode === 1) {
                // Authorize
                this.$store.state.authorized = true;

                // Set cookie
                this.setCookies(response);

                // redirection
                this.$router.push({ name: "dashboard" });
            }

            // Remove loading
            this.loading = false;
            console.log(this.response);
        },
        // [End] Authorize

        setCookies: function (response) {
            let { identifier, accessToken, refreshToken } = response.data;

            this.$cookies.set("_identifier", identifier);
            this.$cookies.set("_accessToken", accessToken);
            this.$cookies.set("_refreshToken", refreshToken);
        },
        // [End] setCookies
    },

    computed: {
        routeName() {
            if (this.$route.params.authname) {
                let name = this.$route.params.authname;
                return name.replace(/^./, name[0].toUpperCase());
            }
        },

        isMobile() {
            return this.$vuetify.breakpoint.smAndDown;
        }
    },
};

/**
 * @description how to authorize on back
 *
 * Send this object:
 *
 *  {
        "type": "register",
        "data": {
            "username": "luka",
            "email": "luka@exam.com",
            "password": "luk32"
        }
    },
    {
        "type": "login",
        "data": {
            "email": "luka@exam.com",
            "password": "luk32"
        }
    }
 */
</script>
