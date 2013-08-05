define([
    'jquery'
    , 'underscore'
    , 'backbone'
    , 'bundles/_public/utils'
    , 'bundles/_models/common'
    , 'bundles/_models/material'
], function($, _, Backbone) {
    var material = new Material();
    
    var MainView = Backbone.View.extend({
        el: $("body")
        , template : _.template($('#mat_form_template').html())
        , events : {
            // 禁用“回车键”，防止意外提交表单
            'keypress #material_form' : 'disableEnterKeyEvent'
            // 保存“广告主”事件
            , 'click #save_material_btn' : 'saveMaterialEvent'
        }
        , initialize : function() {
            this.listenTo(material, 'change', this.render);
            if(App.params.id > 0) {
                material.set('id', App.params.id);
                material.fetch();
            }
            else {
                this.render();
            }
        }
        , render : function() {
            // 获取广告主
            if(typeof App.advertisers == 'undefined') {
                $.ajax({
                    'url' : App.router.url({'m' : 'material', 'a' : 'advertisers'})
                    , 'async' : false
                    , 'success' : function(res) {
                        App.advertisers = res;  
                    }
                });
            }
            var materialInfo = material.toJSON();
            materialInfo.advertisers = App.advertisers;
            this.$('#material_form').html(this.template(materialInfo));
        }
        , disableEnterKeyEvent : function(e) {
            if(e.which == 13) {
                return false;
            }
            return true;
        }
        , saveMaterialEvent : function(e) {
            // 获取表单所有数据
            var formData = App.Utils.getFormData('material_form');
            
            material.set(formData);
            if(material.isValid()) {
                material.save(null, {'success' : function(model) {
                    var status = model.get('status');
                    if(status == false) {
                        material.addErrors(model.get('errors'));
                    }
                }});
            }
        }
    });
    
    new MainView();
});