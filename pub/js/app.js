define([
    'jquery',
    'underscore',
    'backbone',
    'router'
], function ($, _, Backbone, Router) {
    'use strict';
 
    var root = $("[data-main][data-root]").data("root");
    root = root ? root : '/';
    
    return {
        initialize: function () {
            App.router = new Router();
            Backbone.history.start();
        }
    };
});