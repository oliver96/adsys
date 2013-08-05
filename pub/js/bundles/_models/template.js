AdTemplate = (function ( Backbone, _, $ ) {
    'use strict';
    var Template = Backbone.CommonModel.extend({
        // 正则表达式
        urlRoot : App.router.url({
            'm': 'template', 
            'a': 'api'
        })
        , defaults : {
            'id' : ''
            , 'name' : ''
            , 'mat_types' : []
            , 'code' : ''
            , 'memo' : ''
        }
        , validate : function(data) {
            var hasError = false;
            this.removeErrors();
            if('' == data.name) {
                this.addError('adtpl_name', '素材模板名称不能为空');
                hasError = true;
            }
            if(data.mat_types.length == 0) {
                this.addError('mat_type_1', '必须指定支持的素材类型（文本，图片, 视频）');
                hasError = true;
            }
            if(data.code == '') {
                this.addError('code', '必须输入模板的代码');
                hasError = true;
            }
            return hasError;
        }
    });
    
    return Template;
}( Backbone, _, jQuery ));

