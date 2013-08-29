App.Utils = (function ( Backbone, _, $ ) {
    'use strict';
    
    var Utils = {};
    Utils.getFormData = function(formid) {
        var formData = {};
        $(':input', '#' + formid).each(function() {
            var type    = this.type
                , name  = this.name
                , tag   = this.tagName.toLowerCase();

            if(type == 'text' || type == 'password' || tag == 'textarea' || type == 'radio') {
                formData[name] = this.value;
            }
            else if(type == 'checkbox') {
                if(typeof formData[name] == 'undefined') {
                    formData[name] = [];
                }
                if(this.checked) {
                    formData[name].push(this.value);
                }
            }
            else if(tag == 'select') {
                formData[name] = this.options[this.selectedIndex].value;
            }
        });
        return formData;
    };
    
    Utils.equals = function(value1, value2) {
        
    };
    // 动态加载CSS文件
    Utils.loadCss = function(file) {
        var head = document.getElementsByTagName('HEAD').item(0);
        var style = document.createElement('link');
        style.href = App.root + '/pub/css/' + file + '.css';
        style.rel = 'stylesheet';
        style.type = 'text/css';
        head.appendChild(style);
    };
    
    return Utils;
}( Backbone, _, jQuery ));
