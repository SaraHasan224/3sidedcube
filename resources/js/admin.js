import $ from "jquery";
import 'bootstrap';
import axios from 'axios';
import flatpickr from 'flatpickr';


window.$ = window.jQuery = $;

var App = {};
window.App = App;

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

console.log("App: ", App);
require('./bootstrap.js');

// Plugin FILES
require('./plugins/init.js');
window.Swal = swal;

// CUSTOM FILES
// require('./custom/main.js');
window.App = App;


// MODULES

// require('./modules/helpers/sweet-alerts.js');
//
require('./custom/helpers/init.js');
require('./custom/features/init.js');

