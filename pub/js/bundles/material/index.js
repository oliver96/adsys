define([
    'jquery'
    , 'underscore'
    , 'backbone'
    , 'vendors/backbone/backbone-pcollection'
    , 'vendors/backbone/backbone-paginator'
    , 'bundles/_public/utils'
    , 'bundles/_models/material'
], function($, _, Backbone) {
    var MaterialItemView = Backbone.View.extend({
        tagName : "tr"
        , template : _.template($('#mat_item_template').html())
        , events: {
            'click a.edit-link': 'editEvent'
        }

        , initialize : function() {
            this.model.bind('change', this.render, this);
            this.model.bind('destroy', this.remove, this);
        }
        , render : function() {
            var $el = $(this.el);
            $el.html(this.template(this.model.toJSON()));
            return this;
        }
        , editEvent : function(e) {
            var id = parseInt($(e.target).closest('tr').find('input').val())
                , url = App.router.url({'m' : 'material', 'a' : 'edit', 'id' : id});
            window.location.href = url;
        }
    });
    

    var MatrialList = Backbone.PaginatedCollection.extend({
        'model': Material
        , 'url': App.router.url({
            'm' : 'material'
            , 'a' : 'rows'
        })
    });
    
    var materialList = new MatrialList();
    
    var Paginator = Backbone.Paginator.extend({
        collection : materialList
        , el : $('.pagination')
    });
    new Paginator();
    
    var MainView = Backbone.View.extend({
        el: $("body")
        , events: {
            
        }
        , initialize: function() {
            this.listenTo(materialList, 'reset', this.onRenderList);
            materialList.fetch();
        }
        , onRenderList: function() {
            this.$("#material_list").empty();
            if(materialList.length > 0) {
                materialList.each(this.addMaterialRow, this);
            }
        }
        , addMaterialRow: function(model) {
            var view = new MaterialItemView({
                'model' : model
            });
            this.$("#material_list").append(view.render().el);
        }
    });

    new MainView();
});
