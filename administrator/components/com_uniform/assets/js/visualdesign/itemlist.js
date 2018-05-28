/**
 * @category Libraries
 * @package Unifom
 * @author JoomlaShine.com
 * @copyright JoomlaShine.com
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version $Id: itemlist.js 19013 2012-11-28 04:48:47Z thailv $
 * @link JoomlaShine.com
 */!function ($) {
    /**
     * JSNItemList class
     */
    function JSNItemList(options) {
        this.control = null;
        this.options = $.extend({
            name:'item-list',
            allowOther:false,
            listItems:[],
            multipleCheck:false,
            actionField:false,
            actionRecieptEmail:false,
            classHidden:''
        }, options);

        this.options.value = $.toJSON(this.options.listItems);
        this.template = '<div class="controls"><div class="jsn-buttonbar"><button id="items-list-edit" class="btn btn-small"><i class="icon-pencil"></i>Edit</button><button id="items-list-save" class="btn btn-small btn-primary"><i class="icon-ok"></i>Done</button></div>' +
        '<ul class="jsn-items-list ui-sortable">' +
        '{{each(i, val) listItems}}<li class="jsn-item ui-state-default jsn-iconbar-trigger">' +
        '<label class="{{if multipleCheck==true || multipleCheck=="true"}}checkbox{{else}}radio{{/if}}"><input class="${classHidden}" type="{{if multipleCheck==true || multipleCheck=="true"}}checkbox{{else}}radio{{/if}}" value="${val.text}" name="item-list" {{if val.checked == "true" || val.checked == true}}checked{{/if}} />${val.text}</label>' +
        '{{if actionField}}<div class="jsn-iconbar"><a class="element-action-edit" href="javascript:void(0)"><i class="icon-lightning"></i></a></div>{{/if}}{{if actionMoneyField}}<div class="jsn-iconbar"><a class="element-action-money-add" href="javascript:void(0)"><span class="add-on" style="font-size: 16px;font-weight:bold;">$</span></a></div>{{/if}}{{if actionRecieptEmail}}<div class="jsn-iconbar"><a class="element-action-reciept-email" href="javascript:void(0)"><i class="icon-pencil"></i></a></div>{{/if}}' +
        '</li>{{/each}}' +
        '</ul>' +
        '{{if allowOther}}<div class="other ui-sortable">' +
        '<div class="jsn-item ui-state-default">' +
        '<label class="{{if multipleCheck==true || multipleCheck=="true"}}checkbox{{else}}radio{{/if}}"><input class="${classHidden}" type="{{if multipleCheck==true || multipleCheck=="true"}}checkbox{{else}}radio{{/if}}" disabled="disabled" value="other" />Other</label>' +
        '<input type="text" value="" disabled="disabled" />' +
        '</div>' +
        '</div>{{/if}}</div>' +
        '<input type="hidden" name="${name}" value="json:${value}" id="${id}" />';
    }
    ;

    JSNItemList.prototype = {
        /**
         * Update list of items to hidden field
         * @return void
         */
        updateItems:function () {
            var items = this.control.find('input[type="checkbox"],input[type="radio"]');
            var listItems = [];
            items.each(function (index, item) {
                listItems.push({
                    text:item.value,
                    checked:item.checked
                });
            });
            //this.updateAction();
            $('#' + this.options.id).val('json:' + $.toJSON(listItems));
            $('#' + this.options.id).trigger('change');
        },
        updateAction:function () {
            var items = this.control.find('input[type="checkbox"],input[type="radio"]');
            var listItems = {};
            $(".jsn-action-active").remove();
            items.each(function (index, item) {
                var items = {}, checkActive = false;
                items.showField = $(this).attr("action-show-field");
                if (items.showField) {
                    items.showField = $.evalJSON(items.showField);
                    if (items.showField.length > 0) {
                        checkActive = true;
                    }
                }
                items.hideField = $(this).attr("action-hide-field");
                if (items.hideField) {
                    items.hideField = $.evalJSON(items.hideField);
                    if (items.hideField.length > 0) {
                        checkActive = true;
                    }
                }
                listItems[item.value] = items;
                if (checkActive) {
                    var jsnItem = $(item).parents(".jsn-item");
                    $(jsnItem).addClass("jsn-highlight");
                } else {
                    var jsnItem = $(item).parents(".jsn-item");
                    $(jsnItem).removeClass("jsn-highlight");
                }
            });
            $('#option-itemAction-hidden').val($.toJSON(listItems));
            $('#option-itemAction-hidden').trigger('change');
        },
        getBoxStyle:function (element) {
            var display = element.css('display')
            if (display == 'none') {
                element.css({
                    display:'block',
                    visibility:'hidden'
                });
            }
            var style = {
                width:element.width(),
                height:element.height(),
                outerHeight:element.outerHeight(),
                outerWidth:element.outerWidth(),
                offset:element.offset(),
                margin:{
                    left:parseInt(element.css('margin-left')),
                    right:parseInt(element.css('margin-right')),
                    top:parseInt(element.css('margin-top')),
                    bottom:parseInt(element.css('margin-bottom'))
                },
                padding:{
                    left:parseInt(element.css('padding-left')),
                    right:parseInt(element.css('padding-right')),
                    top:parseInt(element.css('padding-top')),
                    bottom:parseInt(element.css('padding-bottom'))
                }
            };
            element.css({
                display:display,
                visibility:'visible'
            });
            return style;
        },
        openActionSettings:function (btnInput) {
            $(".jsn-lock-screen").remove();
            $("body").append($("<div/>", {
                "class":"jsn-lock-screen",
                "style":"z-index: 999999; display: inline;"
            }));
            var self = this;
            $("#visualdesign-options-values .jsn-items-list .jsn-item.ui-state-edit").removeClass("ui-state-edit");
            $(btnInput).parents(".jsn-item").addClass("ui-state-edit");
            $(".control-list-action").remove();
            var container = $("#form-container"), listOptionPage = [], instance = container.data('visualdesign-instance'), content = "";
            var serialize = instance.serialize(true);
            if (serialize != "" && serialize != "[]") {
                content = $.toJSON(serialize);
            }
            $(" ul.jsn-page-list li.page-items").each(function () {
                listOptionPage.push([$(this).find("input").attr('data-id'), $(this).find("input").attr('value')]);
            });

            var dialog = $("<div/>", {
                'class':'control-list-action jsn-bootstrap',
                'id':"control-action"
            }).append(
                $("<div/>", {
                    "class":"popover left"
                }).css("display", "block").append($("<div/>", {
                    "class":"arrow"
                })).append($("<h3/>", {
                    "class":"popover-title",
                    "text":"Action settings"
                })).append(
                    $("<div/>", {
                        "class":"popover-content"
                    }).append(
                        $("<div/>", {"class":"jsn-bgloading", "id":"action-loading"}).append(
                            $("<i/>", {"class":"jsn-icon32 jsn-icon-loading"})
                        )
                    ).append(
                        $("<div/>", {"id":"accordion_content", "class":"hide"}).append(
                            $("<h3/>").append(
                                $("<a/>", {"href":"#"}).append("Show form field(s)")
                            )
                        ).append(
                            $("<div/>", {"id":"jsn-action-show"})
                        ).append(
                            $("<h3/>").append(
                                $("<a/>", {"href":"#"}).append("Hide form fields(s)")
                            )
                        ).append(
                            $("<div/>", {"id":"jsn-action-hide"})
                        )
                    )
                )
            )
            $(dialog).appendTo('body');
            var elmStyle = self.getBoxStyle($(dialog).find(".popover")),
                parentStyle = self.getBoxStyle($(btnInput)),
                position = {};
            position.left = parentStyle.offset.left - elmStyle.outerWidth;
            position.top = parentStyle.offset.top - (elmStyle.outerHeight / 2) + (parentStyle.outerHeight / 2);
            dialog.css(position).click(function (e) {
                e.stopPropagation();
            });

            $.ajax({
                type:"POST",
                dataType:'json',
                url:"index.php?option=com_uniform&view=form&task=form.savepage&tmpl=component&" + this.options.token + "=1",
                data:{
                    form_id:$("#jform_form_id").val(),
                    form_content:content,
                    form_page_name:$("#form-design-header").attr('data-value'),
                    form_list_page:listOptionPage
                },
                success:function () {
                    var listOptionPage = [];
                    var _this = this;
                    var token = $("#form_token").val();
                    $(" ul.jsn-page-list li.page-items").each(function () {
                        listOptionPage.push([$(this).find("input").attr('data-id'), $(this).find("input").attr('value')]);
                    });
                    $.ajax({
                        type:"POST",
                        dataType:'json',
                        url:"index.php?option=com_uniform&view=form&task=form.loadsessionfield&tmpl=component&" + token + "=1",
                        data:{
                            form_id:$("#jform_form_id").val(),
                            form_page_name:$("#form-design-header").attr('data-value'),
                            form_list_page:listOptionPage
                        },
                        success:function (response) {
                            var listFieldShow = $("<ul/>", {"class":"jsn-items-list"}), listFieldHide = $("<ul/>", {"class":"jsn-items-list"});
                            if (response) {
                                var actionShowField = $("#visualdesign-options-values .jsn-items-list .ui-state-edit input[name=item-list]").attr("action-show-field"),
                                    actionHideField = $("#visualdesign-options-values .jsn-items-list .ui-state-edit input[name=item-list]").attr("action-hide-field");
                                if (actionShowField) {
                                    actionShowField = $.evalJSON(actionShowField);
                                }
                                if (actionHideField) {
                                    actionHideField = $.evalJSON(actionHideField);
                                }
                                $.each(response, function (i, item) {
                                    if (self.options.identify != item.options.identify) {
                                        var checkedHideField = false, checkedShowField = false;
                                        if (actionShowField) {
                                            $.each(actionShowField, function (i, val) {
                                                if (val == item.identify) {
                                                    checkedShowField = true;
                                                }
                                            })
                                        }
                                        if (actionHideField) {
                                            $.each(actionHideField, function (i, val) {
                                                if (val == item.identify) {
                                                    checkedHideField = true;
                                                }
                                            })
                                        }
                                        if (item.options.hideField) {
                                            listFieldShow.append(
                                                $("<li/>", {"class":"jsn-item jsn-iconbar-trigger"}).append(
                                                    $("<label/>", {"class":"checkbox"}).append(
                                                        $("<input/>", {"type":"checkbox", "value":item.identify}).prop("checked", checkedShowField)
                                                    ).append(
                                                        item.options.label
                                                    )
                                                )
                                            )
                                        } else {
                                            listFieldHide.append(
                                                $("<li/>", {"class":"jsn-item jsn-iconbar-trigger"}).append(
                                                    $("<label/>", {"class":"checkbox"}).append(
                                                        $("<input/>", {"type":"checkbox", "value":item.identify}).prop("checked", checkedHideField)
                                                    ).append(
                                                        item.options.label
                                                    )
                                                )
                                            )
                                        }
                                    }
                                });
                            }
                            if ($(listFieldShow).html()) {
                                $(".control-list-action #jsn-action-show").append(listFieldShow);
                            } else {
                                $(".control-list-action #jsn-action-show").append($("<ul/>", {"class":"jsn-items-list"}).append(
                                    $("<div/>", {"class":"ui-state-disabled"}).append(
                                        self.options.language.JSN_UNIFORM_ALL_FORM_FIELD_ARE_DISPLAYED
                                    )
                                ));
                            }
                            if ($(listFieldHide).html()) {
                                $(".control-list-action #jsn-action-hide").append(listFieldHide);
                            } else {
                                $(".control-list-action #jsn-action-hide").append($("<ul/>", {"class":"jsn-items-list"}).append(
                                    $("<div/>", {"class":"ui-state-disabled"}).append(
                                        self.options.language.JSN_UNIFORM_ALL_FORM_FIELD_ARE_HIDDEN
                                    )
                                ));
                            }
                            $("#jsn-action-show input[type=checkbox]").change(function () {
                                var dataShowField = [];
                                $("#jsn-action-show input[type=checkbox]:checked").each(function () {
                                    dataShowField.push($(this).val());
                                });
                                $("#visualdesign-options-values .jsn-items-list .ui-state-edit input[name=item-list]").attr("action-show-field", $.toJSON(dataShowField));
                                self.updateAction();
                            });
                            $("#jsn-action-hide input[type=checkbox]").change(function () {
                                var dataHideField = [];
                                $("#jsn-action-hide input[type=checkbox]:checked").each(function () {
                                    dataHideField.push($(this).val());
                                });
                                $("#visualdesign-options-values .jsn-items-list .ui-state-edit input[name=item-list]").attr("action-hide-field", $.toJSON(dataHideField));
                                self.updateAction();
                            });
                            $(".control-list-action #accordion_content").accordion({
                                autoHeight:false
                            });
                            $("#action-loading").addClass("hide");
                            $("#accordion_content").removeClass("hide");
                            $(".jsn-lock-screen").remove();
                            $(document).click(function () {
                                dialog.remove();
                                $("#visualdesign-options-values .jsn-items-list .jsn-item.ui-state-edit").removeClass("ui-state-edit");
                            });
                        }
                    });
                }
            });
        },
        openActionRecieptEmail:function (btnInput) {

            var self = this;

            $("#visualdesign-options-values .jsn-items-list .jsn-item.ui-state-edit").removeClass("ui-state-edit");
            $(btnInput).parents(".jsn-item").addClass("ui-state-edit");
            $(".control-list-action").remove();

            var emailValue = '';
            var titleValue = '';
            var fieldValue = $("#visualdesign-options-values .jsn-items-list .jsn-item.ui-state-edit").find("input").val();


            if (fieldValue) {
                //fieldValue = fieldValue.match(/\[EMAIL:(.*)\]/);
                fieldValue = fieldValue.match(/^([^\[]*)\[EMAIL\:(.*)\]$/);

                if(fieldValue != null){
                    emailValue = fieldValue[2].trim();
                    titleValue = fieldValue[1].trim();
                }
            }

            var dialog = $("<div/>", {
                'class':'control-list-action jsn-bootstrap',
                'id':"control-action",
                'style':"min-height:140px;"
            }).append(
                $("<div/>", {
                    "class":"popover left"
                }).css("display", "block").append($("<div/>", {
                    "class":"arrow"
                })).append($("<h3/>", {
                    "class":"popover-title",
                    "text":"Edit Recipient Email"
                })).append(
                    $("<div/>", {
                        "class":"popover-content", "style":"height:110px"
                    }).append(
                        $("<div/>", {"id":"jsn-reciept-email","class":"control-group"}).append(
                            $("<label />",{"text":"Title: ", "class":"control-label"}).append(
                                $("<div />", {"class":"controls"}).append(
                                    $("<div/>", {"class": "jsn-inline"}).append(
                                        $("<input />", {"class": "jsn-input-medium-fluid", "type":"text","value":titleValue, "name":"title", "placeholder":"Doe John"})
                                    )
                                )
                            ).append(
                                $("<label />",{"text":"Email: ", "class":"control-label"}).append(
                                    $("<div />", {"class":"controls"}).append(
                                        $("<div/>", {"class": "jsn-inline"}).append(
                                            $("<input />", {"class": "jsn-input-medium-fluid", "type":"email","value":emailValue, "name":"email", "placeholder":"example@domain.com"})
                                        )
                                    )
                                )
                            )


                        )
                    )
                )
            )
            $(dialog).appendTo('body');
            var elmStyle = self.getBoxStyle($(dialog).find(".popover")),
                parentStyle = self.getBoxStyle($(btnInput)),
                position = {};
            position.left = parentStyle.offset.left - elmStyle.outerWidth;
            position.top = parentStyle.offset.top - (elmStyle.outerHeight / 2) + (parentStyle.outerHeight / 2);
            dialog.css(position).click(function (e) {
                e.stopPropagation();
            });
            $("#jsn-reciept-email input").focus();
            $("#jsn-reciept-email input").keyup(function () {
                var updateEmail, updateTitle, emailTitle, emailRecepient;
                $("#jsn-reciept-email input").each(function () {

                    emailTitle = $("#jsn-reciept-email input[name*='title']").val();
                    emailRecepient = $("#jsn-reciept-email input[name*='email']").val();
                    emailRecepient = '[EMAIL:'+emailRecepient+']';
                    if(emailTitle != null)
                    {
                        updateEmail =   emailTitle + ' ' + emailRecepient;
                    }

                    $(btnInput).parents('.jsn-item').find('input').val(updateEmail);
                    $(btnInput).parents('.jsn-item').find('span.text-value').text(updateEmail);
                    self.updateItems();
                });
            });

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "index.php?option=com_uniform&view=form&task=form.savepage&tmpl=component&" + this.options.token + "=1",
                success: function () {
                    $(document).click(function () {
                        dialog.remove();
                        $("#visualdesign-options-values .jsn-items-list .jsn-item.ui-state-edit").removeClass("ui-state-edit");
                    });
                }
            })
        },
        /**
         * Register event handling for elements
         * @return void
         */
        addEvents:function () {
            var self = this;
            var listItems = [];
            var itemChecked = [];
            this.control.find('a.jsn-ic-move').click(function () {
                return false;
            });
            this.control.find('ul.jsn-items-list input').click(function (e) {
                e.stopPropagation();
            });
            this.control.find('input[type="checkbox"],input[type="radio"]').change(function () {
                self.updateItems();
            });
            this.control.find(".element-action-edit").click(function (e) {
                self.openActionSettings($(this));
                e.stopPropagation();
            });
            this.control.find(".element-action-reciept-email").click(function (e) {
                self.openActionRecieptEmail($(this));
                e.stopPropagation();
            });
            this.control.find("#items-list-edit").click(function () {
                $(this).hide();
                listItems = [];
                itemChecked = [];
                self.control.find(".jsn-items-list .jsn-item").each(function () {
                    listItems.push($(this).find("input").val())
                    if ($(this).find("input").is(':checked')) {
                        itemChecked.push($(this).find("input:checked").val());
                    }
                });
                self.control.find(".jsn-items-list").hide().after(
                    $("<div/>", {
                        "id":"items-list-edit-content"
                    }).append(
                        $("<textarea/>", {
                            "class":"jsn-input-xxlarge-fluid",
                            "rows":"10",
                            "text":listItems.join("\r")
                        })));
                self.control.find("#items-list-save").show();
                self.control.find("#items-list-cancel").show();
                self.control.find("#items-list-edit-content textarea").focus();
            });
            self.control.find("#items-list-save").click(function (e) {
                var divItems = $(this).parent().parent();
                var valueItems = divItems.find("#items-list-edit-content textarea").val().split("\n");
                var classValue = self.options.multipleCheck ? "checkbox" : "radio";
                var addedItems = [];

                self.control.find(".jsn-items-list").html("");
                $.each(valueItems, function (key, value) {
                    if (value && addedItems.indexOf(value) == -1) {
                        addedItems.push(value);
                        var inputItem = "";
                        if ($.inArray(value, itemChecked) != -1) {
                            if (self.options.multipleCheck) {
                                inputItem = $("<input/>", {
                                    "type":"checkbox",
                                    "checked":"checked",
                                    "class":self.classHidden,
                                    "name":"item-list",
                                    "value":value
                                });
                            } else {
                                inputItem = $("<input/>", {
                                    "type":"radio",
                                    "checked":"checked",
                                    "class":self.classHidden,
                                    "name":"item-list",
                                    "value":value
                                });
                            }
                        } else {
                            if (self.options.multipleCheck) {
                                inputItem = $("<input/>", {
                                    "type":"checkbox",
                                    "name":"item-list",
                                    "class":self.classHidden,
                                    "value":value
                                });
                            } else {
                                inputItem = $("<input/>", {
                                    "type":"radio",
                                    "class":self.classHidden,
                                    "name":"item-list",
                                    "value":value
                                });
                            }
                        }
                        if (self.options.actionField) {
                            self.control.find(".jsn-items-list").append(
                                $("<li/>", {
                                    "class":"jsn-item ui-state-default jsn-iconbar-trigger"
                                }).append(
                                    $("<label/>", {
                                        "class":classValue
                                    }).append(inputItem).append(value)
                                ).append(
                                    $("<div/>", {"class":"jsn-iconbar"}).append(
                                        $("<a/>", {"class":"element-action-edit", href:"javascript:void(0)"}).append(
                                            $("<i/>", {"class":"icon-lightning"})
                                        )
                                    )
                                )
                            )
                        }
                        else if(self.options.actionRecieptEmail)
                        {
                            self.control.find(".jsn-items-list").append(
                                $("<li/>", {
                                    "class":"jsn-item ui-state-default jsn-iconbar-trigger"
                                }).append(
                                    $("<label/>", {
                                        "class":classValue
                                    }).append(inputItem).append($("<span />",{"class":"text-value","text":value}))
                                ).append(
                                    $("<div/>", {"class":"jsn-iconbar"}).append(
                                        $("<a/>", {"class":"element-action-reciept-email", href:"javascript:void(0)"}).append(
                                            $("<i/>", {"class":"icon-pencil"})
                                        )
                                    )
                                )
                            )
                        }
                        else {
                            self.control.find(".jsn-items-list").append(
                                $("<li/>", {
                                    "class":"jsn-item ui-state-default jsn-iconbar-trigger"
                                }).append(
                                    $("<label/>", {
                                        "class":classValue
                                    }).append(inputItem).append(value)
                                )
                            )
                        }

                    }
                });
                addedItems = [];
                self.control.find(".jsn-items-list").show();
                self.control.find("#items-list-save").hide();
                self.control.find("#items-list-cancel").hide();
                self.control.find("#items-list-edit").show();
                self.control.find("#items-list-edit-content textarea").remove();
                self.updateItems();
                self.control.find('a.jsn-ic-move').click(function () {
                    return false;
                });
                self.control.find('ul.jsn-items-list input').click(function (e) {
                    e.stopPropagation();
                });
                self.control.find('input[type="checkbox"],input[type="radio"]').change(function () {
                    self.updateItems();
                });
                self.control.find(".element-action-edit").click(function (e) {
                    self.openActionSettings($(this));
                    e.stopPropagation();
                });
                self.control.find(".element-action-reciept-email").click(function (e) {
                    self.openActionRecieptEmail($(this));
                    e.stopPropagation();
                });
                var itemAction = $("#visualdesign-options-values #option-itemAction-hidden").val();

                if (itemAction) {
                    itemAction = $.evalJSON(itemAction);
                }

                if (itemAction) {
                    var index = 1, listFieldAction = [], itemlist = [];

                    $.each(itemAction, function (i) {
                        listFieldAction.push(i);
                    });

                    $("#visualdesign-options-values .jsn-items-list .jsn-item input[name=item-list]").each(function () {
                        itemlist.push($(this).val());
                    });

                    $("#visualdesign-options-values .jsn-items-list .jsn-item input[name=item-list]").each(function () {
                        index++;
                        var inputItem = $(this), index2 = 1, tmpShowField = "", tmpHideField = "";

                        $.each(itemAction, function (i, item) {
                            index2++;
                            var valueInput = $(inputItem).val();
                            if (i == valueInput) {
                                $(inputItem).attr("action-show-field", $.toJSON(item.showField));
                                $(inputItem).attr("action-hide-field", $.toJSON(item.hideField));
                            }
                            else if (index == index2 && $.inArray(valueInput, listFieldAction) < 0 && $.inArray(i, itemlist) < 0) {
                                $(inputItem).attr("action-show-field", $.toJSON(item.showField));
                                $(inputItem).attr("action-hide-field", $.toJSON(item.hideField));
                            }
                        });
                    });
                    $("#visualdesign-options-values .jsn-items-list .jsn-item input[name=item-list]").each(function () {
                        var actionShowField = $(this).attr("action-show-field"), actionHideField = $(this).attr("action-hide-field"),checkAction = false;
                        if (actionShowField) {
                            actionShowField = $.evalJSON(actionShowField);
                            if(actionShowField && actionShowField.length > 0){
                                checkAction = true;
                            }
                        }
                        if (actionHideField) {
                            actionHideField = $.evalJSON(actionHideField);
                            if(actionHideField && actionHideField.length > 0){
                                checkAction = true;
                            }
                        }
                        if (checkAction) {
                            var jsnItem = $(this).parents(".jsn-item");
                            $(jsnItem).addClass("jsn-highlight");
                        } else {
                            var jsnItem = $(this).parents(".jsn-item");
                            $(jsnItem).removeClass("jsn-highlight");
                        }
                    });
                    self.updateAction();
                }
            });

        },
        /**
         * Render UI for control
         * @return void
         */
        render:function () {
            var self = this;
            this.control = $.tmpl(this.template, this.options);

            this.control.find('ul.jsn-items-list').sortable({
                items:'li.jsn-item',
                axis:'y',
                forceHelperSize:true,
                connectWith:'.jsn-item',
                placeholder:'ui-state-highlight',
                update:function () {
                    self.updateItems();
                    self.updateAction();
                }
            });
            this.addEvents();
            this.updateAction();
            return this.control;
        }
    };

    /**
     * Register jQuery plugin
     */
    $.itemList = function (options) {
        var control = new JSNItemList(options);
        return control.render();
    };
}(jQuery);