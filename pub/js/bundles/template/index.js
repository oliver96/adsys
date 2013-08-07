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
        //�б��ǩ.
        tagName : "tr"
        // Ϊ����Ԫ�ػ���ģ��.
        , template : _.template($('#adtpl_item_template').html())
        // ע���¼�
        , events: {
            'click a.edit-link': 'editEvent'
        }

        // AdvView��ͼ���� model���¼��仯,������Ⱦ
        // **Advertiser** �� **AdvView** ��һһ��Ӧ�Ĺ�ϵ.
        , initialize : function() {
            this.model.bind('change', this.render, this);
            this.model.bind('destroy', this.remove, this);
        }
        // ������Ⱦ������������б���.
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
    
    // ����һ������ҳ�������еļ���
    var AdtplList = Backbone.PaginatedCollection.extend({
        'model': AdTemplate
        , 'url': App.router.url({
            'm' : 'template'
            , 'a' : 'rows'
        })
    });
    
    // �б����ʵ����
    var adtplList = new AdtplList();
    
    // ��ҳ������ʵ����
    var Paginator = Backbone.Paginator.extend({
        collection : adtplList
        , el : $('.pagination')
    });
    new Paginator();
    
    var MainView = Backbone.View.extend({
        el: $("body")
        , events: {
            
        }
        // ��ʼ���¼�
        , initialize: function() {
            // ��RESET�¼�����advList��������ʱȷ��onRenderList����
            this.listenTo(adtplList, 'reset', this.onRenderList);
            //this.listenTo(advList, 'change', this.onRenderList);
            
            // �ӷ�������ȡ������б�����
            adtplList.fetch();
        }
        // ��Ⱦ������б�
        , onRenderList: function() {
            this.$("#adtpl_list").empty();
            if(adtplList.length > 0) {
                adtplList.each(this.addAdtplRow, this);
            }
        }
        // ���һ�й����������
        , addAdtplRow: function(model) {
            var view = new AdtplItemView({
                'model' : model
            });
            this.$("#adtpl_list").append(view.render().el);
        }
    });

    new MainView();
});