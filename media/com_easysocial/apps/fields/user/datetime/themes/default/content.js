<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
EasySocial.module('field.datetime', function($) {
	var module = this;

	EasySocial.require()
	.library( 'ui/datepicker' )
	.done(function($){

		EasySocial.Controller(
			'Field.Datetime',
			{
				defaultOptions:
				{
					required	: false,
					calendar	: false,

					format		: null,

					yearfrom	: 1930,

					yearto		: 2013,

					date		: null,

					'{field}'	: '[data-field-datetime]',

					'{day}'		: '[data-field-datetime-day]',
					'{month}'	: '[data-field-datetime-month]',
					'{year}'	: '[data-field-datetime-year]',

					'{date}'	: '[data-field-datetime-select]',

					'{dateValue}'	: '[data-field-datetime-value]',

					'{dateSelected}': '[data-field-datetime-selected]'
				}
			},
			function( self )
			{
				return {
					init : function() {
						self.options.calendar = !!self.field().data('calendar');

						var format = self.field().data('format');

						switch(format) {
							case 2:
								self.options.format = 'mm/dd/yy';
								break;

							case 3:
								self.options.format = 'yy/dd/mm';
								break;

							case 4:
								self.options.format = 'yy/mm/dd';
								break;

							case 1:
							default:
								self.options.format = 'dd/mm/yy';
								break;
						}

						self.options.yearfrom = self.field().data('yearfrom') || 1930;
						self.options.yearto = self.field().data('yearto') || new Date().getFullYear();

						self.options.date = self.dateValue().val();

						if(self.options.calendar) {
							self.date().datepicker({
								changeMonth: true,
								changeYear: true,
								yearRange: self.options.yearfrom + ':' + self.options.yearto,
								dateFormat: self.options.format,
								onSelect: function(date) {
									self.dateValue().val(date);

									self.dateSelected().text(date).show();
								}
							});

							self.date().datepicker('setDate', self.options.date);
						}
					},

					'{dateSelected} click': function(el, ev) {
						var date = el.text();

						self.date().datepicker('setDate', date);
					},

					'{date} blur': function() {
						self.validateCalendar();
					},

					'{month} change': function() {
						self.setMaxDay();
					},

					'{year} change': function() {
						self.setMaxDay();
					},

					setMaxDay: function() {
						var currentDay = self.day().val(),
							currentMonth = self.month().val(),
							currentYear = self.year().val();

						var days = 31;

						if(!$.isEmpty(currentMonth) && !$.isEmpty(currentYear)) {
							days = new Date(self.year().val(), self.month().val(), 0).getDate();
						}

						self.day().html('');

						if(days > 0) {
							for(i = 1; i <= days; i++) {
								self.day().append($('<option value="' + i + '">' + i + '</option>'));
							}
						}

						self.day().val(currentDay);
					},

					validateInput : function() {
						self.clearError();

						var day 	= self.day().val(),
							month 	= self.month().val(),
							year 	= self.year().val();

						if(self.options.required) {
							if($.isEmpty(day)) {
								self.raiseError('<?php echo JText::_( 'PLG_FIELDS_DATETIME_VALIDATION_PLEASE_ENTER_DAY' , true );?>');
								return false;
							}

							if($.isEmpty(month)) {
								self.raiseError('<?php echo JText::_( 'PLG_FIELDS_DATETIME_VALIDATION_PLEASE_ENTER_MONTH' , true );?>');
								return false;
							}

							if($.isEmpty(year)) {
								self.raiseError('<?php echo JText::_( 'PLG_FIELDS_DATETIME_VALIDATION_PLEASE_ENTER_YEAR' , true );?>');
								return false;
							}
						}

						if(!$.isEmpty(year) && (year < self.options.yearfrom || year > self.options.yearto)) {
							self.raiseError('<?php echo JText::_( 'PLG_FIELDS_DATETIME_VALIDATION_YEAR_OUT_OF_RANGE' , true );?>');
							return false;
						}

						if((!$.isEmpty(day) && !(day > 0)) || (!$.isEmpty(month) && !(month > 0)) || (!$.isEmpty(year) && !(year > 0))) {
							self.raiseError('<?php echo JText::_( 'PLG_FIELDS_DATETIME_VALIDATION_INVALID_DATE_FORMAT' , true ); ?>');
							return false;
						}

						return true;
					},

					validateCalendar: function() {
						self.clearError();

						if(self.options.required && $.isEmpty(self.date().val())) {
							self.raiseError('<?php echo JText::_( 'PLG_FIELDS_DATETIME_VALIDATION_PLEASE_SELECT_DATETIME' , true );?>');
							return false;
						}

						return true;
					},

					raiseError: function(msg) {
						self.trigger('error', [msg]);
					},

					clearError: function() {
						self.trigger('clear');
					},

					"{self} onSubmit" : function(el, event, register) {
						if(self.options.calendar) {
							register.push(self.validateCalendar());
							return;
						}

						register.push(self.validateInput());
						return;
					}
				}
			});

		module.resolve();
	});

});
