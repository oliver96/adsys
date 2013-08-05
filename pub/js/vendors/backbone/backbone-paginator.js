Backbone.Paginator = (function ( Backbone, _, $ ) {
    'use strict';
    
    var Paginator = Backbone.View.extend({
        el: null
        , tpl: '<% if (totalPage > 0) { %>' +
            '<ul>' +
            '<% if(page > 1) { %>' +
            '<% if (showFirst == true) { %>' +
            '<li><a href="#" class="first">&#171;</a></li>' +
            '<% } %>' +
            '<li><a href="#" class="prev">&#8249;</a></li>' +
            '<% } %>' +
            '<% var mod = page % pageSize, startPage = 0; endPage = 0; %>' +
            '<% startPage = (mod == 0 ? page - pageSize : page - mod) + 1; %>' +
            '<% endPage = startPage + pageSize; %>' +
            '<% if(endPage > totalPage + 1) endPage = totalPage + 1; %>' +
            '<% for(var p = startPage; p < endPage; p ++) { %>' +
            '<% if(p == page) { %>' +
            '<li class="active"><a href="#" class="page"><%=p%></a></li>' +
            '<% } else { %>' +
            '<li><a href="#" class="page"><%=p%></a></li>' +
            '<% } %>' +
            '<% } %>' +
            '<% if(page > 0 && page < totalPage) { %>' +
            '<li><a href="#" class="next">&#8250;</a></li>' +
            '<% if(showLast == true) { %>' +
            '<li><a href="#" class="last">&#187;</a></li>' +
            '<% } %>' +
            '<% } %>' +
            '<% if (showInfo == true) { %>' +
            '<li><span class="page-info">第&nbsp;<%=page%>&nbsp;&#8260;&nbsp;<%=totalPage%>&nbsp;页</span></li>' +
            '<% } %>' +
            '</ul>' +
            '<% } %>'
        , template: null
        , events: {
            'click a.first': 'firstPage',
            'click a.prev': 'prevPage',
            'click a.page': 'changePage',
            'click a.next': 'nextPage',
            'click a.last': 'lastPage'
		}
        , initialize: function () {
            if(this.el == null) return;
            
            this.template = _.template(this.tpl);
            
			this.listenTo(this.collection, 'reset', this.render);
			this.listenTo(this.collection, 'change', this.render);
		}
        , render: function () {
			var html = this.template(this.collection.pageInfo);
			this.$el.html(html);
		}
        , firstPage: function(e) {
            e.preventDefault();
			this.collection.firstPage();
        }
        , prevPage: function(e) {
            e.preventDefault();
			this.collection.prevPage();
        }
        , changePage: function(e) {
            e.preventDefault();
            var page = parseInt($(e.target).text());
			this.collection.changePage(page);
        }
        , nextPage: function(e) {
            e.preventDefault();
            this.collection.nextPage();
        }
        , lastPage: function(e) {
            e.preventDefault();
			this.collection.lastPage();
        }
    });
    
    return Paginator
}( Backbone, _, jQuery ));