/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';
import $ from 'jquery';
global.$ = $;
require('@fortawesome/fontawesome-free/js/all');
import './js/bootstrap.bundle.min.js';
import './js/popper.min.js';
import './js/jquery-2.2.4.min.js';
import './js/jquery-3.1.0.min.js';
import './js/cart.js';