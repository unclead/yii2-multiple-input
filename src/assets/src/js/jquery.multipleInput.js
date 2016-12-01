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
        max: 1,
        // minimum number of rows
        min: 1,
        attributes: {},
        indexPlaceholder: 'multiple_index'
    };

    var isActiveFormEnabled = false;

    var methods = {
        init: function (options) {
            var settings = $.extend(true, {}, defaultOptions, options || {}),
                $wrapper = $('#' + settings.id),
                form = $wrapper.closest('form'),
                id = this.selector.replace('#', '');

            $wrapper.data('multipleInput', {
                settings: settings,
                currentIndex: 0
            });


            $wrapper.on('click.multipleInput', '.js-input-remove', function (e) {
                e.stopPropagation();
                removeInput($(this));
            });

            $wrapper.on('click.multipleInput', '.js-input-plus', function (e) {
                e.stopPropagation();
                addInput($(this));
            });

            var i = 0,
                event = $.Event(events.afterInit);

            var intervalID = setInterval(function () {
                if (typeof form.data('yiiActiveForm') === 'object') {
                    var attribute = form.yiiActiveForm('find', id),
                        defaultAttributeOptions = {
                            enableAjaxValidation: false,
                            validateOnBlur: false,
                            validateOnChange: false,
                            validateOnType: false,
                            validationDelay: 500
                        };

                    // fetch default attribute options from active from attribute
                    if (typeof attribute === 'object') {
                        $.each(attribute, function (key, value) {
                            if (['id', 'input', 'container'].indexOf(key) == -1) {
                                defaultAttributeOptions[key] = value;
                            }
                        });

                        form.yiiActiveForm('remove', id);
                    }

                    // append default options to option from settings
                    $.each(settings.attributes, function (attribute, attributeOptions) {
                        attributeOptions = $.extend({}, defaultAttributeOptions, attributeOptions);
                        settings.attributes[attribute] = attributeOptions;
                    });

                    $wrapper.data('multipleInput').settings = settings;

                    $wrapper.find('.multiple-input-list').find('input, select, textarea').each(function () {
                        addAttribute($(this));
                    });

                    $wrapper.data('multipleInput').currentIndex = getCurrentIndex($wrapper);
                    isActiveFormEnabled = true;

                    clearInterval(intervalID);
                    $wrapper.trigger(event);
                } else {
                    i++;
                }

                // wait for initialization of ActiveForm a second
                // If after a second system could not detect ActiveForm it means
                // that widget is used without ActiveForm and we should just complete initialization of the widget
                if (form.length === 0 || i > 10) {
                    $wrapper.data('multipleInput').currentIndex = getCurrentIndex($wrapper);
                    isActiveFormEnabled = false;

                    clearInterval(intervalID);
                    $wrapper.trigger(event);
                }
            }, 100);
        },

        add: function (values) {
            addInput($(this), values);
        },

        remove: function (index) {
            var row = null;
            if (index != undefined) {
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
        },

        option: function(name, value) {
            value = value || null;

            var data = $(this).data('multipleInput'),
                settings = data.settings;
            if (value === null) {
                if (!settings.hasOwnProperty(name)) {
                    throw new Error('Option "' + name + '" does not exist');
                }
                return settings[name];
            } else if (settings.hasOwnProperty(name)) {
                settings[name] = value;
                data.settings = settings;
                $(this).data('multipleInput', data);
            }
        }
    };

    var addInput = function (btn, values) {
        var $wrapper = $(btn).closest('.multiple-input').first(),
            data = $wrapper.data('multipleInput'),
            settings = data.settings,
            template = settings.template,
            inputList = $wrapper.children('.multiple-input-list').first();

        if (settings.max != null && getCurrentIndex($wrapper) >= settings.max) {
            return;
        }

        template = template.replaceAll('{' + settings.indexPlaceholder + '}', data.currentIndex);

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

        var jsTemplate;

        for (var i in settings.jsTemplates) {
            jsTemplate = settings.jsTemplates[i]
                .replaceAll('{' + settings.indexPlaceholder + '}', data.currentIndex)
                .replaceAll('%7B' + settings.indexPlaceholder + '%7D', data.currentIndex);
            
            window.eval(jsTemplate);
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
                    if (val && val.indexOf('option') != -1) {
                        obj.append(val);
                    } else {
                        var option = obj.find('option[value="' + val + '"]');
                        if (option.length) {
                            obj.val(val);
                        }
                    }
                }
            }

            if (isActiveFormEnabled) {
                addAttribute(that);
            }

            index++;
        });

        $wrapper.data('multipleInput').currentIndex++;

        var event = $.Event(events.afterAddRow);
        $wrapper.trigger(event);
    };

    var removeInput = function ($btn) {
        var $wrapper = $btn.closest('.multiple-input').first(),
            $toDelete = $btn.closest('.multiple-input-list__item'),
            data = $wrapper.data('multipleInput'),
            settings = data.settings;

        if (getCurrentIndex($wrapper) > settings.min) {
            var event = $.Event(events.beforeDeleteRow);
            $wrapper.trigger(event, [$toDelete]);

            if (event.result === false) {
                return;
            }

            if (isActiveFormEnabled) {
                $toDelete.find('input, select, textarea').each(function () {
                    removeAttribute($(this));
                });
            }

            $toDelete.fadeOut(300, function () {
                $(this).remove();

                event = $.Event(events.afterDeleteRow);
                $wrapper.trigger(event, [$toDelete]);
            });
        }
    };

    /**
     * Add an attribute to ActiveForm.
     *
     * @param input
     */
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
        var bareID = id.replace(/-\d/, '').replace(/-\d-/, '');

        form.yiiActiveForm('add', $.extend({}, data.settings.attributes[bareID], {
            'id': id,
            'input': '#' + id,
            'container': '.field-' + id
        }));
    };

    /**
     * Removes an attribute from ActiveForm.
     */
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

    var getCurrentIndex = function($wrapper) {
        return $wrapper
            .children('.multiple-input-list')
            .children('tbody')
            .children('.multiple-input-list__item')
            .length;
    };

    String.prototype.replaceAll = function (search, replace) {
        return this.split(search).join(replace);
    };
})(window.jQuery);
