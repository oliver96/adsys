App.Utils = (function ( Backbone, _, $ ) {
    'use strict';
    
    var Utils = {};
    Utils.getFormData = function(formid) {
        var formData = {};
        $(':input', '#' + formid).each(function() {
            var type    = this.type
                , name  = this.name
                , tag   = this.tagName.toLowerCase();

            if(type == 'text' || type == 'password' || tag == 'textarea') {
                formData[name] = this.value;
            }
            else if(type == 'checkbox' || type == 'radio') {
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
    }
    
    Utils.equals = function(value1, value2) {
        
    }
    
    return Utils;
}( Backbone, _, jQuery ));