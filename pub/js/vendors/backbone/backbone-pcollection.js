Backbone.PaginatedCollection = (function ( Backbone, _, $ ) {
    'use strict';
    
    var PaginatedCollection = Backbone.Collection.extend({
        pageInfo: {
            totalPage: 0
            , page: 1
            , pageSize: 10
            , showFirst: true
            , showLast: true
            , showInfo: true
        }
        , queryOptions: {}
        , responseInfo: {}
        , sync: function ( method, model, options ) {
            if(!options.data) options.data = {};
            _.extend(options.data, this.pageInfo);
            this.queryOptions = options.data;
            options.type = 'POST'; // 以post进行提交
            return Backbone.sync.apply(this, [method, model, options]);
        }
        , parse: function(models, options) {
            if(this.model.localStorage || this.localStorage) {
                this.pageInfo.totalPage = Math.ceil(models.length / this.pageInfo.pageSize);
                if(this.pageInfo.totalPage < 0) {
                    this.pageInfo.totalPage = 0;
                    this.pageInfo.page = 1;
                }
                if(this.pageInfo.totalPage > 0 && this.pageInfo.page > this.pageInfo.totalPage) {
                    this.pageInfo.page = this.pageInfo.totalPage;
                }
                var tmpModels = [],
                    start = (this.pageInfo.page - 1) * this.pageInfo.pageSize,
                    end = this.pageInfo.pageSize + start;
                if(models.length < end) {
                    end = models.length;
                }
                
                for(var i = start; i < end; i ++) {
                    if(typeof models[i] != 'undefined') {
                        models[i]['__no__'] = i + 1;
                        tmpModels.push(models[i]);
                    }
                }
                
                return tmpModels;
            }
            else {
                this.pageInfo.totalPage = models.totalPage;
                for(var key in models) {
                    if(key == 'data') continue;
                    this.responseInfo[key] = models[key];
                }
                if(models.data) {
                    return models.data;
                }
                else {
                    return models;
                }
            }
        }
        , getInfo: function(key) {
            if(typeof this.responseInfo[key] != 'undefined') {
                return this.responseInfo[key];
            }
            return null;
        }
        , setPage: function(page) {
            this.pageInfo.page = page;
        }
        , firstPage: function() {
            this.setPage(1);
            this.fetch({data: this.queryOptions});
        }
        , lastPage: function() {
            this.setPage(this.pageInfo.totalPage);
            this.fetch({data: this.queryOptions});
        }
        , changePage: function(page) {
            if(page > 0 && page <= this.pageInfo.totalPage) {
                this.setPage(page);
                this.fetch({data: this.queryOptions});
            }
        }
        , prevPage: function() {
            var page = this.pageInfo.page - 1;
            if(page < 1) {
                page = 1;
            }
            this.setPage(page);
            this.fetch({data: this.queryOptions});
        }
        , nextPage: function() {
            var page = this.pageInfo.page + 1;
            if(page > this.pageInfo.totalPage) {
                page = this.pageInfo.totalPage;
            }
            this.setPage(page);
            this.fetch({data: this.queryOptions});
        }
    });
    
    return PaginatedCollection
}( Backbone, _, jQuery ));
