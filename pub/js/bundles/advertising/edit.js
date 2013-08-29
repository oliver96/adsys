define([
    'jquery'
    , 'underscore'
    , 'backbone'
    , 'bundles/_public/utils'
    , 'bundles/_models/common'
    , 'bundles/_models/advertising'
], function($, _, Backbone) {
    var ad = new Advertising();
    
    var MainView = Backbone.View.extend({
        el: $("body")
        , template : _.template($('#ad_form_template').html())
        , events : {
            // 禁用“回车键”，防止意外提交表单
            'keypress #ad_form' : 'disableEnterKeyEvent'
            // 保存“广告主”事件
            , 'click #save_ad_btn' : 'saveAdEvent'
        }
        , initialize : function() {
            this.listenTo(ad, 'change', this.render);
            if(App.params.id > 0) {
                ad.set('id', App.params.id);
                ad.fetch();
            }
            else {
                this.render();
            }
        }
        , render : function() {
            // 获取广告主
            if(typeof App.advertisers == 'undefined') {
                $.ajax({
                    'url' : App.router.url({'m' : 'advertiser', 'a' : 'advertisers'})
                    , 'async' : false
                    , 'success' : function(res) {
                        App.advertisers = res;  
                    }
                });
            }
            // 获取模板
            if(typeof App.templates == 'undefined') {
                $.ajax({
                    'url' : App.router.url({'m' : 'template', 'a' : 'templates'})
                    , 'async' : false
                    , 'success' : function(res) {
                        App.templates = res;  
                    }
                });
            }
            // 获取尺寸
            if(typeof App.sizes == 'undefined') {
                $.ajax({
                    'url' : App.router.url({'m' : 'size', 'a' : 'sizes'})
                    , 'async' : false
                    , 'success' : function(res) {
                        App.sizes = res;
                    }
                });
            }
            
            var adInfo = ad.toJSON();
            adInfo.advertisers = App.advertisers;
            adInfo.templates = App.templates;
            adInfo.sizes = App.sizes;
            this.$('#ad_form').html(this.template(adInfo));
        }
        , disableEnterKeyEvent : function(e) {
            if(e.which == 13) {
                return false;
            }
            return true;
        }
        , saveAdEvent : function(e) {
            // 获取表单所有数据
            var formData = App.Utils.getFormData('ad_form');
            ad.set(formData);
            if(ad.isValid()) {
                ad.save(null, {'success' : function(model) {
                    var status = model.get('status');
                    if(status == false) {
                        ad.addErrors(model.get('errors'));
                    }
                }});
            }
        }
    });
    
    new MainView();
});
