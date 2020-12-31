import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '@/views/main/Home';
import _404 from "@/views/errors/_404";
import Auth from '@/views/Auth';

// Lazy Loading
const About = () => import(/* webpackChunkName: "about" */ '../views/main/About');

Vue.use(VueRouter);

const routes = [
    {
        path: '/auth/:authname',
        name: 'auth',
        component: Auth
    },
    {
        path: '/account',
        name: 'account',
    },
    {
        path: '/dashboard',
        name: 'dashboard'
    },
    {
        path: '/about',
        name: 'about',
        component: About,
        children: [
            {
                path: ':id',
                component: About
            },
            {
                path: ':id/:x',
                component: About
            }
        ]
    },
    {
        path: '*',
        name: '404',
        component: _404
    }
];

export default new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
});
