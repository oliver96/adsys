Backbone.CommonModel = (function ( Backbone, _, $ ) {
    'use strict';
    
    var CommonModel = Backbone.Model.extend({
        patterns: {
            // 特别字符
            'specialCharacters' : '[^a-zA-Z 0-9]+'
            // 数字
            , 'digits' : '[0-9]'
            // 邮件地址
            , 'email' : '^[a-zA-Z0-9._-]+@[a-zA-Z0-9][a-zA-Z0-9.-]*[.]{1}[a-zA-Z]{2,6}$'
        }
        
        , pattern : function(value, pattern) {
            var regExp = RegExp(pattern);
            return regExp.test(value);
        }

        // Validators
        , validators: {
            // 最小长度
            minLength: function(value, minLength) {
                return value.length >= minLength;
            }
            // 最在长度
            , maxLength: function(value, maxLength) {
                return value.length <= maxLength;
            }
            // 是否是邮件格式
            , isEmail: function(value) {
                return CommonModel.prototype.pattern(value, CommonModel.prototype.patterns.email);
            }
            // 包含特别字符串
            , hasSpecialCharacter: function(value) {
                return CommonModel.prototype.pattern(value, CommonModel.prototype.patterns.specialCharacters);
            }
        }
        , addError : function(elid, message) {
            var $wrapper = $('#' + elid).closest('.control-group')
                          .addClass('error')
                          .find('.controls')
                , $errorEl = $wrapper.find('.help-inline');
            
            if($errorEl.length > 0) {
                $errorEl.text(message);
            }
            else {
                $wrapper.append('<span class="help-inline">' +  message + '</span>')
            }
        }
        , addErrors : function(messages) {
            for(var elid in messages) {
                this.addError(elid, messages[elid]);
            }
        }
        , removeError : function(elid) {
            var $wrapper = $('#' + elid).closest('.control-group');
            if($wrapper.hasClass('error')) {
                $wrapper.find('.help-inline').remove();
            }
        }
        , removeErrors : function() {
            var $els = $('.control-group.error');
            for(var i = 0, l = $els.length; i < l; i ++) {
                $($els[i]).removeClass('error').find('.help-inline').remove();
            }
        }
    });
    
    return CommonModel;
}( Backbone, _, jQuery ));


