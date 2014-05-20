/*------------------------------------------------------------------------
 # Full Name of JSN UniForm
 # ------------------------------------------------------------------------
 # author    JoomlaShine.com Team
 # copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 # Websites: http://www.joomlashine.com
 # Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 # @license - GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 # @version $Id: form.js 19014 2012-11-28 04:48:56Z thailv $
 -------------------------------------------------------------------------*/
define([
    'jquery',
    'uniform/visualdesign/visualdesign',
    'uniform/uniform',
    'uniform/dialogedition',
    'uniform/help',
    'uniform/layout',
    'uniform/libs/select2/select2',
    'uniform/libs/colorpicker/js/colorpicker',
    'jsn/libs/modal',
    'jquery.json',
    'jquery.zeroclipboard',
    'jquery.ui' ],

    function ($, JSNVisualDesign, JSNUniform, JSNUniformDialogEdition, JSNHelp, JSNLayoutCustomizer) {
        var urlBase = "";
        var colorScheme;
        var JSNUniformFormView = function (params) {
            this.params = params;
            this.lang = params.language;
            this.formStyle = params.form_style;
            this.urlAction = params.urlAction;
            this.baseZeroClipBoard = params.baseZeroClipBoard;
            this.checkSubmitModal = params.checkSubmitModal;
            this.pageContent = params.pageContent;
            this.opentArticle = params.opentArticle;
            this.titleForm = params.titleForm;
            urlBase = params.urlBase;
            this.init();
        }
        var oldValuePage = $("#form-design-header").attr('data-value');
        JSNUniformFormView.prototype = {
            init:function () {
                var self = this;
                this.params.edition = 'free';
                this.visualDesign = new JSNVisualDesign('#form-container', this.params);
                this.JSNUniform = new JSNUniform(this.params, this.visualDesign);
                this.JSNUniformDialogEdition = new JSNUniformDialogEdition(this.params);
                this.JSNHelp = new JSNHelp();
                this.JSNLayoutCustomizer = new JSNLayoutCustomizer();
                this.selectPostAction = $("#jform_form_post_action");
                this.inputFormTitle = $("#jform_form_title");
                this.btnAddPageForm = $(".new-page");
                this.btnSelectFormStyle = $("#select_form_style");
                var idForm = $("#jform_form_id").val();
                colorScheme = $("#jform_form_theme").val();
                this.menuToolBar = $("#jsn-menu-item-toolbar-menu ul li a");
                this.menuToolBar.click(function (e) {
                    if (!$(this).hasClass("dropdown-toggle")) {
                        var selfLink = this;
                        $("#confirmSaveForm").remove();
                        $(this).after(
                            $("<div/>", {
                                "id":"confirmSaveForm"
                            }).append(
                                $("<div/>", {
                                    "class":"ui-dialog-content-inner jsn-bootstrap"
                                }).append(
                                    $("<p/>").append(self.lang['JSN_UNIFORM_CONFIRM_SAVE_FORM']))));
                        $("#confirmSaveForm").dialog({
                            height:200,
                            width:500,
                            title:"Confirm",
                            draggable:false,
                            resizable:false,
                            autoOpen:true,
                            modal:true,
                            buttons:{
                                Yes:function () {
                                    $(this).dialog("close");
                                    $("#redirectUrl").val($(selfLink).attr("href"));
                                    Joomla.submitbutton("form.apply");
                                    return false;
                                },
                                No:function () {
                                    $(this).dialog("close");
                                    window.location.href = $(selfLink).attr("href");
                                    return false;
                                }
                            }
                        });
                        return false;
                    }
                });
                $("li.action-save-show a").click(function () {
                    $("#redirectUrlForm").val($(this).attr("href"));
                    Joomla.submitbutton("form.apply");
                    return false;
                });
                $("#dialog-plugin").dialog({
                    height:300,
                    width:500,
                    title:self.lang['JSN_UNIFORM_LAUNCHPAD_PLUGIN_SYNTAX'],
                    draggable:false,
                    resizable:false,
                    autoOpen:false,
                    modal:true,
                    buttons:{
                        Close:function () {
                            $(this).dialog("close");
                        }
                    }
                });

                if (this.opentArticle == "open") {
                    this.opentAcrtileContent();
                }
                $("#article-content-plugin").click(function () {
                    $("#open-article").val("open");
                    Joomla.submitbutton("form.apply");
                })
                $(".jsn-tabs").tabs({
                    selected:0
                });
                var formAction = self.params.formAction ? self.params.formAction : 0;
                if (formAction && formAction != 0) {
                    $("#jform_form_post_action option").each(function () {
                        if (formAction == $(this).val()) {
                            var formActionData = self.params.formActionData;
                            $(this).prop('selected', true);
                            if (formAction == 1 || formAction == 4) {
                                $("#form_post_action_data" + formAction).val(formActionData);
                            }
                            if (formAction == 2 || formAction == 3) {
                                $("#form_post_action_data" + formAction).val(formActionData.id);
                                $("#fr" + formAction + "_form_action_data_title").val(formActionData.title);
                            }
                        }
                    })
                }

                this.selectPostAction.change(function () {
                    $('#postaction div[id^=form]').addClass("hide");
                    $('#form' + $(this).val()).removeClass("hide");
                    $("#action").val($(this).val());
                }).change();
                $("#jform_form_type").change(function () {
                    JSNUniformDialogEdition.createDialogLimitation($("#form-container"), self.lang["JSN_UNIFORM_YOU_HAVE_REACHED_THE_LIMITATION_OF_1_PAGE_IN_FREE_EDITION"]);
                    $("#jform_form_type option").each(function () {
                        if ($(this).val() == 1) {
                            $(this).prop('selected', true);
                        }
                    })
                })
                this.inputFormTitle.bind('keypress', function (e) {
                    if (e.keyCode == 13) {
                        return false;
                    }
                });
                //get menu item
                window.jsnGetSelectMenu = function (id, title, object, link) {
                    $("#form_post_action_data2").val(id);
                    $("#fr2_form_action_data_title").val(title);
                    $.closeModalBox();
                }
                // get article
                window.jsnGetSelectArticle = function (id, title, catid, object, link) {
                    $("#form_post_action_data3").val(id);
                    $("#fr3_form_action_data_title").val(title);
                    $.closeModalBox();
                }

                if (this.checkSubmitModal) {
                    window.parent.jQuery.getSetModal($("#jform_form_id").val());
                }
                window.parentSaveForm = function () {
                    $.parentSaveForm();
                }
                $.parentSaveForm = function () {
                    $(document).trigger("click");
                    var listOptionPage = [];
                    var listContainer = [];
                    $(" ul.jsn-page-list li.page-items").each(function () {
                        listOptionPage.push([$(this).find("input").attr('data-id'), $(this).find("input").attr('value')]);
                    });
                    $("#form-container .jsn-row-container").each(function () {
                        var listColumn = [];
                        $(this).find(".jsn-column-content").each(function () {
                            var dataContainer = {};
                            var columnName = $(this).attr("data-column-name");
                            var columnClass = $(this).attr("data-column-class");
                            dataContainer.columnName = columnName;
                            dataContainer.columnClass = columnClass;
                            listColumn.push(dataContainer);
                        });
                        listContainer.push(listColumn);
                    });
                    $.ajax({
                        type:"POST",
                        async:true,
                        url:"index.php?option=com_uniform&view=form&task=form.savepage&tmpl=component",
                        data:{
                            form_id:$("#jform_form_id").val(),
                            form_content:self.visualDesign.serialize(),
                            form_page_name:$("#form-design-header").attr('data-value'),
                            form_list_page:listOptionPage,
                            form_list_container:$.toJSON(listContainer)
                        },
                        success:function () {
                            if ($("#jform_form_title").val() == "") {
                                $(".jsn-tabs").tabs({
                                    selected:0
                                });
                                $("#jform_form_title").parent().parent().addClass("error");
                                $("#jform_form_title").focus();
                                alert('Please correct the errors in the Form');
                                return false;
                            } else {
                                $("#jsn-task").val("form.apply");
                                $("form#adminForm").submit();
                            }
                        }
                    });
                }
                if (this.urlAction != "component") {
                    Joomla.submitbutton = function (pressedButton) {
                        var listOptionPage = [];
                        var listContainer = [];
                        $(" ul.jsn-page-list li.page-items").each(function () {
                            listOptionPage.push([$(this).find("input").attr('data-id'), $(this).find("input").attr('value')]);
                        });
                        $("#form-container .jsn-row-container").each(function () {
                            var listColumn = [];
                            $(this).find(".jsn-column-content").each(function () {
                                var dataContainer = {};
                                var columnName = $(this).attr("data-column-name");
                                var columnClass = $(this).attr("data-column-class");
                                dataContainer.columnName = columnName;
                                dataContainer.columnClass = columnClass;
                                listColumn.push(dataContainer);
                            });
                            listContainer.push(listColumn);
                        });
                        $.ajax({
                            type:"POST",
                            async:true,
                            url:"index.php?option=com_uniform&view=form&task=form.savepage&tmpl=component",
                            data:{
                                form_id:$("#jform_form_id").val(),
                                form_content:self.visualDesign.serialize(),
                                form_page_name:$("#form-design-header").attr('data-value'),
                                form_list_page:listOptionPage,
                                form_list_container:$.toJSON(listContainer)
                            },
                            success:function () {
                                if (/^form\.(save|apply)/.test(pressedButton)) {
                                    if ($("#jform_form_title").val() == "") {
                                        $(".jsn-tabs").tabs({
                                            selected:0
                                        });
                                        $("#jform_form_title").parent().parent().addClass("error");
                                        $("#jform_form_title").focus();
                                        alert('Please correct the errors in the Form');
                                        return false;
                                    }
                                }
                                submitform(pressedButton);
                            }
                        });
                    };
                }
                $(".jsn-modal-overlay,.jsn-modal-indicator").remove();
                $("body").append($("<div/>", {
                    "class":"jsn-modal-overlay",
                    "style":"z-index: 1000; display: inline;"
                })).append($("<div/>", {
                    "class":"jsn-modal-indicator",
                    "style":"display:block"
                })).addClass("jsn-loading-page");
                this.loadPage('defaultPage');
                this.actionForm();
                if (this.titleForm) {
                    $("#jform_form_title").val(this.titleForm);
                }
                this.btnSelectFormStyle.click(function (e) {
                    self.dialogFormStyle($(this));
                    e.stopPropagation();
                });
                $("#jform_form_theme").select2({
                    formatResult:self.formatSelect2,
                    formatSelection:self.formatSelect2,
                    escapeMarkup:function (m) {
                        return m;
                    }
                });
                $("#form-design-content").attr('class', $("#form-design-content").attr('class').replace(/\bjsn-style[-_]*[^\s]+\b/, $("#jform_form_theme").val()));
                //  $("#form-design-content").attr("class", $("#jform_form_theme").val());
                $("#jform_form_style").change(function () {
                    if ($(this).val() == "form-horizontal") {
                        $("#form-design-content").addClass("form-horizontal");
                    } else {
                        $("#form-design-content").removeClass("form-horizontal");
                    }
                    //$("#form-design-content").attr("class",$(this).val())
                }).trigger("change")
                //self.changeTheme();
                $("#theme_action_add").click(function () {
                    $("#add-theme-select").removeClass("hide");
                    $("#form-select").addClass("hide");
                    $("#theme_action").addClass("hide");
                    $("#input_new_theme").focus().focus().bind('keypress', function (e) {
                        if (e.keyCode == 13) {
                            $("#btn_add_theme").trigger("click");
                            return false;
                        }
                        if (e.keyCode == 27) {
                            $("#btn_cancel_theme").trigger("click");
                        }
                    });
                    $(document).click(function () {
                        $("#btn_cancel_theme").trigger("click");
                    });
                });
                $("#btn_cancel_theme").click(function () {
                    $("#add-theme-select").addClass("hide");
                    $("#form-select").removeClass("hide");
                    $("#theme_action").removeClass("hide");
                    $("#input_new_theme").val("");
                });
                $("#btn_add_theme").click(function () {
                    var theme = $("#input_new_theme").val();
                    var check = false;
                    if (theme == "") {
                        return false;
                    }
                    $("#jform_form_theme option").each(function () {
                        if ($(this).val() == "jsn-style-" + theme) {
                            check = true;
                        }
                    });
                    if (check) {
                        alert(self.lang['JSN_UNIFORM_COLOR_CONFIRM_EXISTS']);
                        return false;
                    }
                    $("#jform_form_theme").append($("<option/>", {"value":"jsn-style-" + theme, "text":theme}));
                    $("#option_themes").append(
                        $("<input/>", {"class":"jsn-style-" + theme, "type":"hide", "name":"form_style[themes_style][" + theme + "]"})
                    ).append(
                        $("<input/>", {"value":theme, "type":"hide", "name":"form_style[themes][]"})
                    )
                    $("#add-theme-select").addClass("hide");
                    $("#form-select").removeClass("hide");
                    $("#theme_action").removeClass("hide");
                    $("#jform_form_theme").select2({
                        formatResult:self.formatSelect2,
                        formatSelection:self.formatSelect2,
                        escapeMarkup:function (m) {
                            return m;
                        }
                    });
                    $("#jform_form_theme").val("jsn-style-" + theme).prop('selected', true);
                    $("#jform_form_theme").trigger("change");
                    self.resetTheme("jsn-style-light");
                    $("#input_new_theme").val("");
                });
                $("#jform_form_theme").change(function () {
                    var theme = $(this).val();
                    var styleTheme = {};
                    $("#style_accordion_content input,#style_accordion_content select").each(function () {
                        var nameStyle = $(this).attr("name");
                        if (nameStyle) {
                            nameStyle = nameStyle.match(/form_style\[(.*?)\]/);
                            styleTheme[nameStyle[1]] = $(this).val();
                        }
                        $("#option_themes input[name$='[themes_style]["+colorScheme.replace("jsn-style-","")+"]']").val($.toJSON(styleTheme));
                    });
                    var optionTheme = $("#option_themes input[name$='[themes_style]["+theme.replace("jsn-style-","")+"]']").val();
                    if (optionTheme) {
                        var options = $.evalJSON(optionTheme);
                        $("#style_accordion_content input").each(function () {
                            if (!$(this).hasClass('select2-focusser')) {
                                var className = $(this).attr("id");
                                if (className) {
                                    var nameOption = className.replace("style_", "");
                                    $(this).val(options[nameOption]);
                                }
                            }
                        });
                        $("#style_accordion_content select").each(function () {
                            var className = $(this).attr("id");
                            var nameOption = className.replace("style_", "");
                            $(this).val(options[nameOption]).prop('selected', true);
                        });
                    } else {
                        if (theme == "jsn-style-light" || theme == "jsn-style-dark") {
                            self.resetTheme($("#jform_form_theme").val());
                        } else {
                            $("#style_accordion_content input[type=text]").each(function () {
                                $(this).val("");
                            });
                            $("#style_accordion_content select").each(function () {
                                $(this).eq(1).prop('selected', true);
                            });
                        }
                    }
                    $(".jsn-select-color").each(function () {
                        var inputParent = $(this).prev();
                        $(this).find("div").css("background-color", $(inputParent).val());
                        $(this).ColorPickerSetColor($(inputParent).val());
                    });
                    $("#form-design-content").attr('class', $("#form-design-content").attr('class').replace(/\bjsn-style[-_]*[^\s]+\b/, theme));
                    self.changeStyleInline();
                    self.actionTheme();
                    colorScheme = $(this).val();
                });

                $("#theme_action_refresh").click(function () {
                    if (confirm(self.lang['JSN_UNIFORM_COLOR_CONFIRM_RESET'])) {
                        self.resetTheme($("#jform_form_theme").val());
                    }
                });
                $("#jform_form_edit_submission0,#jform_form_edit_submission1").change(function () {
                    if ($("#jform_form_edit_submission1").is(':checked')) {
                        $("#jsn-select-user-group").removeClass("hide");
                    } else {
                        $("#jsn-select-user-group").addClass("hide");
                    }
                }).trigger("change");
                $("#theme_action_delete").click(function () {
                    if (confirm(self.lang['JSN_UNIFORM_COLOR_CONFIRM_DELETE'])) {
                        var valueSelectTheme = $("#jform_form_theme").val();
                        if (valueSelectTheme == "jsn-style-light" || valueSelectTheme == "jsn-style-dark") {
                            return false;
                        } else {
                            $("#jform_form_theme option:selected").each(function () {
                                if ($(this).val() != "jsn-style-light" && $(this).val() != "jsn-style-dark") {
                                    var classRemove = $(this).val();
                                    var valueRemove = classRemove.replace("jsn-style-", "");
                                    $("#option_themes input").each(function () {
                                        if ($(this).attr("class") == classRemove) {
                                            $(this).remove();
                                        }
                                        if ($(this).val() == valueRemove) {
                                            $(this).remove();
                                        }
                                    });
                                    $(this).remove();
                                }
                            });
                            $("#jform_form_theme").eq(1).prop('selected', true);
                            $("#jform_form_theme").trigger("change");
                        }
                    }
                });
                self.actionTheme();
                $("#jsn-add-container").click(function (e) {
                    self.addContainer(1);
                });
                $("#button_submit_color").change(function () {
                    if ($(".jsn-sortable-disable .form-actions button.jsn-form-submit").hasClass("hide")) {
                        $(".jsn-sortable-disable .form-actions button.jsn-form-submit").attr("class", "jsn-form-submit hide " + $(this).val());
                    } else {
                        $(".jsn-sortable-disable .form-actions button.jsn-form-submit").attr("class", "jsn-form-submit " + $(this).val());
                    }
                });
                $("#button_position").change(function(){
                    $(".jsn-sortable-disable .form-actions .btn-toolbar").attr("class", $(this).val());
                });
                $("select.jsn-select2").select2({
                    formatResult:self.formatButtonSelect2,
                    formatSelection:self.formatButtonSelect2,
                    minimumResultsForSearch:99,
                    escapeMarkup:function (m) {
                        return m;
                    }
                });
                if (!idForm) {
                    self.resetTheme($("#jform_form_theme").val());
                }
            },
            addContainer:function (count) {
                var listContainer = [];
                $("#form-container .jsn-row-container").each(function (j) {
                    $(this).find(".jsn-column-content").each(function (i) {
                        var columnName = $(this).attr("data-column-name");
                        columnName = columnName.split("_");
                        var column = columnName[1] ? columnName[1] : 1;
                        column = parseInt(column);
                        if (column && listContainer.indexOf(column) == -1) {
                            listContainer.push(parseInt(column));
                        }
                    })
                });
                var identify = 1;
                var c = 1;
                while ($.inArray(identify, listContainer) != -1) {
                    identify = parseInt(identify) + c;
                    c++;
                }
                if (identify >= 1) {
                    identify = "_" + identify;
                }

                var htmlContainer = "";
                htmlContainer = $("<div/>", {"class":"jsn-row-container row-fluid"});
                var positionsColumn = ['left', 'center', 'right'];
                for (i = 0; i < count; i++) {
                    var span = "span" + 12 / count;
                    htmlContainer.append(
                        $("<div/>", {"class":"jsn-column-container clearafter"}).append(
                            $("<div/>", {"class":"jsn-column " + span}).append(
                                $("<div/>", {"class":"thumbnail clearafter"}).append(
                                    $("<div/>", {"class":"jsn-column-content", "data-column-name":positionsColumn[i] + identify, "data-column-class":span}).append(
                                        $("<div/>", {"class":"jsn-handle-drag jsn-horizontal jsn-iconbar-trigger"}).append('<div class="jsn-iconbar layout"><a class="row-delete column" onclick="return false;" title="Delete column" href="#"><i class="icon-trash"></i></a></div>')
                                    ).append(
                                        $("<div/>", {"class":"jsn-element-container"})
                                    )
                                )
                            )
                        )
                    )
                }
                $("#jsn-add-container").before(htmlContainer);
                $(".jsn-row-container .jsn-add-more").remove();
                this.eventContainer();

            },
            formatButtonSelect2:function (state) {
                var imgName = state.id.split("-");
                return "<img class='imgSelect2' src='" + urlBase + "components/com_uniform/assets/images/icons-16/" + imgName[imgName.length - 1] + ".png'/>" + state.text;
            },
            formatSelect2:function (state) {
                var self = this, imgName = "";
                if (state.id.toLowerCase() == "jsn-style-dark" || state.id.toLowerCase() == "jsn-style-light") {
                    imgName = state.id.toLowerCase();
                } else {
                    imgName = "jsn-style-custom";
                }
                return "<img class='imgSelect2' src='" + urlBase + "components/com_uniform/assets/images/icons-16/" + imgName + ".png'/>" + state.text;
            },
            actionTheme:function () {
                var valueSelectTheme = $("#jform_form_theme").val();
                if (valueSelectTheme == "jsn-style-light" || valueSelectTheme == "jsn-style-dark") {
                    $("#theme_action_refresh").removeClass("hide");
                    $("#theme_action_delete").addClass("hide");
                } else {
                    $("#theme_action_refresh").addClass("hide");
                    $("#theme_action_delete").removeClass("hide");
                }
            },
            resetTheme:function (theme) {
                var self = this;
                $("#style_accordion_content input").val("");
                $("#form-design-content").attr('class', $("#form-design-content").attr('class').replace(/\bjsn-style[-_]*[^\s]+\b/, theme));
                if (theme == "jsn-style-light") {
                    $("#style_background_active_color").val("#FCF8E3");
                    $("#style_border_active_color").val("#FBEED5");
                    $("#style_text_color").val("#333333");
                    $("#style_font_size").val("14");
                    $("#style_message_error_text_color").val("#FFFFFF");
                    $("#style_message_error_background_color").val("#B94A48");
                    $("#style_field_background_color").val("#ffffff");
                    $("#style_field_shadow_color").val("");
                    $("#style_field_text_color").val("#666666");
                    $("#style_field_border_color").val("");
                    $("#style_padding_space").val(10);
                    $("#style_margin_space").val(0);
                    $("#style_border_thickness").val(0);
                    $("#style_rounded_corner_radius").val(0);
                    $("#style_font_type option:eq(0)").prop('selected', true).trigger("change");
                    $("#button_submit_color option:eq(1)").prop('selected', true).trigger("change");
                    $("#button_prev_color option:eq(0)").prop('selected', true).trigger("change");
                    $("#button_next_color option:eq(0)").prop('selected', true).trigger("change");
                    $("#button_position option:eq(0)").prop('selected', true).trigger("change");

                } else if (theme == "jsn-style-dark") {
                    $("#style_background_active_color").val("#444444");
                    $("#style_border_active_color").val("#666666");
                    $("#style_text_color").val("#C6C6C6");
                    $("#style_font_size").val("14");
                    $("#style_message_error_text_color").val("#FFFFFF");
                    $("#style_message_error_background_color").val("#B94A48");
                    $("#style_field_background_color").val("#000000");
                    $("#style_field_shadow_color").val("#000000");
                    $("#style_field_text_color").val("#333333");
                    $("#style_field_border_color").val("#111111");
                    $("#style_padding_space").val(10);
                    $("#style_margin_space").val(0);
                    $("#style_border_thickness").val(0);
                    $("#style_rounded_corner_radius").val(0);
                    $("#style_font_type option:eq(0)").prop('selected', true).trigger("change");
                    $("#button_submit_color option:eq(1)").prop('selected', true).trigger("change");
                    $("#button_prev_color option:eq(0)").prop('selected', true).trigger("change");
                    $("#button_next_color option:eq(0)").prop('selected', true).trigger("change");
                    $("#button_position option:eq(0)").prop('selected', true).trigger("change");
                }
                $(".jsn-select-color").each(function () {
                    var inputParent = $(this).prev();
                    $(this).find("div").css("background-color", $(inputParent).val());
                    $(this).ColorPickerSetColor($(inputParent).val());
                });
                self.changeStyleInline();
            },
            hexToRgb:function (h) {
                var r = parseInt((this.cutHex(h)).substring(0, 2), 16), g = ((this.cutHex(h)).substring(2, 4), 16), b = parseInt((this.cutHex(h)).substring(4, 6), 16)
                return r + ',' + b + ',' + b;
            },
            cutHex:function (h) {
                return (h.charAt(0) == "#") ? h.substring(1, 7) : h
            },
            changeStyleInline:function () {
                var self = this,
                    styleField = ".jsn-master #form-design-content .jsn-element-container .jsn-element .controls input,.jsn-master #form-design-content .jsn-element-container .jsn-element .controls select,.jsn-master #form-design-content .jsn-element-container .jsn-element .controls textarea{\n",
                    styleFormElement = ".jsn-master #form-design-content .jsn-element-container .jsn-element {\n",
                    styleActive = ".jsn-master #form-design-content .jsn-element-container .jsn-element.ui-state-edit {\n",
                    styleTitle = ".jsn-master #form-design-content .jsn-element-container .jsn-element .control-label {\n";
                $("#style_accordion_content input").each(function () {
                    var dataValue = $(this).attr("data-value");
                    var valueInput = $(this).val();
                    if (valueInput) {
                        if ($(this).attr("type") == "number") {
                            if (dataValue == "border") {
                                valueInput = valueInput + "px solid";
                            } else if (dataValue == "margin") {
                                valueInput = valueInput + "px 0px";
                            } else {
                                valueInput = valueInput + "px";
                            }
                        }
                        var dataType = $(this).attr("data-type");
                        switch (dataType) {
                            case "jsn-element":
                                if (dataValue) {
                                    var items = dataValue.split(",");
                                    if (items.length > 1) {
                                        $.each(items, function (value, key) {
                                            styleFormElement += key + ":" + valueInput + ";\n";
                                        });
                                    } else {
                                        styleFormElement += items + ":" + valueInput + ";\n";
                                    }
                                }
                                break;
                            case "ui-state-edit":
                                styleActive += dataValue + ":" + valueInput + ";\n";
                                break;
                            case "control-label":
                                styleTitle += dataValue + ":" + valueInput + ";\n";
                                break;
                            case "field":
                                if (dataValue == "background-color") {
                                    styleField += "background:" + valueInput + ";\n";
                                } else if (dataValue == "box-shadow") {
                                    valueInput = self.hexToRgb(valueInput);
                                    styleField += "box-shadow:0 1px 0 rgba(255, 255, 255, 0.1), 0 1px 7px 0 rgba(" + valueInput + ", 0.8) inset;\n";
                                } else {
                                    styleField += dataValue + ":" + valueInput + ";\n";
                                }
                                break;
                        }
                    }
                });
                styleFormElement += "}\n";
                styleActive += "}\n";
                styleTitle += "}\n";
                styleField += "}\n";
                $("#style_inline").html("<style>" + styleFormElement + styleActive + styleTitle + styleField + "</style>");
            },
            dialogFormStyle:function (_this) {
                var self = this;
                var dialog = $("#container-select-style"), parentDialog = $("#container-select-style").parent();
                dialog.width("500");
                $(dialog).appendTo('body');
                var elmStyle = JSNVisualDesign.getBoxStyle($(dialog)),
                    parentStyle = JSNVisualDesign.getBoxStyle($(_this)),
                    position = {};
                position.left = parentStyle.offset.left - elmStyle.outerWidth + parentStyle.outerWidth;
                //  position.left = parentStyle.offset.left + (parentStyle.outerWidth - elmStyle.outerWidth) / 2;
                position.top = parentStyle.offset.top + parentStyle.outerHeight;

                $(dialog).find(".arrow").css("left", elmStyle.outerWidth - (parentStyle.outerWidth / 2));
                dialog.css(position).click(function (e) {
                    e.stopPropagation();
                });
                $(".jsn-select-color").each(function () {
                    var inputParent = $(this).prev();
                    var selfColor = this;
                    $(this).find("div").css("background-color", $(inputParent).val());
                    $(this).ColorPicker({
                        color:$(inputParent).val(),
                        onChange:function (hsb, hex, rgb) {
                            $(selfColor).prev().val("#" + hex);
                            var idInput = $(selfColor).prev().attr("id");
                            $(selfColor).find("div").css("background-color", "#" + hex);
                            self.changeStyleInline();
                            var styleTheme = {};
                            $("#style_accordion_content input,#style_accordion_content select").each(function () {
                                var nameStyle = $(this).attr("name");
                                if (nameStyle) {
                                    nameStyle = nameStyle.match(/form_style\[(.*?)\]/);
                                    styleTheme[nameStyle[1]] = $(this).val();
                                }
                                $("#option_themes input[name$='[themes_style]["+colorScheme.replace("jsn-style-","")+"]']").val($.toJSON(styleTheme));
                            });
                        }
                    });
                });
                $("#style_accordion_content input,#style_accordion_content select").change(function () {
                    self.changeStyleInline();
                    var styleTheme = {};
                    $("#style_accordion_content input,#style_accordion_content select").each(function () {
                        var nameStyle = $(this).attr("name");
                        if (nameStyle) {
                            nameStyle = nameStyle.match(/form_style\[(.*?)\]/);
                            styleTheme[nameStyle[1]] = $(this).val();
                        }
                        $("#option_themes input[name$='[themes_style]["+colorScheme.replace("jsn-style-","")+"]']").val($.toJSON(styleTheme));
                    });
                });
                $(dialog).show();
                $("#container-select-style .popover").show();
                $(".jsn-input-number").keypress(function (e) {
                    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                        return false;
                    }
                });
                $(document).click(function (e) {
                    if ($(e.target).parents(".colorpicker").css('display') == 'block' || ( $(e.target).hasClass("colorpicker") && $(e.target).css('display') == 'block')) {
                        return false;
                    }
                    if ($(e.target).parent().parent().hasClass("ui-autocomplete")) {
                        return false;
                    }
                    if ($(e.target).parents(".ui-autocomplete").css('display') == 'block') {
                        return false;
                    }
                    if ($(dialog).css('display') != 'none') {
                        $(dialog).appendTo($(parentDialog));
                        dialog.hide();
                        dialog.width("0");
                    }
                });
            },
            actionForm:function () {
                var self = this;
                $(".form-actions  .jsn-iconbar a.element-edit").click(function () {
                    var sender = $(this).parents(".form-actions");
                    $(sender).addClass("ui-state-edit");
                    var type = "form-actions";
                    var params = {};
                    var action = $(this);
                    JSNVisualDesign.openOptionsBox(sender, type, params, action);
                    $("#option-btnNext-text").val($("#jform_form_btn_next_text").val()).keyup(function () {
                        var btnNext = $("#option-btnNext-text").val() ? $("#option-btnNext-text").val() : "Next";
                        $("#jform_form_btn_next_text").val(btnNext);
                        $(".form-actions .btn-toolbar .jsn-form-next").text(btnNext);
                        $("#button_next_color").parents(".control-group").find("label").text(btnNext);
                    });
                    $("#option-btnPrev-text").val($("#jform_form_btn_prev_text").val()).keyup(function () {
                        var btnPrev = $("#option-btnPrev-text").val() ? $("#option-btnPrev-text").val() : "Prev";
                        $("#jform_form_btn_prev_text").val(btnPrev);
                        $(".form-actions .btn-toolbar .jsn-form-prev").text(btnPrev);
                        $("#button_prev_color").parents(".control-group").find("label").text(btnPrev);
                    });
                    $("#option-btnSubmit-text").val($("#jform_form_btn_submit_text").val()).keyup(function () {
                        var btnSubmit = $("#option-btnSubmit-text").val() ? $("#option-btnSubmit-text").val() : "Submit";
                        $("#jform_form_btn_submit_text").val(btnSubmit);
                        $(".form-actions .btn-toolbar .jsn-form-submit").text(btnSubmit);
                        $("#button_submit_color").parents(".control-group").find("label").text(btnSubmit);
                    });
                });
                $(".settings-footer .jsn-iconbar a.element-delete").click(function () {
                    self.JSNUniformDialogEdition = new JSNUniformDialogEdition(self.params);
                    JSNUniformDialogEdition.createDialogLimitation($(this), self.lang["JSN_UNIFORM_YOU_CAN_NOT_HIDE_THE_COPYLINK"]);
                    return false;
                });
            },
            renderContainer:function (container) {
                $("#form-container .jsn-row-container").remove();
                if (container) {
                    var containerPage = $.evalJSON(container);
                    containerPage.reverse();

                    $.each(containerPage, function (i, value) {
                        var containerPageHtml = $("<div/>", {"class":"jsn-row-container row-fluid"});

                        $.each(value, function () {

                            $(containerPageHtml).append(
                                $("<div/>", {"class":"jsn-column-container clearafter"}).append(
                                    $("<div/>", {"class":"jsn-column " + this.columnClass}).append(
                                        $("<div/>", {"class":"thumbnail clearafter"}).append(
                                            $("<div/>", {"class":"jsn-column-content", "data-column-name":this.columnName, "data-column-class":this.columnClass}).append(
                                                $("<div/>", {"class":"jsn-handle-drag jsn-horizontal jsn-iconbar-trigger"}).append('<div class="jsn-iconbar layout"><a class="row-delete column" onclick="return false;" title="Delete column" href="#"><i class="icon-trash"></i></a></div>')
                                            ).append(
                                                $("<div/>", {"class":"jsn-element-container"})
                                            )
                                        )
                                    )
                                )
                            )
                        })
                        $("#form-container #page-loading").after($(containerPageHtml))
                    })
                } else {
                    $("#form-container #page-loading").after(
                        $("<div/>", {"class":"jsn-row-container row-fluid"}).append(
                            $("<div/>", {"class":"jsn-column-container clearafter"}).append(
                                $("<div/>", {"class":"jsn-column span12"}).append(
                                    $("<div/>", {"class":"thumbnail clearafter"}).append(
                                        $("<div/>", {"class":"jsn-column-content", "data-column-name":"left", "data-column-class":"span12"}).append(
                                            $("<div/>", {"class":"jsn-handle-drag jsn-horizontal jsn-iconbar-trigger"}).append('<div class="jsn-iconbar layout"><a class="row-delete column" onclick="return false;" title="Delete column" href="#"><i class="icon-trash"></i></a></div>')
                                        ).append(
                                            $("<div/>", {"class":"jsn-element-container"})
                                        )
                                    )
                                )
                            )
                        )
                    )
                }
                this.eventContainer();
            },
            eventContainer:function () {
                var self = this;
                $(".jsn-row-container .jsn-add-more").remove();
                self.visualDesign.init($(".jsn-row-container"));
                $(".jsn-iconbar a.add-container").parent().remove();
                $(".jsn-row-container").each(function (e) {
                    $(this).append(
                        $("<div/>", {"class":"jsn-iconbar jsn-vertical", "title":self.lang['JSN_UNIFORM_ADD_CONTAINER_COLUMN']}).append(
                            $("<a/>", {"href":"javascript:void(0);", "class":"add-container"}).append(
                                $("<i/>", {"class":"icon-plus"})
                            ).click(function () {
                                    if (!$(this).hasClass("disabled")) {
                                        var parentForm = $(this).parents(".jsn-row-container");
                                        var countColumn = $(parentForm).find(".jsn-column-container").length;
                                        if (countColumn < 3) {
                                            var positionsColumn = ['left', 'center', 'right'];

                                            var columnName = [];
                                            var columnCount = "";
                                            var numberClass = 0;
                                            var span = "span" + (12 / parseInt($(parentForm).find(".jsn-column-container").length + 1));

                                            $(parentForm).find(".jsn-column-container .jsn-column-content").each(function () {
                                                var dataColumn = $(this).attr("data-column-name");
                                                var columnClass = $(this).attr("data-column-class").replace("span", "");
                                                $(this).attr("data-column-class", span);
                                                var splitDataColumn = dataColumn.split("_");
                                                columnName.push(splitDataColumn[0]);
                                                columnCount = splitDataColumn[1] ? "_" + splitDataColumn[1] : "";
                                            });
                                            var i = 0;
                                            $.each(positionsColumn, function (j, val) {
                                                if (i == 0 && columnName[0] && columnName.indexOf(val) == -1) {
                                                    i++;
                                                    $(parentForm).find(".jsn-iconbar a.add-container").parent().before(
                                                        $("<div/>", {"class":"jsn-column-container clearafter"}).append(
                                                            $("<div/>", {"class":"jsn-column span12"}).append(
                                                                $("<div/>", {"class":"thumbnail clearafter"}).append(
                                                                    $("<div/>", {"class":"jsn-column-content", "data-column-name":this + columnCount, "data-column-class":span}).append(
                                                                        $("<div/>", {"class":"jsn-handle-drag jsn-horizontal jsn-iconbar-trigger"}).append('<div class="jsn-iconbar layout"><a class="row-delete column" onclick="return false;" title="Delete column" href="#"><i class="icon-trash"></i></a></div>')
                                                                    ).append(
                                                                        $("<div/>", {"class":"jsn-element-container"})
                                                                    )
                                                                )
                                                            )
                                                        )
                                                    )
                                                }
                                            });
                                            $(parentForm).find(".jsn-column-container .jsn-column").each(function () {
                                                $(this).attr('class', $(this).attr('class').replace(/\bspan\d+\b/, span));
                                            });
                                        }
                                    }
                                    self.eventContainer();
                                })
                        ).append(
                            $("<a/>", {"href":"javascript:void(0);", "title":self.lang['JSN_UNIFORM_DELETE_CONTAINER']}).append(
                                $("<i/>", {"class":"icon-trash"})
                            ).click(function () {
                                    if ($(this).parents(".jsn-row-container").find(".jsn-column-container .jsn-element").length > 0) {
                                        if (confirm(self.lang['JSN_UNIFORM_CONFIRM_DELETE_CONTAINER'])) {
                                            $(this).parents(".jsn-row-container").remove();
                                            self.eventContainer();
                                        }
                                    } else {
                                        $(this).parents(".jsn-row-container").remove();
                                        self.eventContainer();
                                    }
                                })
                        ).append(
                            $("<hr/>")
                        ).append(
                            $("<a/>", {"href":"javascript:void(0);", "title":self.lang['JSN_UNIFORM_MOVE_UP_CONTAINER'], "class":"jsn-move-up"}).append(
                                $("<i/>", {"class":"icon-chevron-up"})
                            ).click(function () {
                                    if (!$(this).hasClass("disabled")) {
                                        var prevContainer = $(this).parents(".jsn-row-container").prev(".jsn-row-container");
                                        if (prevContainer.length) {
                                            var temp = $(this).parents(".jsn-row-container").detach();

                                            temp.insertBefore($(prevContainer));
                                            self.eventContainer();
                                        }
                                    }
                                })
                        ).append(
                            $("<a/>", {"href":"javascript:void(0);", "title":self.lang['JSN_UNIFORM_MOVE_DOWN_CONTAINER'], "class":"jsn-move-down"}).append(
                                $("<i/>", {"class":" icon-chevron-down"})
                            ).click(function () {
                                    if (!$(this).hasClass("disabled")) {
                                        var nextContainer = $(this).parents(".jsn-row-container").next(".jsn-row-container");
                                        if (nextContainer.length) {
                                            var temp = $(this).parents(".jsn-row-container").detach();
                                            temp.insertAfter($(nextContainer));
                                            self.eventContainer();
                                        }
                                    }
                                })
                        )
                    )
                    $(".jsn-row-container.row-fluid").each(function () {
                        var selfContainer = this;
                        var formRow = $(this).find(".jsn-column-container").length;
                        if (formRow == 3) {
                            $(this).find("a.add-container").addClass("disabled");
                        } else {
                            $(this).find("a.add-container").removeClass("disabled");
                        }
                        if ($(this).prev(".jsn-row-container")[0]) {
                            $(this).find("a.jsn-move-up").removeClass("disabled");
                        } else {
                            $(this).find("a.jsn-move-up").addClass("disabled");
                        }
                        if ($(this).next(".jsn-row-container")[0]) {
                            $(this).find("a.jsn-move-down").removeClass("disabled");
                        } else {
                            $(this).find("a.jsn-move-down").addClass("disabled");
                        }
                        $(this).find(".jsn-iconbar a.row-delete").unbind('click');
                        $(this).find("a.row-delete").click(function () {
                            var actionDelete = function (selfContainer, _this) {
                                if ($(selfContainer).find(".jsn-column-container").length > 1) {
                                    var parentContainer = $(_this).parents(".jsn-row-container");
                                    var spanNumber = $(_this).parents(".jsn-column-container").find(".jsn-column-content").attr("data-column-class").replace("span", "");
                                    $(_this).parents(".jsn-column-container").remove();
                                    var span = "span" + (12 / parseInt($(parentContainer).find(".jsn-column-container").length));
                                    $(parentContainer).find(".jsn-column-container .jsn-column").each(function () {
                                        $(this).attr('class', $(this).attr('class').replace(/\bspan\d+\b/, span));
                                        $(this).find(".jsn-column-content").attr("data-column-class", span);
                                        $(this).find(".jsn-column").removeAttr("style");
                                    });
                                } else {
                                    $(selfContainer).remove();
                                }
                                self.eventContainer();
                            }
                            if ($(this).parents(".jsn-column-container").find(".jsn-element").length > 0) {
                                if (confirm(self.lang['JSN_UNIFORM_CONFIRM_DELETE_CONTAINER_COLUMN'])) {
                                    actionDelete(selfContainer, $(this));
                                }
                            } else {
                                actionDelete(selfContainer, $(this));
                            }
                        });
                        self.JSNLayoutCustomizer.init(this);
                    });
                });
            },
            //get data page
            loadPage:function (action) {
                var self = this;
                var listOptionPage = [];
                var listContainer = [];
                $(" ul.jsn-page-list li.page-items").each(function () {
                    listOptionPage.push([$(this).find("input").attr('data-id'), $(this).find("input").attr('value')]);
                });
                $("#form-container .jsn-row-container").each(function () {
                    var listColumn = [];
                    $(this).find(".jsn-column-content").each(function () {
                        var dataContainer = {};
                        var columnName = $(this).attr("data-column-name");
                        var columnClass = $(this).attr("data-column-class");
                        dataContainer.columnName = columnName;
                        dataContainer.columnClass = columnClass;
                        listColumn.push(dataContainer);
                    });
                    listContainer.push(listColumn);
                });
                $("#form-design-content #page-loading").show();
                $("#form-design-content .jsn-column-container ").hide();
                $(".jsn-page-actions").hide();
                $("#form-design-header .jsn-iconbar").css("display", "none");
                $.ajax({
                    type:"POST",
                    dataType:'json',
                    url:"index.php?option=com_uniform&view=form&task=form.loadpage&tmpl=component",
                    data:{
                        form_page_name:$("#form-design-header").attr('data-value'),
                        form_page_old_name:oldValuePage,
                        form_page_old_content:self.visualDesign.serialize(),
                        form_page_old_container:$.toJSON(listContainer),
                        form_id:$("#jform_form_id").val(),
                        form_list_page:listOptionPage,
                        join_page:action
                    },
                    success:function (response) {

                        self.renderContainer(response.containerPage);
                        if ($("#jform_form_id").val() > 0 && self.pageContent) {
                            var pageContent = JSON.parse(self.pageContent);
                            if (!response && action == "defaultPage" && $.inArray(oldValuePage, pageContent) != -1) {
                                location.reload();
                            }
                        }
                        self.visualDesign.clearElements();
                        if (response.dataField) {
                            var dataField = $.evalJSON(response.dataField);
                            self.visualDesign.setElements(dataField);
                        }
                        if (action == "join") {
                            $(".jsn-page-list li.page-items").each(function (index) {
                                if (index != 0) {
                                    $(this).remove();
                                }
                            })
                        }
                        if (action == 'defaultPage') {
                            JSNVisualDesign.emailNotification();
                            $("#adminForm").removeClass("hide");
                            $(".jsn-modal-overlay,.jsn-modal-indicator").remove();
                        }
                        $(".jsn-page-actions").show();
                        $("#form-design-content #page-loading").hide();
                        +$("body").removeClass("jsn-loading-page");
                        $("#form-design-content .jsn-column-container ").show();
                        $("#form-design-header .jsn-iconbar").css("display", "");
                    }
                });
                oldValuePage = $("#form-design-header").attr('data-value');
            },
            loadDefaultPage:function (value) {
                var self = this;
                $("ul.jsn-page-list li.page-items").each(function () {
                    if ($(this).attr("data-value") == value) {
                        var dataValue = $(this).attr("data-value");
                        var dataText = $(this).find("input").val();
                        $("#form-design-header").attr("data-value", dataValue);
                        $("#form-design-header .page-title h1").text(dataText);
                        return false;
                    }
                })
                self.loadPage('defaultPage');
            },
            opentAcrtileContent:function () {

                var self = this;
                var valuePlugin = "{uniform form=" + $("#jform_form_id").val() + "/}";
                $("#syntax-plugin").val(valuePlugin);
                $("#dialog-plugin").dialog("open");
                ZeroClipboard.moviePath = self.baseZeroClipBoard;
                var clipboard = new ZeroClipboard.Client();
                clipboard.glue('jsn-clipboard-button', 'dialog-plugin', {
                    "z-index":"9999"
                });
                clipboard.setText($("#syntax-plugin").val());
                $("#syntax-plugin").change(function () {
                    clipboard.setText($("#syntax-plugin").val());
                });
                clipboard.addEventListener('complete', function (client, text) {
                    if ($("#syntax-plugin").val() != '') {
                        $(".jsn-clipboard-checkicon").addClass('jsn-clipboard-coppied');
                        setTimeout(function () {
                            $(".jsn-clipboard-checkicon").delay(6000).removeClass('jsn-clipboard-coppied');
                        }, 2000);
                    }
                });
            }
        }
        return JSNUniformFormView;
    })