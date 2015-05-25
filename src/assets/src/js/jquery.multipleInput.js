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
        id: null,
        template: null,
        jsTemplates: [],
        btn_action: null,
        btn_type: null,
        limit: 1,
        replacement: []
    };

    var methods = {
        init: function (options) {
            var settings = $.extend({}, defaultOptions, options || {}),
                wrapper = $('#' + settings.id),
                form = wrapper.closest('form'),
                id = this.selector.replace('#', '');

            wrapper.data('multipleInput', {
                settings: settings,
                currentIndex: 0,
                attributeDefaults: {}
            });

            $(document).on('click.multipleInput', '#' + settings.id + ' .js-input-remove', function (e) {
                e.preventDefault();
                methods.removeInput.apply(this);
            });

            $(document).on('click.multipleInput', '#' + settings.id + ' .js-input-plus', function (e) {
                e.preventDefault();
                methods.addInput.apply(this);
            });

            var intervalID = setInterval(function(){
                if (typeof form.data('yiiActiveForm') === 'object') {
                    var attribute = form.yiiActiveForm('find', id);
                    if (typeof attribute === 'object') {
                        $.each(attribute, function (key, value) {
                            if (['id', 'input', 'container'].indexOf(key) == -1) {
                                wrapper.data('multipleInput').attributeDefaults[key] = value;
                            }
                        });
                        form.yiiActiveForm('remove', id);
                    }

                    wrapper.find('.multiple-input-list').find('input, select, textarea').each(function () {
                        methods.addAttribute.apply(this);
                    });
                    wrapper.data('multipleInput').currentIndex = wrapper.find('.multiple-input-list__item').length;
                    clearInterval(intervalID);
                }
            }, 100);
            wrapper.trigger('init');

        },

        addInput: function () {
            var wrapper     = $(this).closest('.multiple-input').first(),
                data        = wrapper.data('multipleInput'),
                settings    = data.settings,
                template    = settings.template,
                inputList   = wrapper.find('.multiple-input-list').first(),
                count       = wrapper.find('.multiple-input-list__item').length,
                replacement = settings.replacement || [];

            if (settings.limit != null && count >= settings.limit) {
                return;
            }
            var search = ['{index}', '{btn_action}', '{btn_type}', '{value}'],
                replace = [data.currentIndex, settings.btn_action, settings.btn_type, ''];

            for (var i in search) {
                template = template.replaceAll(search[i], replace[i]);
            }

            for (var j in replacement) {
                template = template.replaceAll('{' + j + '}', replacement[j]);
            }

            $(template).hide().appendTo(inputList).fadeIn(300);
            $(template).find('input, select, textarea').each(function () {
                methods.addAttribute.apply(this);
            });

            var jsTemplate;
            for (i in settings.jsTemplates) {
                jsTemplate = settings.jsTemplates[i].replaceAll('{index}', data.currentIndex);
                window.eval(jsTemplate);
            }
            wrapper.data('multipleInput').currentIndex++;
            wrapper.trigger('addNewRow');
        },

        removeInput: function () {
            var wrapper = $(this).closest('.multiple-input').first(),
                line = $(this).closest('.multiple-input-list__item');
            line.find('input, select, textarea').each(function () {
                methods.removeAttribute.apply(this);
            });
            line.fadeOut(300, function () {
                $(this).remove();
            });
            wrapper.trigger('removeRow');
        },

        addAttribute: function () {
            var id = $(this).attr('id'),
                ele = $('#' + $(this).attr('id')),
                wrapper = ele.closest('.multiple-input').first(),
                form = ele.closest('form');

            form.yiiActiveForm('add', $.extend(wrapper.data('multipleInput').attributeDefaults, {
                'id': id,
                'input': '#' + id,
                'container': '.field-' + id
            }));
        },

        removeAttribute: function () {
            var id = $(this).attr('id');
            var form = $('#' + $(this).attr('id')).closest('form');
            form.yiiActiveForm('remove', id);
        }

    };

    String.prototype.replaceAll = function(search, replace){
        return this.split(search).join(replace);
    };
})(window.jQuery);