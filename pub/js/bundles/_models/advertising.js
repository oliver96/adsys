Advertising = (function ( Backbone, _, $ ) {
    'use strict';
    var Advertising = Backbone.CommonModel.extend({
        // 正则表达式
        urlRoot : App.router.url({
            'm': 'advertising', 
            'a': 'api'
        })
        , defaults : {
            'id' : ''
            , 'name' : ''
            , 'adv_id' : 0
            , 'tpl_id' : 0
            , 'size_id' : 0
            , 'optimize' : 0
        }
        , validate : function(data) {
            var hasError = false;
            this.removeErrors();
            if('' == data.name) {
                this.addError('ad_name', '广告名称不能为空');
                hasError = true;
            }
            if(parseInt(data.adv_id) == 0) {
                this.addError('advertiser', '必须选择所属的广告主');
                hasError = true;
            }
            if(parseInt(data.tpl_id) == 0) {
                this.addError('template', '必须选择所属的广告创意模板');
                hasError = true;
            }
            if(parseInt(data.size_id) == 0) {
                this.addError('size', '必须选择所属的广告尺寸');
                hasError = true;
            }
            return hasError;
        }
    });
    
    return Advertising;
}( Backbone, _, jQuery ));
