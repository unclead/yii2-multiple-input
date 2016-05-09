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

    var events = {
        /**
         * afterAddRow event is triggered after widget's initialization.
         * The signature of the event handler should be:
         *     function (event)
         * where event is an Event object.
         *
         */
        afterInit: 'afterInit',
        /**
         * afterAddRow event is triggered after successful adding new row.
         * The signature of the event handler should be:
         *     function (event)
         * where event is an Event object.
         *
         */
        afterAddRow: 'afterAddRow',
        /**
         * beforeDeleteRow event is triggered before row will be removed.
         * The signature of the event handler should be:
         *     function (event, row)
         * where event is an Event object and row is html container of row for removal
         *
         * If the handler returns a boolean false, it will stop removal the row.
         */
        beforeDeleteRow: 'beforeDeleteRow',

        /**
         * afterAddRow event is triggered after successful removal the row.
         * The signature of the event handler should be:
         *     function (event)
         * where event is an Event object.
         *
         */
        afterDeleteRow: 'afterDeleteRow'
    };

    var defaultOptions = {
        id: null,
        // the template of row
        template: null,
        // string that collect js templates of widgets which uses in the columns
        jsTemplates: [],
        // how many row has to renders
        limit: 1,
        // minimum number of rows
        min: 1
    };

    var defaultAttributeOptions = {
        enableAjaxValidation: false,
        validateOnBlur: false,
        validateOnChange: false,
        validateOnType: false
    };

    var methods = {
        init: function (options) {
            var settings = $.extend(true, {}, defaultOptions, options || {}),
                $wrapper = $('#' + settings.id),
                form = $wrapper.closest('form'),
                id = this.selector.replace('#', '');

            $wrapper.data('multipleInput', {
                settings: settings,
                currentIndex: 0,
                attributeDefaults: {}
            });


            $wrapper.on('click.multipleInput', '.js-input-remove', function (e) {
                e.preventDefault();
                removeInput($(this));
            });

            $wrapper.on('click.multipleInput', '.js-input-plus', function (e) {
                e.preventDefault();
                addInput($(this));
            });

            var intervalID = setInterval(function () {
                if (typeof form.data('yiiActiveForm') === 'object') {
                    var attribute = form.yiiActiveForm('find', id);
                    var attributeDefaults = [];
                    if (typeof attribute === 'object') {
                        $.each(attribute, function (key, value) {
                            if (['id', 'input', 'container'].indexOf(key) == -1) {
                                attributeDefaults[key] = value;
                            }
                        });
                        form.yiiActiveForm('remove', id);
                    }

                    var attributeOptions = $.extend({}, defaultAttributeOptions, settings.attributeOptions);
                    $.each(attributeOptions, function (key, value) {
                        if (typeof attributeDefaults[key] === 'undefined') {
                            attributeDefaults[key] = value;
                        }
                    });

                    $wrapper.data('multipleInput').attributeDefaults = attributeDefaults;

                    $wrapper.find('.multiple-input-list').find('input, select, textarea').each(function () {
                        addAttribute($(this));
                    });
                    $wrapper.data('multipleInput').currentIndex = $wrapper.find('.multiple-input-list__item').length;
                    clearInterval(intervalID);

                    var event = $.Event(events.afterInit);
                    $wrapper.trigger(event);
                }
            }, 100);
        },
        add: function (values) {
            addInput($(this), values);
        },
        remove: function (index) {
            var row = null;
            if (index) {
                row = $(this).find('.js-input-remove:eq(' + index + ')');
            } else {
                row = $(this).find('.js-input-remove').last();
            }
            removeInput(row);
        },
        clear: function () {
            $('.js-input-remove').each(function () {
                removeInput($(this));
            });
        }
    };

    var addInput = function (btn, values) {
        var $wrapper = $(btn).closest('.multiple-input').first(),
            data = $wrapper.data('multipleInput'),
            settings = data.settings,
            template = settings.template,
            inputList = $wrapper.find('.multiple-input-list').first(),
            count = $wrapper.find('.multiple-input-list__item').length;

        if (settings.limit != null && count >= settings.limit) {
            return;
        }

        template = template.replaceAll('{multiple_index}', data.currentIndex);

        $(template).hide().appendTo(inputList).fadeIn(300);

        if (values instanceof Object) {
            var tmp = [];
            for (var key in values) {
                if (values.hasOwnProperty(key)) {
                    tmp.push(values[key]);
                }
            }
            values = tmp;
        }

        var index = 0;
        $(template).find('input, select, textarea').each(function () {
            var that = $(this),
                tag = that.get(0).tagName,
                id = getInputId(that),
                obj = $('#' + id);

            if (values) {
                var val = values[index];

                if (tag == 'INPUT' || tag == 'TEXTAREA') {
                    obj.val(val);
                } else if (tag == 'SELECT') {
                    if (val && val.indexOf('option')) {
                        obj.append(val);
                    } else {
                        var option = obj.find('option[value="' + val + '"]');
                        if (option.length) {
                            obj.val(val);
                        }
                    }
                }
            }

            addAttribute(that);

            index++;
        });

        var jsTemplate;
        for (var i in settings.jsTemplates) {
            jsTemplate = settings.jsTemplates[i]
                .replaceAll('{multiple_index}', data.currentIndex)
                .replaceAll('%7Bmultiple_index%7D', data.currentIndex);
            window.eval(jsTemplate);
        }
        $wrapper.data('multipleInput').currentIndex++;

        var event = $.Event(events.afterAddRow);
        $wrapper.trigger(event);
    };

    var removeInput = function ($btn) {
        var $wrapper = $btn.closest('.multiple-input').first(),
            $toDelete = $btn.closest('.multiple-input-list__item'),
            count = $('.multiple-input-list__item').length,
            data = $wrapper.data('multipleInput'),
            settings = data.settings;

        if (count > settings.min) {
            var event = $.Event(events.beforeDeleteRow);
            $wrapper.trigger(event, [$toDelete]);
            if (event.result === false) {
                return;
            }

            $toDelete.find('input, select, textarea').each(function () {
                removeAttribute($(this));
            });

            $toDelete.fadeOut(300, function () {
                $(this).remove();

                event = $.Event(events.afterDeleteRow);
                $wrapper.trigger(event);
            });
        }
    };

    var addAttribute = function (input) {
        var id = getInputId(input);

        // skip if we could not get an ID of input
        if (id === null) {
            return;
        }

        var ele = $('#' + id),
            wrapper = ele.closest('.multiple-input').first(),
            form = ele.closest('form');


        // do not add attribute which are not the part of widget
        if (wrapper.length == 0) {
            return;
        }

        // check that input has been already added to the activeForm
        if (typeof form.yiiActiveForm('find', id) !== 'undefined') {
            return;
        }

        var data = wrapper.data('multipleInput');
        form.yiiActiveForm('add', $.extend({}, data.attributeDefaults, {
            'id': id,
            'input': '#' + id,
            'container': '.field-' + id
        }));
    };

    var removeAttribute = function () {
        var id = getInputId($(this));

        if (id === null) {
            return;
        }

        var form = $('#' + id).closest('form');

        if (form.length !== 0) {
            form.yiiActiveForm('remove', id);
        }
    };

    var getInputId = function ($input) {
        var id = $input.attr('id');

        if (typeof id === 'undefined') {
            id = $input.data('id');
        }

        if (typeof id === 'undefined') {
            return null;
        }

        return id;
    };

    String.prototype.replaceAll = function (search, replace) {
        return this.split(search).join(replace);
    };
})(window.jQuery);