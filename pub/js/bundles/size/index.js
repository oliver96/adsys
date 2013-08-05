define([
    'jquery'
    , 'underscore'
    , 'backbone'
    , 'vendors/backbone/backbone-pcollection'
    , 'vendors/backbone/backbone-paginator'
    , 'bundles/_public/utils'
    , 'bundles/_models/size'
], function($, _, Backbone) {
    var SizeItemView = Backbone.View.extend({
        //列表标签.
        tagName : "tr"
        // 为单个元素缓存模板.
        , template : _.template($('#size_item_template').html())
        // 注册事件
        , events: {
            'click a.edit-link': 'editEvent'
        }

        // AdvView视图监听 model的事件变化,重新渲染
        // **Advertiser** 和 **AdvView** 成一一对应的关系.
        , initialize : function() {
            this.model.bind('change', this.render, this);
            this.model.bind('destroy', this.remove, this);
        }
        // 重新渲染单条广告主的列表行.
        , render : function() {
            var $el = $(this.el);
            $el.html(this.template(this.model.toJSON()));
            return this;
        }
        , editEvent : function(e) {
            var id = parseInt($(e.target).closest('tr').find('input').val())
                , url = App.router.url({'m' : 'size', 'a' : 'edit', 'id' : id});
            window.location.href = url;
        }
    });
    
    // 创建一个带分页的数据行的集合
    var SizeList = Backbone.PaginatedCollection.extend({
        'model': Size
        , 'url': App.router.url({
            'm' : 'size'
            , 'a' : 'rows'
        })
    });
    
    // 列表对象实例化
    var sizeList = new SizeList();
    
    // 分页条对象实例化
    var Paginator = Backbone.Paginator.extend({
        collection : sizeList
        , el : $('.pagination')
    });
    new Paginator();
    
    var MainView = Backbone.View.extend({
        el: $("body")
        , events: {
            
        }
        // 初始化事件
        , initialize: function() {
            // 绑定RESET事件，当advList数据重置时确发onRenderList方法
            this.listenTo(sizeList, 'reset', this.onRenderList);
            //this.listenTo(advList, 'change', this.onRenderList);
            
            // 从服务器获取广告主列表数据
            sizeList.fetch();
        }
        // 渲染广告主列表
        , onRenderList: function() {
            this.$("#size_list").empty();
            if(sizeList.length > 0) {
                sizeList.each(this.addSizeRow, this);
            }
        }
        // 添加一行广告主数据行
        , addSizeRow: function(model) {
            var view = new SizeItemView({
                'model' : model
            });
            this.$("#size_list").append(view.render().el);
        }
    });

    new MainView();
});
