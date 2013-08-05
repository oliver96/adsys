define([
    'jquery'
    , 'underscore'
    , 'backbone'
    , 'bundles/_public/utils'
    , 'bundles/_models/common'
    , 'bundles/_models/advertiser'
], function($, _, Backbone) {
    var advertiser = new Advertiser();
    
    var MainView = Backbone.View.extend({
        el: $("body")
        , template : _.template($('#adv_form_template').html())
        , events : {
            // 禁用“回车键”，防止意外提交表单
            'keypress #advertiser_form' : 'disableEnterKeyEvent'
            // 保存“广告主”事件
            , 'click #save_advertiser_btn' : 'saveAdvertiserEvent'
        }
        , initialize : function() {
            this.listenTo(advertiser, 'change', this.render);
            if(App.params.id > 0) {
                advertiser.set('id', App.params.id);
                advertiser.fetch();
            }
            else {
                this.render();
            }
        }
        , render : function() {
            // 获取行业
            if(typeof App.industries == 'undefined') {
                $.ajax({
                    'url' : App.router.url({'m' : 'advertiser', 'a' : 'industries'})
                    , 'async' : false
                    , 'success' : function(res) {
                        App.industries = res;  
                    }
                });
            }
            var advertiserInfo = advertiser.toJSON();
            advertiserInfo.industries = App.industries;
            advertiserInfo.credits = [
                {'label' : '一星', 'value' : 1}
                , {'label' : '二星', 'value' : 2}
                , {'label' : '三星', 'value' : 3}
                , {'label' : '四星', 'value' : 4}
                , {'label' : '五星', 'value' : 5}
            ];
            this.$('#advertiser_form').html(this.template(advertiserInfo));
        }
        , disableEnterKeyEvent : function(e) {
            if(e.which == 13) {
                return false;
            }
            return true;
        }
        , saveAdvertiserEvent : function(e) {
            // 获取表单所有数据
            var formData = App.Utils.getFormData('advertiser_form');
            
            advertiser.set(formData);
            if(advertiser.isValid()) {
                advertiser.save(null, {'success' : function(model) {
                    var status = model.get('status');
                    if(status == false) {
                        advertiser.addErrors(model.get('errors'));
                    }
                }});
            }
        }
        , resetAdvertiserEvent : function() {
            
        }
    });
    
    new MainView();
});