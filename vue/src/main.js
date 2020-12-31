import Vue from 'vue';
import App from '@/App.vue';
import router from '@/router/router';
import store from '@/store/index';
import vuetify from '@/plugins/vuetify';
import VueCookies from 'vue-cookies';

//globals
import '@/assets/css/global.css';
import '@/global/protos/ArrayProto';
import '@/global/protos/VueGlobal';

// Cookies
Vue.use(VueCookies);

// Vue Configs
Vue.config.productionTip = false;
Vue.$cookies.config('30d');

new Vue({
    router,
    store,
    vuetify,
    render: h => h(App)
}).$mount('#app');

/*
    ! account
    * giorgi@exam.com
    * giorgi321jjhi9rio12
 */

/*TODO
    ** Icons: https://materialdesignicons.com/cdn/2.0.46/
    ** Dashboard: [
        https://vuse-dark-preview.hexesis.com/dashboard/operational (Better)
        https://vuse-dark-preview.hexesis.com/dashboard/analytical
    ]

    //! Need completion
    !) need to add avatar logo and some other stuff
    !) Forgot Password


    //* Successful ones
    +) Remove cookie on logout
    +) Authorization
    +) Rember me in background
    +) logout
    +) Add message for no login
    +) Axios stuff get it to .env
    +) Safe api call + Bearer access token
    +) Remove cookie on refresh expiration
    +) Create configs
    +) JWT front (refresh,access token + expiration)
    +) JWT server (access expiration)
    +) Block unauthorized
    +) Views + Router
    +) Navbar
    +) Dark mode
    +) register page
    +) 404 page gaitane calke
*/
