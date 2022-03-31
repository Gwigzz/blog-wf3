/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../styles/app.scss';

// Chargement de jquery
const $ = require('jquery');

// Import JS (chargement de la partie JS)
require('bootstrap');

// Import font awesome
require('@fortawesome/fontawesome-free/js/all.js');

// Document is Load, start JQuery
$(document).ready(function () {

    $('#test').on('mouseover', function () {
        $(this).css('color', 'red');
    });

    if ($('div.alert')) {
        $('div.alert').delay(4000).fadeOut(350, function () {
            $('div.alert').remove();
        });
    }

});