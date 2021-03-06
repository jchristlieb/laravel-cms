import Vue from 'vue';
import VueJSModal from "vue-js-modal";
import router from "./router";
import i18n from './i18n';
import route from 'ziggy';

export default class Cms {

    constructor(config) {
        this.bus = new Vue();
        this.bootingCallbacks = [];
        this.config = config;
    }

    /**
     * Register a callback to be called before Laravel CMS starts
     */
    booting(callback) {
        this.bootingCallbacks.push(callback)
    }

    /**
     * Execute all of the booting callbacks.
     */
    boot() {
        this.bootingCallbacks.forEach(callback => callback(Vue, router));
        this.bootingCallbacks = []
    }

    /**
     * Start Laravel CMS by calling each of the booting callbacks and create the underlying Vue instance.
     */
    start() {
        this.boot();

        Vue.use(VueJSModal, {dynamic: true});

        this.app = new Vue({
            el: '#cms',
            router,
            i18n,
            data() {
                return {
                    transitionName: 'fade'
                }
            },
            created() {
                this.$router.beforeEach((to, from, next) => {

                    const toDepth = to.path.split('/').length;
                    const fromDepth = from.path.split('/').length;
                    this.transitionName = toDepth < fromDepth ? 'slide-right' : 'slide-left';

                    next();
                });
            },
        })
    }

    /**
     * Register a listener on the event bus
     */
    $on(...args) {
        this.bus.$on(...args)
    }

    /**
     * flash a message via the event bus
     */
    flash(type, text) {
        this.bus.$emit('flash', {'type': type, 'text': text});
    }

    /**
     * Generates an object which contains the method and the url needed for a request.
     * We use https://github.com/tightenco/ziggy for the generation. We only have
     * to alter the output that we have a simple object with url and method.
     */
    route(name, params, absolute) {
        const routeObject = route(name, params, absolute, this.config.routes);

        return {
            method: routeObject.urlBuilder.route.methods[0],
            url: routeObject.toString()
        }
    }
}

