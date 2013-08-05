(function () {
    'use strict';
 
    require.config({
        baseUrl: App.root + "/pub/js/"
        , urlArgs: "t=" + (new Date()).getTime()
        , paths: {
            "jquery": "vendors/jquery/jquery",
            "underscore": "vendors/underscore/underscore",
            "backbone": "vendors/backbone/backbone",
            "backstrap": "vendors/backstrap"
        }
        , shim    : {
            'underscore': {
                exports     : '_'
            }
            , 'backbone': { // 依赖underscore jquery类库
                deps        : ['underscore', 'jquery']
                , exports   : 'Backbone' 
            }
            , 'bootstrap': {
                deps        : ['jquery']
                , exports     : 'Bootstrap'
            }
        }
    });
 
    require(['app'], function (App) {
        App.initialize();
    });
 
}());