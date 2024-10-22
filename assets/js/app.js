/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
let $ = require('jquery')
require('../css/app.css');
require('select2');

$('select').select2();
let $contactBtn = $('#contactButton');
$contactBtn.click(e => {
 e.preventDefault();
 $('#contactForm').slideDown();
 $contactBtn.slideUp();
});

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
