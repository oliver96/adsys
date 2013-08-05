Material = (function ( Backbone, _, $ ) {
    'use strict';
    var Material = Backbone.CommonModel.extend({
        // 正则表达式
        urlRoot : App.router.url({
            'm': 'material', 
            'a': 'api'
        })
        , defaults : {
            'id' : ''
            , 'name' : ''
            , 'adv_id' : 0
            , 'type' : 0
            , 'url' : 0
            , 'size' : ''
        }
        , validate : function(data) {
            var hasError = false;
            this.removeErrors();
            if('' == data.name) {
                this.addError('mat_name', '素材名称不能为空');
                hasError = true;
            }
            if(data.type != 'image' && data.type != 'video') {
                this.addError('type', '素材类型不符合允许的范围（图片, 视频）');
                hasError = true;
            }
            return hasError;
        }
    });
    
    return Material;
}( Backbone, _, jQuery ));

