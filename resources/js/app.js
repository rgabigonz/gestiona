
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/* VUE sweetalert2 */
import swal from 'sweetalert2';
window.swal = swal;

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

window.toast = toast;
/* VUE sweetalert2 */

/* VUE Form */
import { Form, HasError, AlertError } from 'vform';

window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
/* VUE Form */

/* VUE Router */
import VueRouter from 'vue-router';
Vue.use(VueRouter)

let routes = [
    { path: '/productos', name: 'productos', component: require('./components/Productos.vue') },
    { path: '/depositos', name: 'depositos', component: require('./components/Depositos.vue') },
    { path: '/formasventa', name: 'formasventa', component: require('./components/FormasVenta.vue') },
    { path: '/formaspago', name: 'formaspago', component: require('./components/FormasPago.vue') },
    { path: '/clientes', name: 'clientes', component: require('./components/Clientes.vue') },
    { path: '/vendedores', name: 'vendedores', component: require('./components/Vendedores.vue') },
    { path: '/proveedores', name: 'proveedores', component: require('./components/Proveedores.vue') },    
    { path: '/dashboard', name: 'dashboard', component: require('./components/Dashboard.vue') },
    { path: '/notaspedido', name: 'notaspedido', component: require('./components/NotasPedido.vue') },
    { path: '/notaspedidodetalle/:notaspedidoId', name: 'notaspedidodetalle', component: require('./components/NotasPedidoDetalle.vue') },
    { path: '/ordenescompra', name: 'ordenescompra', component: require('./components/OrdenesCompra.vue') },
    { path: '/ordenescompradetalle/:ordenescompraId', name: 'ordenescompradetalle', component: require('./components/OrdenesCompraDetalle.vue') },
]

const router = new VueRouter({
    //mode: 'history',
    routes // short for `routes: routes`
})
/*VUE Router */

/* VUE Progress Bar */
import VueProgressBar from 'vue-progressbar';
const options = {
    color: '#bffaf3',
    failedColor: '#874b4b',
    thickness: '5px',
    transition: {
      speed: '0.2s',
      opacity: '0.6s',
      termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
}
  
Vue.use(VueProgressBar, options)
/* VUE Progress Bar */

/* VUE Fire events */
window.Fire = new Vue();
/* VUE Fire events */

/* VUE Moment */
import moment from 'moment';
/* VUE Moment */

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.filter('formatDate', function(myDate){
    if (myDate) {
        return moment(myDate).format('DD-MM-YYYY');
    }
});

Vue.filter('currency', function(value){
    if (value) {
        return value.toFixed(2);
    }
});

const app = new Vue({
    el: '#app',
    router
});
