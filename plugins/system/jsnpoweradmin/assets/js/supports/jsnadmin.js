/*------------------------------------------------------------------------
 # JSN PowerAdmin
 # ------------------------------------------------------------------------
 # author    JoomlaShine.com Team
 # copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 # Websites: http://www.joomlashine.com
 # Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
 # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 # @version $Id
 -------------------------------------------------------------------------*/

var JSNAdminBarUIHelper = new Class
({
	getMenuPosition: function () {
		return jsnpa_m('module-menu');
	},

	getStatusPosition: function () {
		return jsnpa_m('jsn-body-wrapper').getElement('#module-status');
	},

	getComponentMenu: function (menubar) {
		var menu = menubar.getElement('#jsn-adminbar-menu'),
			poweradminMenu = menu.getElement('a[href="index.php?option=com_poweradmin"]');

		if (poweradminMenu != null) {
			return poweradminMenu
				.getParent()
				.getParent()
				.getChildren();
		}
	},

	getExtensionMenu: function (menubar) {
		var menu = menubar.getElement('#jsn-adminbar-menu'),
			templateMenu = menu.getElement('a[href="index.php?option=com_templates"]') || menu.getElement('a[href="index.php?option=com_installer"]');

		if (templateMenu != null) {
			return templateMenu
				.getParent()
				.getParent()
				.getChildren();
		}
	},

	getMenuSeparator: function () {
		return new Element('li', { 'class': 'divider' }).adopt(new Element('span'));
	},

	createMenuItem: function (title, url, target, className) {
		return new Element('li').adopt(
			new Element('a', { 'class': className, 'href': url, 'target': target, 'text': title })
		);
	},

	createMenuContainer: function () {
		return new Element('ul');
	},

	formatParentMenu: function (menu) {
		menu.getElement('a').addClass('node');
	},
	
	afterCreateUiHook: function (obj)
	{
		jsnpa_mm('#module-menu>.logo').dispose();
		jsnpa_mm('#jsn-body-wrapper #module-status').dispose();
		jsnpa_mm('#jsn-body-wrapper #jsn-nav').dispose();
	},
});