/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require("./bootstrap");
require("./main");
require("./schedule");
require("./query-group-page");

//window.Vue = require('vue');
require("bootstrap-select");
require("jquery");
window.$ = window.jQuery = require("jquery");
import "jquery-ui/ui/widgets/datepicker.js";
require("flatpickr");

// SELECT2
require("select2");

// project.home
require("./project/project-home");

// navigator
require("./navigation/navigation");

// select
require("./select/select");

// summary
require("./summary/summary");

// charts
window.ApexCharts = require("apexcharts");

// brief
require("./brief/brief");
