define([
    'jquery'
    , 'underscore'
    , 'backbone'
    , 'bundles/_public/utils'
    , 'bundles/_models/common'
    , 'bundles/_models/micro'
], function($, _, Backbone) {
    var micro = new Micro();
    
    var MainView = Backbone.View.extend({
        el: $("body")
        , template : _.template($('#micro_form_template').html())
        , events : {
            // 禁用“回车键”，防止意外提交表单
            'keypress #micro_form' : 'disableEnterKeyEvent'
            // 保存“广告主”事件
            , 'click #save_micro_btn' : 'saveMicroEvent'
        }
        , initialize : function() {
            this.listenTo(micro, 'change', this.render);
            if(App.params.id > 0) {
                micro.set('id', App.params.id);
                micro.fetch();
            }
            else {
                this.render();
            }
        }
        , render : function() {
            var microInfo = micro.toJSON();
            microInfo.valueTypes = [
                {'label': '输入框', 'value': 'input'}
                , {'label': '多项单选', 'value': 'radio'}
                , {'label': '多项多选', 'value': 'checkbox'}
                , {'label': '多项下拉', 'value': 'select'}
            ];
            microInfo.validates = [
                {'label': '字符串', 'value': 'string'}
                , {'label': 'URL', 'value': 'url'}
                , {'label': '邮件地址', 'value': 'email'}
                , {'label': '数字', 'value': 'digit'}
                , {'label': '字母与数字', 'value': 'alpha'}
            ];
            this.$('#micro_form').html(this.template(microInfo));
        }
        , disableEnterKeyEvent : function(e) {
            if(e.which == 13) {
                return false;
            }
            return true;
        }
        , saveMicroEvent : function(e) {
            // 获取表单所有数据
            var formData = App.Utils.getFormData('micro_form');
            
            micro.set(formData);
            if(micro.isValid()) {
                micro.save(null, {'success' : function(model) {
                    var status = model.get('status');
                    if(status == false) {
                        micro.addErrors(model.get('errors'));
                    }
                }});
            }
        }
        , resetAdtplEvent : function() {
            
        }
    });
    
    new MainView();
});
