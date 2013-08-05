Micro = (function ( Backbone, _, $ ) {
    'use strict';
    var Micro = Backbone.CommonModel.extend({
        urlRoot : App.router.url({
            'm': 'micro', 
            'a': 'api'
        })
        , defaults : {
            'id' : ''
            , 'code' : ''
            , 'name' : ''
            , 'value_type' : 0
            , 'values' : ''
            , 'validate' : ''
            , 'memo' : ''
        }
        , validate : function(data) {
            var hasError = false;
            this.removeErrors();
            if('' == data.code) {
                this.addError('code', '');
                hasError = true;
            }
            return hasError;
        }
    });
    
    return Micro;
}( Backbone, _, jQuery ));
