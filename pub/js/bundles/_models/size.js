Size = (function ( Backbone, _, $ ) {
    'use strict';
    var Size = Backbone.CommonModel.extend({
        urlRoot : App.router.url({
            'm': 'size', 
            'a': 'api'
        })
        , defaults : {
            'id' : ''
            , 'name' : ''
            , 'width' : 0
            , 'height' : 0
            , 'memo' : ''
        }
        , validate : function(data) {
            var hasError = false;
            this.removeErrors();
            if('' == data.name) {
                this.addError('size_name', '尺寸名称不能为空');
                hasError = true;
            }
            if(parseInt(data.width) == 0) {
                this.addError('width', '尺寸宽度必须大于零的整数');
                hasError = true;
            }
            if(parseInt(data.height) == 0) {
                this.addError('height', '尺寸高度必须大于零的整数');
                hasError = true;
            }
            return hasError;
        }
    });
    
    return Size;
}( Backbone, _, jQuery ));
