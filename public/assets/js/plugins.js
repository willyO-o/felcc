/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Version: 2.2.0
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Common Plugins Js File
*/

//Common plugins
if (document.querySelectorAll("[toast-list]")) {
    document.writeln(`<script type='text/javascript' src='${window.location.origin}/assets/libs/toastify-js/src/toastify.js'></script>`);
}
if (document.querySelectorAll('[data-choices]')) {
    document.writeln(`<script type='text/javascript' src='${window.location.origin}/assets/libs/choices.js/public/assets/scripts/choices.min.js'></script>`);

}
if (document.querySelectorAll("[data-provider]")) {
    document.writeln(`<script type='text/javascript' src='${window.location.origin}/assets/libs/flatpickr/flatpickr.min.js'></script>`);
}
