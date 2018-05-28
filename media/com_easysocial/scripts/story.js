EasySocial.module("story", function($){

var module = this;

// This speeds up story initialization during development mode.
// Do not add this to the manifest file.
EasySocial.require()
	.language(
		"COM_EASYSOCIAL_WITH_FRIENDS",
		"COM_EASYSOCIAL_AND_ONE_OTHER",
		"COM_EASYSOCIAL_AND_MANY_OTHERS",
		"COM_EASYSOCIAL_LOCATION_PERMISSION_ERROR"
	)
	.view(
		"apps/user/links/story/attachment.item",
		"apps/user/locations/suggestion",
		"site/albums/upload.item",
		"site/friends/suggest.item",
		"site/friends/suggest.hint.search",
		"site/friends/suggest.hint.empty"
	)
	.done();

EasySocial.require()
	.library("mentions")
	.script('site/stream/item')
	.language(
		"COM_EASYSOCIAL_STORY_SUBMIT_ERROR",
		"COM_EASYSOCIAL_STORY_CONTENT_EMPTY",
		"COM_EASYSOCIAL_STORY_NOT_ON_STREAM_FILTER"
	)
	.done(function(){

		EasySocial.Controller("Story",
		{
			defaultOptions: {

				view: {
					suggestItem: "site/friends/suggest.item"
				},

				plugin: {

				},

				attachment: {
					limit: 1,
					lifo: true
				},

				"{header}": "[data-story-header]",
				"{body}"  : "[data-story-body]",
				"{footer}": "[data-story-footer]",

				"{form}"        : "[data-story-form]",
				"{textbox}"     : "[data-story-textbox]",
				"{textField}"   : "[data-story-textField]",
				"{target}"      : "[data-story-target]",
				"{submitButton}": "[data-story-submit]",
				"{resetButton}" : "[data-story-reset]",
				"{privacyButton}": "[data-story-privacy]",

				"{panelContents}" : "[data-story-panel-contents]",
				"{panelContent}"  : "[data-story-panel-content]",
				"{panelButton}"   : "[data-story-panel-button]",

				"{attachmentContainer}"     : "[data-story-attachment-container]",
				"{attachmentIcon}"          : "[data-story-attachment-icon]",
				"{attachmentButtons}"       : "[data-story-attachment-buttons]",
				"{attachmentButton}"        : "[data-story-attachment-button]",
				"{attachmentItems}"         : "[data-story-attachment-items]",
				"{attachmentItem}"          : "[data-story-attachment-item]",
				"{attachmentContent}"       : "[data-story-attachment-content]",
				"{attachmentToolbar}"       : "[data-story-attachment-toolbar]",
				"{attachmentDragHandle}"    : "[data-story-attachment-drag-handle]",
				"{attachmentRemoveButton}"  : "[data-story-attachment-remove-button]",
				"{attachmentClearButton}"   : "[data-story-attachment-clear-button]",
				"{attachmentPanelIcon}"     : "[data-story-attachment-panel-icon]",
				"{attachmentPanelCaption}"  : "[data-story-attachment-panel-caption]",

				//stream listing
				"{streamContainer}"	 		: "[data-streams]",
				"{streamItem}"	 			: "[data-streamItem]",
			},

			hostname: "story"
		},
		function(self, opts, base){ return {

			init: function() {

				// Put this here until all components are on 3.1.7
				base = self.element;
				opts = self.options;

				// Temporary for development purpose
				window.___story = self;

				// Find out what's my story id
				self.id = self.element.data("story");

				// Create plugin repository
				$.each(self.options.plugin, function(pluginName, pluginOptions) {

					var plugin = self.plugins[pluginName] = pluginOptions;

					// Pre-count the number of available attachment type
					if (plugin.type=="attachment") {
						self.attachments.max++;
					}

					// Add selector property
					plugin.selector = self.getPluginSelector(pluginName);
				});

				self.setMentionsLayout();

				// Resolve story instance
				$.module("story-" + self.id).resolve(self);
			},

			"{self} click": function(element, event) {

				if ($(event.target).parents().andSelf().filter(self.resetButton()[0]).length > 0) return;

				self.expand();
			},

			"{textField} touchstart": function() {
				self.expand();
			},

			"{textField} keydown": function(textField, event) {

				self.expand();
			},

			"{textField} click": function() {

				self.expand();
			},

			"{textField} mousedown": function(textField, event) {

				self.expand();
			},

			expand: $.debounce(function() {

				if (base.hasClass("active")) {
					self.submitButton().removeAttr("data-disabled");
					return;
				}

				// The CSS transition in this class expands the textarea
				base.addClass("active");

				setTimeout(function(){

					base.addTransitionClass("no-transition")
						.addClass("expanded");

					// Executes only once
					self.setMentionsLayout();

					self.submitButton().removeAttr("data-disabled");

					self.textField().focus();

				}, 500);
			}, 1),

			collapse: function() {

				self.submitButton().attr("data-disabled", "true");

				base.addTransitionClass("no-transition")
					.removeClass("expanded");

				setTimeout(function(){
					base.removeClass("active");
				}, 0);
			},

			setMentionsLayout: function() {

				var textbox = self.textbox(),
					mentions = textbox.controller("mentions");

				if (mentions) {
					mentions.cloneLayout();
					return;
				}

				var header = self.header();

				textbox
					.mentions({
						triggers: {
						    "@": {
								type: "entity",
								wrap: false,
								stop: "",
								allowSpace: true,
								finalize: true,
								query: {
									emptyHint: true,
									loadingHint: true,
									searchHint: true,
									data: function(keyword) {

										var task = $.Deferred();

										EasySocial.ajax("site/controllers/friends/suggest", {search: keyword})
											.done(function(items){

												if (!$.isArray(items)) task.reject();

												var items = $.map(items, function(item){
													item.title = item.screenName;
													item.type = "user";
													item.menuHtml = self.view.suggestItem(true, {
														item: item,
														name: "uid[]"
													});
													return item;
												});

												task.resolve(items);
											})
											.fail(task.reject);

										return task;
									}
							    }
							},
							"#": {
							    type: "hashtag",
							    wrap: true,
							    stop: " #",
							    allowSpace: false
							}
						},
						plugin: {
							autocomplete: {
								id: "es-wrap",
								position: {
									my: 'left top',
									at: 'left bottom',
									of: header,
									collision: 'none'
								},
								size: {
									width: function() {
										return header.width();
									}
								},
								view: {
									searchHint: "easysocial/site/friends/suggest.hint.search",
									emptyHint: "easysocial/site/friends/suggest.hint.empty"
								}
							}
						}
					});
			},

			//
			// PLUGINS
			//

			plugins: {},

			getPluginName: function(element) {
				return $(element).data("story-plugin-name");
			},

			getPluginSelector: function(pluginName) {
				return "[data-story-plugin-name=" + pluginName + "]";
			},

			hasPlugin: function(pluginName, pluginType) {

				var plugin = self.plugins[pluginName];

				if (!plugin) return false;

				// Also check for pluginType
				if (pluginType) {
					return (plugin.type===pluginType);
				}

				return true;
			},

			buildPluginSelectors: function(selectorNames, plugin, pluginControllerType) {

				var selectors = {};

				$.each(selectorNames, function(i, selectorName){

					var selector = self[selectorName].selector + plugin.selector;

					if (pluginControllerType=="function") {
						selectors[selectorName] = function() {
							return self.find(selector);
						};
					} else {
						selectors["{"+selectorName+"}"] = selector;
					}
				});

				return selectors;
			},

			"{self} addPlugin": function(element, event, pluginName, pluginController, pluginOptions, pluginControllerType) {

				// Prevent unregistered plugin from extending onto story
				if (!self.hasPlugin(pluginName)) return;

				var plugin = self.plugins[pluginName],
					extendedOptions = {};

				// See plugin type and build the necessary options for them
				switch (plugin.type) {

					case "attachment":
						var attachmentSelectors = [
							"attachmentIcon",
							"attachmentButton",
							"attachmentItem",
							"attachmentContent",
							"attachmentToolbar",
							"attachmentDragHandle",
							"attachmentRemoveButton"
						];
						extendedOptions = self.buildPluginSelectors(attachmentSelectors, plugin, pluginControllerType);
						break;

					case "panel":
						var panelSelectors = [
							"panelButton",
							"panelContent"
						];
						extendedOptions = self.buildPluginSelectors(panelSelectors, plugin, pluginControllerType);
						break;
				}

				$.extend(pluginOptions, extendedOptions);
			},

			"{self} registerPlugin": function(element, event, pluginName, pluginInstance) {

				// Prevent unregistered plugin from extending onto story
				if (!self.hasPlugin(pluginName)) return;

				var plugin = self.plugins[pluginName];

				plugin.instance = pluginInstance;
			},

			//
			// PANELS
			//

			panels: {},

			currentPanel: null,

			getPanel: function(pluginName) {

				// If plugin is not a panel, stop.
				if (!self.hasPlugin(pluginName, "panel")) return;

				var plugin = self.plugins[pluginName];

                       // Return existing panel entry if it has been created,
				return self.panels[plugin.name] ||

					   // or create panel entry and return it.
					   (self.panels[plugin.name] = {
					       plugin: plugin,
					       button: self.panelButton(plugin.selector),
					       content: self.panelContent(plugin.selector)
					   });
			},

			togglePanel: function(pluginName) {

				// Get current panel
				var currentPanel = self.currentPanel;

				// If current panel exists
				if (currentPanel) {
					self.deactivatePanel(currentPanel);
				}

				// Do not reactivate panel that
				// was deactivated just now.
				if (currentPanel===pluginName) return;

				self.activatePanel(pluginName);
			},

			activatePanel: function(pluginName) {

				// Get panel
				var panel = self.getPanel(pluginName);

				// If panel does not exist, stop.
				if (!panel) return;

				// Deactivate current panel
				self.deactivatePanel(self.currentPanel);

				var panelContents = self.panelContents();

				// Activate panel container
				panelContents.addClass("active");

				// Activate panel
				panel.button.addClass("active");
				panel.content
					.appendTo(panelContents)
					.addClass("active");

				// Invoke plugin's activate method if exists
				self.invokePlugin(pluginName, "activatePanel", [panel]);

				// Trigger panel activate event
				self.trigger("activatePanel", [pluginName]);
			},

			deactivatePanel: function(pluginName) {

				// Get panel
				var panel = self.getPanel(pluginName);

				// If panel does not exist, stop.
				if (!panel) return;

				// Deactivate panel
				panel.button.removeClass("active");
				panel.content.removeClass("active");

				// Deactivate panel container
				self.panelContents().removeClass("active");

				// Invoke plugin's deactivate method if exists
				self.invokePlugin(pluginName, "deactivatePanel", [panel]);

				// Trigger panel deactivate event
				self.trigger("deactivatePanel", [pluginName]);
			},

			addPanelCaption: function(pluginName, panelCaption) {

				// Get panel
				var panel = self.getPanel(pluginName);

				// If panel does not exist, stop.
				if (!panel) return;

				panel.button
					.addClass("has-data")
					.find(".with-data").html(panelCaption);
			},

			removePanelCaption: function(pluginName) {

				// Get panel
				var panel = self.getPanel(pluginName);

				// If panel does not exist, stop.
				if (!panel) return;

				panel.button
					.removeClass("has-data")
					.find(".with-data").empty();
			},

			"{self} activatePanel": function(story, event, pluginName) {

				self.currentPanel = pluginName;
			},

			"{self} deactivatePanel": function(story, event, pluginName) {

				// If the deactivated panel is the current panel,
				if (self.currentPanel===pluginName) {

					// set current panel to null.
					self.currentPanel = null;
				}
			},

			"{panelButton} click": function(panelButton, event) {

				var pluginName = self.getPluginName(panelButton);

				self.togglePanel(pluginName);
			},

			//
			// ATTACHMENTS
			//

			attachments: {
				length: 0, // Pseudo array
				max: 0
			},

			currentAttachment: null,

			getAttachment: function(pluginName) {

				// If plugin is not an attachment, stop.
				if (!self.hasPlugin(pluginName, "attachment")) return;

				return self.attachments[pluginName];
			},

			addAttachment: function(pluginName) {

				// Do not allow non-attachment plugin to add attachment
				if (!self.hasPlugin(pluginName, "attachment")) return false;

				// Get plugin
				var plugin = self.plugins[pluginName];

				// Get master attachment list
				var attachments = self.attachments,
					attachment = attachments[pluginName];

				// Return existing attachment if exists
				if (attachment) return attachment;

				// Create attachment
				var createAttachment = function(){

					var attachment = {
							plugin: plugin,
							button: self.attachmentButton(plugin.selector),
							icon  : self.attachmentIcon(plugin.selector)
						};

						attachment.item =
							self.attachmentItem(plugin.selector)
								.prependTo(self.attachmentItems());

					// Add to master attachments
					attachments[plugin.name] = attachment;
					attachments.length++;

					// Invoke plugin's add method if exists
					self.invokePlugin(pluginName, "addAttachment", [attachment]);

					// Trigger addAttachment event
					self.trigger("addAttachment", [attachment]);
				}

				// Check attachment limit
				var options = self.options.attachment,
					lifo = options.lifo,
					limitExceeded = (options.limit > 0 && attachments.length >= options.limit);

				// If exceeded attachment limit
				if (limitExceeded) {
					// but we allow new attachment to replace oldest attachment
					if (lifo) {

						// Create new attachment
						createAttachment();

						// then remove old attachment
						var oldestAttachmentName = self.getPluginName(self.attachmentItem(":last"));
						self.removeAttachment(oldestAttachmentName);

						return attachment;
					} else {
						// else prevent adding of new attachment.
						return false;
					}
				}

				// Create new attachment
				createAttachment();

				return attachment;
			},

			removeAttachment: function(pluginName) {

				var attachment = self.getAttachment(pluginName);

				// If attachment does not exist, skip.
				if (!attachment) return;

				// Invoke plugin's remove method if exists
				self.invokePlugin(pluginName, "removeAttachment", [attachment]);

				// Trigger removeAttachment event
				self.trigger("removeAttachment", [attachment]);

				// Remove attachment item
				attachment.icon.removeClass("active");
				attachment.button.removeClass("active");

				setTimeout(function(){
					attachment.item.removeClass("active");
				}, 0);

				// Remove from master attachments
				delete self.attachments[pluginName];
				self.attachments.length--;

				// Invoke plugin's remove method if exists
				self.invokePlugin(pluginName, "destroyAttachment", [attachment]);

				// Trigger removeAttachment event
				self.trigger("destroyAttachment", [attachment]);

				return attachment;
			},

			clearAttachment: function() {

				var removedPluginNames =
					$.map(self.attachments, function(plugin, pluginName) {

						// Ignore pseudo-array properties
						if (/length|max/.test(pluginName)) return;

						// Remove attachment
						self.removeAttachment(pluginName);

						// Add it to the list of removed plugins
						return pluginName;
					});

				// Trigger removeAttachment event
				self.trigger("clearAttachment", [removedPluginNames]);
			},

			activateAttachment: function(pluginName) {

				var attachment = self.getAttachment(pluginName);

				// If attachment does not exist, skip.
				if (!attachment) return;

				// Deactivate current attachment
				self.deactivateAttachment(self.currentAttachment);

				// Activate attachment
				attachment.icon.addClass("active");
				attachment.button.addClass("active");

				setTimeout(function(){
					attachment.item.addClass("active");
				}, 0);

				// Invoke plugin's activate method if exists
				self.invokePlugin(pluginName, "activateAttachment", [attachment]);

				// Trigger activateAttachment event
				self.trigger("activateAttachment", [attachment]);
			},

			deactivateAttachment: function(pluginName) {

				var attachment = self.attachments[pluginName];

				// If attachment does not exist, skip.
				if (!attachment) return;

				// Remove active class from attachment item and button
				// attachment.item.removeClass("active");
				attachment.icon.removeClass("active");
				attachment.button.removeClass("active");

				setTimeout(function(){
					attachment.item.removeClass("active");
				}, 0);

				// Invoke plugin's deactivate method is exists
				self.invokePlugin(pluginName, "deactivateAttachment", [attachment]);

				// Trigger deactivateAttachment event
				self.trigger("deactivateAttachment", [attachment]);
			},

			"{self} activateAttachment": function(story, event, attachment) {

				var pluginName = attachment.plugin.name;

				self.currentAttachment = pluginName;

				self.body().addClass("active");

				self.element.addClass("attaching-" + pluginName);

				// Update attachment panel content
				var attachmentPanel = self.getPanel("attachments"),
					attachmentButton = attachment.button,
					attachmentIcon = attachmentButton.find("[data-story-attachment-icon]").attr("class")
					attachmentCaption = attachment.button.find("[data-story-attachment-caption]").text();

				// Update icon and text
				attachmentPanel.button.addClass("has-data");
				self.attachmentPanelIcon().attr("class", attachmentIcon)
				self.attachmentPanelCaption().text(attachmentCaption);
			},

			"{self} deactivateAttachment": function(story, event, attachment) {

				var pluginName = attachment.plugin.name;

				// If the deactivated panel is the current panel,
				if (self.currentAttachment===pluginName) {

					// set current panel to null.
					self.currentAttachment = null;
				}

				self.body().removeClass("active");

				self.element.removeClass("attaching-" + pluginName);
			},

			"{attachmentButton} click": function(attachmentButton, event) {

				var pluginName = self.getPluginName(attachmentButton);

				// Deactivate attachment panel
				if (self.currentPanel=="attachments") {
					self.deactivatePanel("attachments");
				}

				// If the attachment hasn't been created
				if (!self.getAttachment(pluginName)) {

					// Create the attachment
					var attachment = self.addAttachment(pluginName);

					// If unable to create attachment, stop.
					if (!attachment) return;
				}

				// Activate attachment
				self.activateAttachment(pluginName);
			},

			"{attachmentClearButton} click": function() {

				self.clearAttachment();

				self.textField().focus();
			},

			"{self} addAttachment": function(story, event, attachment) {

				var pluginName = attachment.plugin.name;

				self.activateAttachment(pluginName);
			},

			"{self} removeAttachment": function(el, event, attachment) {

				var pluginName = attachment.plugin.name;

				self.element.removeClass("attaching-" + pluginName);
			},

			"{self} destroyAttachment": function(el, event, attachment) {

				if (self.attachments.length < 1) {

					self.body().removeClass("active");

					// Update attachment panel content
					var attachmentPanel = self.getPanel("attachments"),
						attachmentPanelIcon = self.attachmentPanelIcon(),
						attachmentPanelCaption = self.attachmentPanelCaption();

					// Update icon and text
					attachmentPanel.button.removeClass("has-data");
					attachmentPanelIcon.attr("class", attachmentPanelIcon.data("defaultIcon"));
					attachmentPanelCaption.text("");
				}
			},

			"{self} clearAttachment": function() {

				// Deactivate attachment panel
				if (self.currentPanel=="attachments") {
					self.deactivatePanel("attachments");
				}
			},

			//
			// ATTACHMENT TOOLBAR
			//

			"{attachmentRemoveButton} click": function(attachmentRemoveButton, event) {

				var pluginName = self.getPluginName(attachmentRemoveButton);

				self.removeAttachment(pluginName);
			},


			//
			// SAVING
			//
			saving: false,

			save: function() {

				if (self.saving) return;

				self.saving = true;

				// Create save object
				var save = $.Deferred();

					save.data = {};

					save.tasks = [];

					save.addData = function(plugin, props) {

						var pluginName = plugin.options.name,
							pluginType = plugin.options.type;

						if (pluginType=="attachment") {

							// Stop attachment plugins other than the current
							// one from adding stuff to the save data.
							if (pluginName!==self.currentAttachment) return;

							// Don't decorate the attachment property we know
							// there are proper attachment data coming in
							// from the attachment plugin.
							save.data.attachment = self.currentAttachment;
						}

						if ($.isPlainObject(props)) {
							$.each(props, function(key, val){
								save.data[pluginName + "_" + key] = val;
							});
						} else {
							save.data[pluginName] = props;
						}
					};

					save.addTask = function(name) {
						var task = $.Deferred();
						task.name = name;
						task.save = save;
						save.tasks.push(task);
						return task;
					};

					save.process = function() {

						if (save.state()==="pending") {
							$.when.apply($, save.tasks)
								.done(function(){
									// If content & attachment is empty, reject.
									if (!$.trim(save.data.content) && !save.data.attachment) {
										save.reject($.language("COM_EASYSOCIAL_STORY_CONTENT_EMPTY"), "warning");
										return;
									}
									save.resolve();
								})
								.fail(save.reject);
						}

						return save;
					};

				// Trigger the save event
				self.trigger("save", [save]);

				self.element.addClass("saving");

				save.process()
					.done(function(){

						var mentions = self.textbox().mentions("controller").toArray(),
							hashtags = self.element.data("storyHashtags"),
							hashtags = (hashtags) ? hashtags.split(",") : [],
							nohashtags = false;

						if (hashtags.length > 0) {

							var tags =
								$.map(mentions, function(mention){
									if (mention.type==="hashtag" && $.inArray(mention.value, hashtags) > -1) {
										return mention;
									}
								});

							nohashtags = tags.length < 1;
						}

						// then the ajax call to save story.
						EasySocial.ajax("site/controllers/story/create", save.data)
							.done(function(html, id){

								if (nohashtags) {
									html = self.setMessage($.language("COM_EASYSOCIAL_STORY_NOT_ON_STREAM_FILTER"));
								}

								self.trigger("create", [html, id]);
								self.clear();
							})
							.fail(function(message){
								self.trigger("fail", arguments);
								if (!message) return;
								self.setMessage(message.message, message.type);
							})
							.always(function(){
								self.element.removeClass("saving");
								self.saving = false;
							});
					})
					.fail(function(message, messageType){

						if (!message) {
							message = $.language("COM_EASYSOCIAL_STORY_SUBMIT_ERROR");
							messageType = "error";
						}

						self.setMessage(message, messageType);
						self.element.removeClass("saving");
						self.saving = false;
					});
			},

			clear: function() {

				self.textField().val('');

				self.trigger("clear");

				self.clearMessage();

				self.clearAttachment();

				self.deactivatePanel(self.currentPanel);

				var mentions = self.textbox().mentions("controller");

				mentions.reset();

				setTimeout(function(){

					mentions.cloneLayout();

				}, 500);

				// Focus textfield
				self.textField().focus();
			},

			"{self} save": function(element, event, save) {

				var content = self.textField().val(),
					data = save.data;

				data.content = content;
				data.target  = self.target().val();
				data.privacy = self.find("[data-privacy-hidden]").val();
				data.privacyCustom = self.find("[data-privacy-custom-hidden]").val();
				data.mentions = self.textbox().mentions("controller").toArray(true);
			},

			"{submitButton} click": function(submitButton, event) {

				if (submitButton.attr("data-disabled")) {
					self.expand();
					return;
				}

				self.save();
			},

			"{resetButton} click": function() {

				self.clear();

				// If there are default values in the textarea, don't collapse
				if (self.textField().val()!=="") return;

				self.collapse();
			},

			//
			// Privacy
			//
			"{privacyButton} click": function(el) {

				setTimeout(function(){
					var isActive = el.find("[data-es-privacy-container]").hasClass("active");
					self.footer().toggleClass("allow-overflow", isActive);
				}, 1);
			}

		}});

		module.resolve();
	});

});
