define(["underscore", "backbone"], function (_, Backbone) {
    'use strict';
 
     // Router
    var Router = Backbone.Router.extend({
        routes : {
            '': 'loadPage'
        }
        , url: function(urlAttrs) {
            var urlStr = '';
            for(var key in urlAttrs) {
                urlStr += '/' + urlAttrs[key];
            }
            return App.root + urlStr;
        }
        , loadPage: function() {
            var url = (window || this).location.href; //.match(/\?(.*)$/);
            
            // 解析URL
            this.parseUrl(url);
            var module = App.module
                , action = App.action
                , requires = [];

            requires.push('bundles/_models/common');
            requires.push('bundles/' + module + '/' + action);
            
            require(requires);
        }
        , parseUrl: function(url) {
            this._fields = {
                'Username' : 4,
                'Password' : 5,
                'Port' : 7,
                'Protocol' : 2,
                'Host' : 6,
                'Pathname' : 8,
                'URL' : 0,
                'Querystring' : 9,
                'Fragment' : 10
            };

            this._values = {};
            this._regex = null;
            this.version = 0.1;
            this._regex = /^((\w+):\/\/)?((\w+):?(\w+)?@)?([^\/\?:]+):?(\d+)?(\/?[^\?#]+)?\??([^#]+)?#?(\w*)/;

            for(var f in this._fields) {
                this['get' + f] = this._makeGetter(f);
            }

            if (typeof url !== 'undefined') {
                this._parse(url);
            }
        }
        , _initValues: function() {
            for(var f in this._fields) {
                this._values[f] = '';
            }
        }
        , _parse: function(url) {
            this._initValues();
            var r = this._regex.exec(url);
            if (!r) throw "DPURLParser::_parse -> Invalid URL";

            for(var f in this._fields) {
                if (typeof r[this._fields[f]] !== 'undefined') {
                    if(f === 'Querystring') {
                        this._values[f] = this._parseQueryString(r[this._fields[f]]);
                    }
                    else {
                        this._values[f] = r[this._fields[f]];
                    }
                }
            }
        }
        , _makeGetter: function(field) {
            return function() {
                return this._values[field];
            };
        }
        , _parseQueryString: function(query) {  
            var params = {};  
            var paramNames = new Array();  

            if (query !== null && query.length > 0) {  
                var array = query.split("&");  
                for(var i in array){  
                    var kv = array[i].split("=");  
                    var name = decodeURIComponent(kv[0]);  
                    var value = (typeof kv[1] === undefined) ? "" : decodeURIComponent(kv[1]);  
                    if (params[name]) {  
                        var arr = params[name];  
                        arr[arr.length] = value;  
                    } else {  
                        params[name] = new Array(value);  
                        paramNames[paramNames.length] = name;  
                    }  
                }  
            }

            this.getQueryString = function() {
                return query;
            };
            this.getParameterNames = function() {
                return paramNames; 
            };  
            this.getParameterValues = function(name) { 
                return (typeof params[name] === undefined) ? null : params[name]; 
            };
            this.getParameter = function(name) {
                return (typeof params[name] === undefined) ? null : params[name][0]; 
            };
        }  
    });
    
    
 
    return Router;
});