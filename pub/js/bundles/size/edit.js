define([
    'jquery'
    , 'underscore'
    , 'backbone'
    , 'bundles/_public/utils'
    , 'bundles/_models/common'
    , 'bundles/_models/size'
], function($, _, Backbone) {
    var size = new Size();
    
    var MainView = Backbone.View.extend({
        el: $("body")
        , template : _.template($('#size_form_template').html())
        , events : {
            // 禁用“回车键”，防止意外提交表单
            'keypress #size_form' : 'disableEnterKeyEvent'
            // 保存“广告主”事件
            , 'click #save_size_btn' : 'saveSizeEvent'
        }
        , initialize : function() {
            this.listenTo(size, 'change', this.render);
            if(App.params.id > 0) {
                size.set('id', App.params.id);
                size.fetch();
            }
            else {
                this.render();
            }
        }
        , render : function() {
            var sizeInfo = size.toJSON();
            this.$('#size_form').html(this.template(sizeInfo));
        }
        , disableEnterKeyEvent : function(e) {
            if(e.which == 13) {
                return false;
            }
            return true;
        }
        , saveSizeEvent : function(e) {
            // 获取表单所有数据
            var formData = App.Utils.getFormData('size_form');
            
            size.set(formData);
            if(size.isValid()) {
                size.save(null, {'success' : function(model) {
                    var status = model.get('status');
                    if(status == false) {
                        size.addErrors(model.get('errors'));
                    }
                    else {
                        window.location.href = App.router.url({'m': 'size', 'a': 'index'});
                    }
                }});
            }
        }
        , resetSizeEvent : function() {
            
        }
    });
    
    new MainView();
});
