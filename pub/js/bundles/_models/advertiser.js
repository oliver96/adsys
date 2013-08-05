Advertiser = (function ( Backbone, _, $ ) {
    'use strict';
    var Advertiser = Backbone.CommonModel.extend({
        // 正则表达式
        urlRoot : App.router.url({
            'm': 'advertiser', 
            'a': 'api'
        })
        , defaults : {
            'id' : ''
            , 'name' : ''
            , 'indus_id' : 0
            , 'credit' : 0
            , 'contact' : ''
            , 'email' : ''
            , 'detail' : ''
        }
        , validate : function(data) {
            var hasError = false;
            this.removeErrors();
            if('' == data.name) {
                this.addError('adv_name', '广告主名称不能为空');
                hasError = true;
            }
            if(parseInt(data.indus_id) == 0) {
                this.addError('industry', '必须选择所属的行业');
                hasError = true;
            }
            if('' != data.email
               && !this.validators.isEmail(data.email)) {
                this.addError('email', '邮件不是有效的格式');
                hasError = true;
            }
            return hasError;
        }
    });
    
    return Advertiser;
}( Backbone, _, jQuery ));
