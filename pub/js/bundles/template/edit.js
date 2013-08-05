define([
    'jquery'
    , 'underscore'
    , 'backbone'
    , 'bundles/_public/utils'
    , 'bundles/_models/common'
    , 'bundles/_models/template'
], function($, _, Backbone) {
    var adtpl = new AdTemplate();
    
    var MainView = Backbone.View.extend({
        el: $("body")
        , template : _.template($('#adtpl_form_template').html())
        , events : {
            // 禁用“回车键”，防止意外提交表单
            'keypress #adtpl_form' : 'disableEnterKeyEvent'
            // 保存“广告主”事件
            , 'click #save_adtpl_btn' : 'saveAdtplEvent'
        }
        , initialize : function() {
            this.listenTo(adtpl, 'change', this.render);
            if(App.params.id > 0) {
                adtpl.set('id', App.params.id);
                adtpl.fetch();
            }
            else {
                this.render();
            }
        }
        , render : function() {
            var adtplInfo = adtpl.toJSON();
            adtplInfo.matTypes = [
                {'label': '文本', 'value': 'text'}
                , {'label': '图片', 'value': 'image'}
                , {'label': '视频', 'value': 'video'}
            ];
            this.$('#adtpl_form').html(this.template(adtplInfo));
        }
        , disableEnterKeyEvent : function(e) {
            if(e.which == 13) {
                return false;
            }
            return true;
        }
        , saveAdtplEvent : function(e) {
            // 获取表单所有数据
            var formData = App.Utils.getFormData('adtpl_form');
            
            adtpl.set(formData);
            if(adtpl.isValid()) {
                adtpl.save(null, {'success' : function(model) {
                    var status = model.get('status');
                    if(status == false) {
                        adtpl.addErrors(model.get('errors'));
                    }
                }});
            }
        }
        , resetAdtplEvent : function() {
            
        }
        , saveMicroEvent : function(e) {
            var form
        }
    });
    
    new MainView();
});
