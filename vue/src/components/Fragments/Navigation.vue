<template>
    <div>
        <v-navigation-drawer
            v-model="drawer"
            :mini-variant.sync="mini"
            app
            width="220"
        >
            <!-- Sidebar Header -->
            <v-list-item class="px-2">
                <v-list-item-avatar>
                    <v-img src="https://cdn.vuetifyjs.com/images/john.jpg" />
                </v-list-item-avatar>

                <v-list-item-title>{{ NAME }}</v-list-item-title>

                <v-btn v-show="moreThanMedium" @click.stop="mini = !mini" icon>
                    <v-icon>mdi-chevron-left</v-icon>
                </v-btn>
            </v-list-item>

            <v-divider />

            <!-- Main Sidebar Links -->
            <v-list dense>
                <!-- Routes (Links) -->
                <template v-for="item in items">
                    <!-- Routes without child -->
                    <router-link
                        tag="div"
                        class="d-flex"
                        v-if="!item.child"
                        :to="mini ? '' : item.path"
                        :key="item.title"
                    >
                        <v-list-item link>
                            <v-list-item-icon>
                                <v-icon>{{ item.icon }}</v-icon>
                            </v-list-item-icon>
                            <!-- {{item.child}} -->

                            <v-list-item-title v-text="item.title" />
                        </v-list-item>
                    </router-link>

                    <!-- Routes with childs -->
                    <v-list-group
                        v-else
                        color="secondary"
                        :key="item.title"
                        :value="false"
                        :prepend-icon="item.icon"
                    >
                        <template v-slot:activator>
                            <v-list-item-title>{{
                                item.title
                            }}</v-list-item-title>
                        </template>

                        <router-link
                            v-for="{ title, icon, path } of item.child"
                            tag="div"
                            class="d-flex"
                            :key="title"
                            :to="path"
                        >
                            <v-list-item class="pl-11" :key="title + icon" link>
                                <v-list-item-icon>
                                    <v-icon v-text="icon"></v-icon>
                                </v-list-item-icon>

                                <v-list-item-title
                                    v-text="title"
                                ></v-list-item-title>
                            </v-list-item>
                        </router-link>
                    </v-list-group>
                </template>

                <!-- Logout (not a link but a modal) -->
                <v-dialog
                    v-model="logoutModelToggle"
                    persistent
                    max-width="420"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-list-item class="px-4" key="'logout-key'" link>
                            <v-list-item-icon>
                                <v-icon v-text="'mdi-export'" />
                            </v-list-item-icon>

                            <v-list-item-title
                                v-text="'Logout'"
                                color="primary"
                                dark
                                v-bind="attrs"
                                v-on="on"
                            />
                        </v-list-item>
                    </template>
                    <v-card>
                        <v-card-title
                            class="headline"
                            v-text="'Are you sure you want to log out !'"
                        />
                        <v-card-actions>
                            <v-spacer />
                            <v-btn
                                text
                                color="red darken-1"
                                @click="logoutModelToggle = false"
                                v-text="'Disagree'"
                            />
                            <v-btn
                                text
                                color="green darken-1"
                                @click="logout"
                                v-text="'Agree'"
                            />
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar app dense>
            <!-- Icon after screen under lg -->
            <v-app-bar-nav-icon
                v-show="isOrLessThanMedium"
                @click.stop="drawer = !drawer"
                color="purple darken-2"
            />

            <!-- Title -->
            <v-toolbar-title>Food Heaven</v-toolbar-title>

            <v-spacer />

            <!-- Little config place -->
            <div class="text-center pr-4">
                <v-menu
                    v-model="menu"
                    :close-on-content-click="false"
                    :nudge-width="200"
                    offset-y
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn icon v-bind="attrs" v-on="on">
                            <v-icon>mdi-apps</v-icon>
                        </v-btn>
                    </template>

                    <v-card>
                        <v-list>
                            <v-list-item>
                                <v-list-item-avatar color="black">
                                    <v-icon
                                        dark
                                        v-text="'mdi-account-cog-outline'"
                                    />
                                </v-list-item-avatar>

                                <v-list-item-content>
                                    <v-list-item-title
                                        v-text="'Configuration'"
                                    />
                                    <v-list-item-title
                                        v-text="'Mini version'"
                                    />
                                </v-list-item-content>

                                <v-list-item-action>
                                    <v-icon>
                                        {{
                                            darkMode.enabled
                                                ? darkMode.darkIcon
                                                : darkMode.lightIcon
                                        }}
                                    </v-icon>
                                </v-list-item-action>
                            </v-list-item>
                        </v-list>

                        <v-divider></v-divider>

                        <v-list>
                            <v-list-item>
                                <v-list-item-action>
                                    <v-switch
                                        v-model="darkMode.enabled"
                                        color="secondary"
                                        inset
                                    />
                                </v-list-item-action>

                                <v-list-item-title v-text="darkMode.text" />
                            </v-list-item>
                        </v-list>
                    </v-card>
                </v-menu>
            </div>
        </v-app-bar>
    </div>
</template>

<script>
import Vue from "vue";
import Back from "@/global/Back";

export default {
    data: () => ({
        // Mini config section
        menu: false,

        darkMode: {
            enabled: null,
            text: "Dark Mode",
            darkIcon: "mdi-sunglasses",
            lightIcon: "mdi-white-balance-sunny",
        },

        // Sidebar section
        NAME: "Giorga K",
        logoutModelToggle: false,
        drawer: true,
        mini: false,
        activeSize: null,
        items: [
            {
                title: "Dashboard",
                icon: "mdi-view-dashboard",
                path: "/dashboard",
            },
            { title: "Store", icon: "mdi-cart", path: "/store" },
            {
                title: "Profile",
                icon: "mdi-account-multiple",
                path: "/profile",
            },
            { title: "Account", icon: "mdi-wallet-outline", path: "/account" },
            {
                title: "Food",
                icon: "mdi-silverware-fork-knife",
                child: [
                    {
                        title: "Read",
                        icon: "mdi-file-outline",
                        path: "/food/read",
                    },
                    { title: "Edit", icon: "mdi-pencil", path: "/food/edit" },
                ],
            },
            {
                title: "Recipe",
                icon: "mdi-chef-hat",
                child: [
                    {
                        title: "Create",
                        icon: "mdi-plus-outline",
                        path: "/recipe/create",
                    },
                    {
                        title: "Read",
                        icon: "mdi-file-outline",
                        path: "/recipe/read",
                    },
                    { title: "Edit", icon: "mdi-pencil", path: "/recipe/edit" },
                ],
            },
            {
                title: "Warehouse",
                icon: "mdi-garage-variant",
                path: "/warehouse",
            },
            {
                title: "Events",
                icon: "mdi-calendar-multiselect ",
                path: "/events",
            },
            { title: "Settings", icon: "mdi-cog-outline", path: "/settings" },
            { title: "Promotions", icon: "mdi-tag", path: "/promotions" },

            // ! doesnt loses state
            { title: "About", icon: "mdi-help-box", path: { name: "about" } },
            // { title: "About",       icon: "mdi-help-box",               path: "/about" },

            // { title: "Logout", icon: "mdi-export", path: "" },
        ],
    }),

    methods: {
        toggleDarkMode: function () {
            this.$store.state.darkModeEnabled = !this.$store.state.darkModeEnabled;

            // Original toggling between dark mode
            this.$vuetify.theme.dark = !this.$vuetify.theme.dark;
        },
        // [End] toggleDarkMode

        logout: function () {
            Back.removeCookies();
            this.$store.state.authorized = false;
            this.$router.push({ name: "auth", params: { authname: "login" } });
        }
        // [End] logout
    },

    computed: {
        moreThanMedium: function () {
            return this.activeSize === "lg" || this.activeSize === "xl";
        },

        isOrLessThanMedium: function () {
            return this.activeSize !== "lg" && this.activeSize !== "xl";
        },
    },

    watch: {
        "darkMode.enabled": {
            handler() {
                this.toggleDarkMode();
            }
        },
        "$vuetify.breakpoint.name": {
            handler() {
                Vue.set(this, "activeSize", this.$vuetify.breakpoint.name);
                const { name: screenSize } = this.$vuetify.breakpoint;
                this.drawer = screenSize === "lg" || screenSize === "xl" ? true : false;

                if (this.isOrLessThanMedium) this.mini = false;
            },
            deep: true,
            immediate: true,
        }
    },
};
</script>
