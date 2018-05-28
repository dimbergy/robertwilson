/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
define([
    'jquery',
    'uniform/help',
    'uniform/libs/googlemaps/jquery.ui.map',
    'uniform/libs/googlemaps/jquery.ui.map.services',
    'uniform/libs/googlemaps/jquery.ui.map.extensions',
    'jquery.json'],

    function ($, JSNHelp) {
        var JSNUniformSubmissionView = function (params) {
            this.params = params;
            this.lang = params.language;
            this.nextAndPreviousForm = params.nextAndPreviousForm;
            this.init();
        }
        JSNUniformSubmissionView.prototype = {
            init:function () {
                var self = this;
                this.JSNHelp = new JSNHelp();
                $("#jsn-submission-edit").click(function () {
                    $(this).addClass("hide");
                    $("#jsn-submission-save").removeClass("hide");
                    $("#jsn-submission-cancel").removeClass("hide");
                    $("dl.submission-page-content").addClass("hide");
                    $("div.submission-page-content").removeClass("hide");
                });
                $('#jsn-submission-print').click(function(){   //bind handlers
                    var url = $(this).attr('href');
                    window.open(url, "MsgWindow", "width="+window.screen.width*0.5+", height="+window.screen.height*0.5+",scrollbars=yes");
                });


                $("#jsn-submission-save").click(function () {
                    $(".submission-content .submission-page .submission-page-content input").each(function () {
                        var key = $(this).attr("dataValue");
                        var type = $(this).attr("typeValue");
                        //  $(this).attr("oldValue", $(this).val());
                        if (type != "email") {
                            $("dd#sd_" + key).html(self.htmlEntities($(this).val()));
                        } else {
                            if ($(this).val()) {
                                $("dd#sd_" + key + " a").html(self.htmlEntities($(this).val()));
                            } else {
                                $("dd#sd_" + key + " a").html("N/A");
                            }
                        }
                    });
                    $(".submission-content .submission-page .submission-page-content .jsn-likert").each(function () {
                        var likertSettings = $.evalJSON($(this).find(".jsn-likert-settings").val());
                        var fieldId = $(this).find(".jsn-likert-settings").attr("data-value");
                        var html = [];
                        $(this).find("tbody tr td input:checked").each(function(){
                            var inputSelf = this;
                            $.each(likertSettings.rows,function(i,setting){
                                if(setting.text == $(inputSelf).attr("data-value")){
                                    html.push('<strong>'+setting.text+':</strong>'+$(inputSelf).val());
                                }
                            });
                        });
                        $("dd#" + fieldId).html(html.join("<br/>"));
                    });
                    $(".submission-content .submission-page .submission-page-content textarea").each(function () {
                        var key = $(this).attr("dataValue");
                        $(this).attr("oldValue", self.htmlEntities($(this).val()));
                        if ($(this).val()) {
                            var value = $(this).val().split("\n");
                            $("dd#sd_" + key).html(self.htmlEntities(value.join("<br/>")));
                        } else {
                            $("dd#sd_" + key).html("N/A");
                        }
                    });
                    $(this).addClass("hide");
                    $("#jsn-submission-cancel").addClass("hide");
                    $("#jsn-submission-edit").removeClass("hide");
                    $("dl.submission-page-content").removeClass("hide");
                    $("div.submission-page-content").addClass("hide");
                });
                $(".jsn-page-actions .prev-page").click(function () {
                    self.prevpaginationPage();
                });
                $(".jsn-page-actions .next-page").click(function () {
                    self.nextpaginationPage();
                });
                $("#jform_form_type option").each(function () {
                    if ($(this).val() == $("#jform_form_type").attr("data-value")) {
                        $(this).prop("selected", true);
                    } else {
                        $(this).prop("selected", false);
                    }
                });
                if (this.nextAndPreviousForm.next) {
                    $("#next-submission").show().click(function () {
                        window.location = "index.php?option=com_uniform&view=submission&submission_id=" + self.nextAndPreviousForm.next + "&layout=detail";
                    });
                } else {
                    $("#next-submission").hide();
                }

                if (this.nextAndPreviousForm.previous) {
                    $("#previous-submission").show().click(function () {
                        window.location = "index.php?option=com_uniform&view=submission&submission_id=" + self.nextAndPreviousForm.previous + "&layout=detail";
                    });
                } else {
                    $("#previous-submission").hide();
                }
                $("#jform_form_type").change(function () {
                    if ($(this).val() == 2) {
                        $(".jsn-page-actions").show();
                        $(".jsn-section-content div.submission-page").hide();
                        $($(".jsn-section-content div.submission-page")[0]).show();
                        $(".jsn-section-content hr").remove();
                        $(".jsn-section-content .submission-content .jsn-page-actions button").show();
                        self.checkPage();
                    } else if ($(this).val() == 1) {
                        $(".jsn-page-actions").hide();
                        $(".jsn-section-content div.submission-page").show();
                        $(".jsn-section-content div.submission-page").each(function (i) {
                            if (i != 0) {
                                $(this).before("<hr/>");
                            }
                        });
                        self.checkPage();
                    }
                }).change();
                if (!$("#jform_form_type").attr("data-value")) {
                    $(".jsn-page-actions").hide();
                    $(".jsn-section-content div.submission-page").show();
                }
                $($(".jsn-section-content div.submission-page")[0]).show();
                this.checkPage();
            },
			htmlEntities:function(str) {
				return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
			},
            checkPage:function () {
                $(".jsn-section-content div.submission-page").each(function (i) {
                    if (!$(this).is(':hidden')) {
                        if ($(this).next().attr("data-value")) {
                            $(".jsn-page-actions .next-page").removeAttr("disabled");
                        } else {
                            $(".jsn-page-actions .next-page").attr("disabled", "disabled");
                        }
                        if ($(this).prev().attr("data-value")) {
                            $(".jsn-page-actions .prev-page").removeAttr("disabled");
                        } else {
                            $(".jsn-page-actions .prev-page").attr("disabled", "disabled");
                        }
                        $(this).find(".content-google-maps").each(function () {
                            $(this).find('.google_maps').width($(this).attr("data-width"));
                            $(this).find('.google_maps').height($(this).attr("data-height"));
                            var dataValue = $(this).attr("data-value");
                            var dataMarker = $(this).attr("data-marker");
                            if (dataValue) {
                                var gmapOptions = $.evalJSON(dataValue);
                                if (dataMarker) {
                                    var gmapMarker = $.evalJSON(dataMarker);
                                }
                                if (!gmapOptions.center.nb && gmapOptions.center.lb) {
                                    gmapOptions.center.nb = gmapOptions.center.lb;
                                }
                                if (!gmapOptions.center.ob && gmapOptions.center.mb) {
                                    gmapOptions.center.ob = gmapOptions.center.mb;
                                }
                                $(this).find('.google_maps').gmap({'zoom':gmapOptions.zoom, 'mapTypeId':gmapOptions.mapTypeId, 'center':gmapOptions.center.nb + ',' + gmapOptions.center.ob, 'disableDefaultUI':false, 'callback':function (map) {
                                    var self = this;
                                    self.set('inforWindow', function (marker, val) {
                                        var descriptions = val.descriptions;
                                        var content = '<div class="thumbnail">';
                                        if (val.images) {
                                            content += '<img  src="' + val.images + '">';
                                        }
                                        content += '<div class="caption">';
                                        if (val.title) {
                                            content += '<h4>' + val.title + '</h4>';
                                        }
                                        if (descriptions) {
                                            content += '<p>' + descriptions.replace(new RegExp('\n', 'g'), "<br/>") + '</p>';
                                        }

                                        if (val.link) {
                                            content += '<p><a target="_blank" href="' + val.link + '">more info</a></p>';
                                        }
                                        content += '</div></div>';
                                        self.openInfoWindow({ 'content':content}, marker);
                                    });
                                    self.get('map').setOptions({streetViewControl:false});
                                    if (gmapMarker) {
                                        $.each(gmapMarker, function (i, val) {
                                            var position = $.evalJSON(val.position);
                                            if (position) {
                                                if (!position.nb && position.lb) {
                                                    position.nb = position.lb;
                                                }
                                                if (!position.ob && position.mb) {
                                                    position.ob = position.mb;
                                                }
                                                self.addMarker({'position':position.nb + "," + position.ob, 'draggable':false, 'bounds':false},function (map, marker) {
                                                    if (val.open == "true") {
                                                        self.get('inforWindow')(marker, val);
                                                    }
                                                    if (val.title) {
                                                        marker.setTitle(val.title);
                                                    }
                                                }).xclick(function (event) {
                                                        self.get('inforWindow')(this, val);
                                                    })
                                            }

                                        });
                                    }

                                    setTimeout(function () {
                                        self.get('map').setCenter(self._latLng(gmapOptions.center.nb + ',' + gmapOptions.center.ob));
                                        self.get('map').setZoom(gmapOptions.zoom);
                                        self.get('map').setMapTypeId(gmapOptions.mapTypeId);
                                    }, 1000);

                                }});

                            }
                        });
                    }
                });
            },
            nextpaginationPage:function () {
                var self = this;
                $(".jsn-section-content div.submission-page").each(function () {
                    if (!$(this).is(':hidden')) {
                        $(this).hide();
                        $(this).next().show();
                        self.checkPage();
                        return false;
                    }
                });
            },
            prevpaginationPage:function () {
                var self = this;
                $(".jsn-section-content div.submission-page").each(function () {
                    if (!$(this).is(':hidden')) {
                        $(this).hide();
                        $(this).prev().show();
                        self.checkPage();
                        return false;
                    }
                });
            }
        }
        return JSNUniformSubmissionView;
    });