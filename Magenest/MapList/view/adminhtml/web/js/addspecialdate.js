/**
 * Created by keysnt on 21/04/2018.
 */
define([
    "jquery",
    "ko",
    "uiClass",
    "Magento_Ui/js/modal/modal",
    "underscore",
    'mage/calendar'
],function ($,ko,Class,modal,_,calendar) {
    ko.bindingHandlers.datepicker = {
        init: function(element, valueAccessor, allBindingsAccessor) {
            var $el = $(element);

            //initialize datepicker with some optional options
            var options = allBindingsAccessor().datepickerOptions || {};
            $el.datepicker(options);

            //handle the field changing
            ko.utils.registerEventHandler(element, "change", function() {
                var observable = valueAccessor();
                observable($(element).datepicker("getDate"));
            });

            //handle disposal (if KO removes by the template binding)
            ko.utils.domNodeDisposal.addDisposeCallback(element, function() {
                $el.datepicker("destroy");
            });

        },
    };
    function TempateSpecialDate(data) {
        var self = this;
        self.id = ko.observable(data.id);
        self.special_date = ko.observable(data.special_date);
        self.description = ko.observable(data.description);
    }
    function TemplateModel(config) {
        var self = this;
        self.templateSpecialDate = ko.observableArray([]);
        var templateData = ko.utils.parseJson(config.specialDate);
            var map = $.map(templateData, function (data) {
                return new TempateSpecialDate(data);
            });
        self.templateSpecialDate(map);
        self.defaultDate = ko.observable(new Date());
        self.addSpecialDate = function () {
                var optionIds = $.map(self.templateSpecialDate(), function (template) {
                    return template.id();
                });
                var maxId = 0;
                if (optionIds != '' || optionIds.length > 0) {
                    maxId = Math.max.apply(this, optionIds);
                    maxId++;
                }
                self.templateSpecialDate.push(new TempateSpecialDate({
                    id: maxId,
                    special_date: '',
                    description: ''
                }));
        }
        self.deleteSpecialDate = function (templateSpecialDate) {
            ko.utils.arrayForEach(self.templateSpecialDate(),function (template) {
               if(templateSpecialDate.id() == template.id()){
                    self.templateSpecialDate.destroy(template);
               }
            });
        }
    }
    return Class.extend({
        defaults: {
            template: "Magenest_MapList/options"
        },
        initObservable: function () {
            this._super();
            return this;
        },
        initialize: function (config) {
            this._super;
            var self = this;
            this.initConfig(config);
            this.bindAction(self);
            return this;
        },
        bindAction: function (self) {
            var config = self;
            ko.cleanNode(document.getElementById("mapspecialdate"));
            ko.applyBindings(new TemplateModel(config),document.getElementById("mapspecialdate"));
        }
    });
});
