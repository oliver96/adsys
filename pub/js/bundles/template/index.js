define([
    'jquery'
    , 'underscore'
    , 'backbone'
    , 'vendors/backbone/backbone-pcollection'
    , 'vendors/backbone/backbone-paginator'
    , 'bundles/_public/utils'
    , 'bundles/_models/template'
], function($, _, Backbone) {
    var AdtplItemView = Backbone.View.extend({
        tagName : "tr"
        , template : _.template($('#adtpl_item_template').html())
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
                , url = App.router.url({'m' : 'template', 'a' : 'edit', 'id' : id});
            window.location.href = url;
        }
    });
    

    var AdtplList = Backbone.PaginatedCollection.extend({
        'model': AdTemplate
        , 'url': App.router.url({
            'm' : 'template'
            , 'a' : 'rows'
        })
    });
    
    var adtplList = new AdtplList();
    
    var Paginator = Backbone.Paginator.extend({
        collection : adtplList
        , el : $('.pagination')
    });
    new Paginator();
    
    var MainView = Backbone.View.extend({
        el: $("body")
        , events: {
            
        }
        , initialize: function() {
            this.listenTo(adtplList, 'reset', this.onRenderList);
            adtplList.fetch();
        }
        , onRenderList: function() {
            this.$("#adtpl_list").empty();
            if(adtplList.length > 0) {
                adtplList.each(this.addAdtplRow, this);
            }
        }
        , addAdtplRow: function(model) {
            var view = new AdtplItemView({
                'model' : model
            });
            this.$("#adtpl_list").append(view.render().el);
        }
    });

    new MainView();
});
