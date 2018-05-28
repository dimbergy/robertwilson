/*------------------------------------------------------------------------
 # Full Name of JSN MOBILIZE
 # ------------------------------------------------------------------------
 # author    JoomlaShine.com Team
 # copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 # Websites: http://www.joomlashine.com
 # Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 # @license - GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 # @version $Id: dialogedition.js 19013 2012-11-28 04:48:47Z thailv $
 -------------------------------------------------------------------------*/
define([
    'jquery',
    'jquery.ui'],

function ($) {
    var lang;

    function JSNMobilizeDialogEdition(params) {
        this.params = params;
        lang = params.language;
    }
    JSNMobilizeDialogEdition.createDialogLimitation = function (_this, message) {
        $("#dialog-limitation").remove();
        $($(_this)).after(
        $("<div/>", {
            "id": "dialog-limitation"
        }).append(
        $("<div/>", {
            "class": "ui-dialog-content-inner jsn-bootstrap"
        }).append(
        $("<p/>").append(message)).append(
        $("<div/>", {
            "class": "form-actions"
        }).append(
        $("<button/>", {
            "class": "btn",
            "id": "btn-upgade-edition",
            "text": lang["JSN_MOBILIZE_UPGRADE_EDITION"]
        }).click(function () {
            document.location.href = "index.php?option=com_mobilize&view=upgrade";
        })))));
        $("#dialog-limitation").dialog({
            height: 300,
            width: 500,
            title: lang['JSN_MOBILIZE_UPGRADE_EDITION_TITLE'],
            draggable: false,
            resizable: false,
            autoOpen: true,
            modal: true,
            buttons: {
                Close: function () {
                    $(this).dialog("close");
                }
            }
        });
    }

    return JSNMobilizeDialogEdition;
});