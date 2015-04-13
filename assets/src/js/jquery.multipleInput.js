(function ($) {
    $.fn.multipleInput = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.multipleInput');
            return false;
        }
    };

    var defaultOptions = {
        group_id: null,
        template: null,
        btn_action: null,
        btn_type: null,
        limit: 1,
        replacement: []
    };

    var methods = {
        init: function (options) {
            var settings = $.extend(defaultOptions, options || {});

            $(document).on('click.multipleinput', '.js-' + settings.group_id + '-input-remove', function (e) {
                e.preventDefault();
                methods.removeInput.apply(this);
            });

            $(document).on('click.multipleinput', '.js-'+ settings.group_id + '-input-plus', function (e) {
                e.preventDefault();
                methods.addInput.apply(this,[settings]);
            });

            $(function() {
                $('.multiple-input-list').find('input, select, textarea').each(function () {
                    methods.addAttribute.apply(this);
                });
            });
        },

        addInput: function (settings) {
            var template = settings.template,
                $wrapper = $(this).parents('.multiple-input-list').first(),
                index = $wrapper.find('.multiple-input-list__item').length,
                btn_action = settings.btn_action,
                btn_type = settings.btn_type,
                replacement = settings.replacement || [];

            if (settings.limit != null && index >= settings.limit) {
                return;
            }

            var search = ['{index}', '{btn_action}', '{btn_type}', '{value}'],
                replace = [index, btn_action, btn_type, ''];

            for (var i in search) {
                template = template.replaceAll(search[i], replace[i]);
            }

            for (var j in replacement) {
                template = template.replaceAll('{' + j + '}', replacement[j]);
            }

            console.log(template);
            $(template).hide().appendTo($wrapper).fadeIn(300);
            $(template).find('input, select, textarea').each(function () {
                methods.addAttribute.apply(this);
            });
        },

        removeInput: function () {
            var line = $(this).closest('.multiple-input-list__item');
            line.find('input, select, textarea').each(function () {
                methods.removeAttribute.apply(this);
            });
            line.fadeOut(300, function () {
                $(this).remove();
            });
        },

        addAttribute: function () {
            var id = $(this).attr('id');
            var list = methods.getAttributesList.apply(this);
            var isExists = false;

            for (var i in list) {
                if (list[i]['id'] == id) {
                    isExists = true;
                }
            }
            if (!isExists) {
                list.push({
                    'id': id,
                    'input': '#' + id,
                    'container': '.field-' + id,
                    'enableAjaxValidation': true
                });
            }
        },

        removeAttribute: function () {
            var id = $(this).attr('id');
            var list = methods.getAttributesList.apply(this);
            for (var i in list) {
                if (list[i]['id'] == id) {
                    delete list[id];
                    return;
                }
            }
        },

        getAttributesList: function () {
            var form = $('#' + $(this).attr('id')).closest('form');
            return form.data('yiiActiveForm')['attributes'];
        }
    };

    String.prototype.replaceAll = function(search, replace){
        return this.split(search).join(replace);
    };
})(window.jQuery);
