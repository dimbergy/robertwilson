<?php
/**
 * @version     $Id$
 * @package     JSN_Mobilize
 * @subpackage  SystemPlugin
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
// no direct access
defined('_JEXEC') or die;

$app 		= JFactory::getApplication();
$template 	= $app->getTemplate();


$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

// Create a shortcut for params.
$params = &$this->item->params;
?>
<?php if (empty($this->items)) : ?>


<p> <?php echo JText::_('COM_CONTACT_NO_CONTACTS'); ?> </p>
<?php else : ?>
<?php if (JSNMobilizeTemplateHelper::isJoomla3()): ?>
<?php JHtml::_('behavior.framework'); ?>
<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset class="filters">
	<legend class="hidelabeltxt"><?php echo JText::_('JGLOBAL_FILTER_LABEL'); ?></legend>
	<?php if ($this->params->get('show_pagination_limit')) : ?>
		<div class="display-limit">
			<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
	<?php endif; ?>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	</fieldset>

	<table class="category">
		<?php if ($this->params->get('show_headings')) : ?>
		<thead><tr>
			<th class="sectiontableheader item-num">
				<?php echo JText::_('JGLOBAL_NUM'); ?>
			</th>
			<th class="sectiontableheader item-title">
				<?php echo JHtml::_('grid.sort', 'COM_CONTACT_CONTACT_EMAIL_NAME_LABEL', 'a.name', $listDirn, $listOrder); ?>
			</th>
			<?php if ($this->params->get('show_position_headings')) : ?>
			<th class="sectiontableheader item-position">
				<?php echo JHtml::_('grid.sort', 'COM_CONTACT_POSITION', 'a.con_position', $listDirn, $listOrder); ?>
			</th>
			<?php endif; ?>
			<?php if ($this->params->get('show_email_headings')) : ?>
			<th class="sectiontableheader item-email">
				<?php echo JText::_('JGLOBAL_EMAIL'); ?>
			</th>
			<?php endif; ?>
			<?php if ($this->params->get('show_telephone_headings')) : ?>
			<th class="sectiontableheader item-phone">
				<?php echo JText::_('COM_CONTACT_TELEPHONE'); ?>
			</th>
			<?php endif; ?>

			<?php if ($this->params->get('show_mobile_headings')) : ?>
			<th class="sectiontableheader item-phone">
				<?php echo JText::_('COM_CONTACT_MOBILE'); ?>
			</th>
			<?php endif; ?>

			<?php if ($this->params->get('show_fax_headings')) : ?>
			<th class="sectiontableheader item-phone">
				<?php echo JText::_('COM_CONTACT_FAX'); ?>
			</th>
			<?php endif; ?>

			<?php if ($this->params->get('show_suburb_headings')) : ?>
			<th class="sectiontableheader item-suburb">
				<?php echo JHtml::_('grid.sort', 'COM_CONTACT_SUBURB', 'a.suburb', $listDirn, $listOrder); ?>
			</th>
			<?php endif; ?>

			<?php if ($this->params->get('show_state_headings')) : ?>
			<th class="sectiontableheader item-state">
				<?php echo JHtml::_('grid.sort', 'COM_CONTACT_STATE', 'a.state', $listDirn, $listOrder); ?>
			</th>
			<?php endif; ?>

			<?php if ($this->params->get('show_country_headings')) : ?>
			<th class="sectiontableheader item-state">
				<?php echo JHtml::_('grid.sort', 'COM_CONTACT_COUNTRY', 'a.country', $listDirn, $listOrder); ?>
			</th>
			<?php endif; ?>

			</tr>
		</thead>
		<?php endif; ?>

		<tbody>
			<?php foreach($this->items as $i => $item) : ?>
				<tr class="<?php echo ($i % 2) ? "sectiontableentry2" : "sectiontableentry1"; ?>">
					<td class="item-num">
						<?php echo $i; ?>
					</td>

					<td class="item-title">
						<?php if ($this->items[$i]->published == 0): ?>
							<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
						<?php endif; ?>
						<a href="<?php echo JRoute::_(ContactHelperRoute::getContactRoute($item->slug, $item->catid)); ?>">
							<?php echo $item->name; ?></a>
					</td>

					<?php if ($this->params->get('show_position_headings')) : ?>
						<td class="item-position">
							<?php echo $item->con_position; ?>
						</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_email_headings')) : ?>
						<td class="item-email">
							<?php echo $item->email_to; ?>
						</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_telephone_headings')) : ?>
						<td class="item-phone">
							<?php echo $item->telephone; ?>
						</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_mobile_headings')) : ?>
						<td class="item-phone">
							<?php echo $item->mobile; ?>
						</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_fax_headings')) : ?>
					<td class="item-phone">
						<?php echo $item->fax; ?>
					</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_suburb_headings')) : ?>
					<td class="item-suburb">
						<?php echo $item->suburb; ?>
					</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_state_headings')) : ?>
					<td class="item-state">
						<?php echo $item->state; ?>
					</td>
					<?php endif; ?>

					<?php if ($this->params->get('show_country_headings')) : ?>
					<td class="item-state">
						<?php echo $item->country; ?>
					</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>

		</tbody>
	</table>

</form>
<?php else :
JHtml::core(); ?>
<form action="<?php echo JFilterOutput::ampReplace(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
	<div class="jsn-infofilter">
		<?php if ($this->params->get('show_pagination_limit')) : ?>
		<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160; <?php echo $this->pagination->getLimitBox(); ?>
		<?php endif; ?>
	</div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="jsn-infotable">
		<?php if ($this->params->get('show_headings')) : ?>
		<tr class="jsn-tableheader">
			<td class="sectiontableheader jsn-table-column-order" width="10" align="center"><?php echo JText::_('JGLOBAL_NUM'); ?></td>
			<td class="sectiontableheader jsn-table-column-name"><?php echo JHtml::_('grid.sort', 'COM_CONTACT_CONTACT_EMAIL_NAME_LABEL', 'a.name', $listDirn, $listOrder); ?></td>
			<?php if ($this->params->get('show_country_headings')) : ?>
			<td class="sectiontableheader jsn-table-column-country"><?php echo JHtml::_('grid.sort', 'COM_CONTACT_COUNTRY', 'a.country', $listDirn, $listOrder); ?></td>
			<?php endif; ?>
			<?php if ($this->params->get('show_state_headings')) : ?>
			<td class="sectiontableheader jsn-table-column-state"><?php echo JHtml::_('grid.sort', 'COM_CONTACT_STATE', 'a.state', $listDirn, $listOrder); ?></td>
			<?php endif; ?>
			<?php if ($this->params->get('show_suburb_headings')) : ?>
			<td class="sectiontableheader jsn-table-column-suburb"><?php echo JHtml::_('grid.sort', 'COM_CONTACT_SUBURB', 'a.suburb', $listDirn, $listOrder); ?></td>
			<?php endif; ?>
			<?php if ($this->params->get('show_email_headings')) : ?>
			<td class="sectiontableheader jsn-table-column-email"><?php echo JText::_('JGLOBAL_EMAIL'); ?></td>
			<?php endif; ?>
			<?php if ($this->params->get('show_mobile_headings')) : ?>
			<td class="sectiontableheader jsn-table-column-mobile"><?php echo JText::_('COM_CONTACT_MOBILE'); ?></td>
			<?php endif; ?>
			<?php if ($this->params->get('show_telephone_headings')) : ?>
			<td class="sectiontableheader jsn-table-column-telephone"><?php echo JText::_('COM_CONTACT_TELEPHONE'); ?></td>
			<?php endif; ?>
			<?php if ($this->params->get('show_fax_headings')) : ?>
			<td class="sectiontableheader jsn-table-column-fax"><?php echo JText::_('COM_CONTACT_FAX'); ?></td>
			<?php endif; ?>
			<?php if ($this->params->get('show_position_headings')) : ?>
			<td class="sectiontableheader jsn-table-column-position"><?php echo JHtml::_('grid.sort',  'COM_CONTACT_POSITION', 'a.con_position', $listDirn, $listOrder); ?></td>
			<?php endif; ?>
		</tr>
		<?php endif; ?>
		<?php foreach($this->items as $i => $item) : ?>
		<?php if ($this->items[$i]->published == 0) : ?>
		<tr class="system-unpublished sectiontableentry<?php echo $i % 2 +1; ?>">
			<?php else: ?>
		<tr class="sectiontableentry<?php echo $i % 2 +1; ?>">
			<?php endif; ?>
			<td class="jsn-table-column-order" width="10" align="center"><?php echo $i+1; ?></td>
			<td class="jsn-table-column-name"><a href="<?php echo JRoute::_(ContactHelperRoute::getContactRoute($item->slug, $item->catid)); ?>"> <?php echo $item->name; ?></a></td>
			<?php if ($this->params->get('show_country_headings')) : ?>
			<td class="jsn-table-column-country"><?php echo $item->country; ?></td>
			<?php endif; ?>
			<?php if ($this->params->get('show_state_headings')) : ?>
			<td class="jsn-table-column-state"><?php echo $item->state; ?></td>
			<?php endif; ?>
			<?php if ($this->params->get('show_suburb_headings')) : ?>
			<td class="jsn-table-column-suburb"><?php echo $item->suburb; ?></td>
			<?php endif; ?>
			<?php if ($this->params->get('show_email_headings')) : ?>
			<td class="jsn-table-column-email"><?php echo $item->email_to; ?></td>
			<?php endif; ?>
			<?php if ($this->params->get('show_telephone_headings')) : ?>
			<td class="jsn-table-column-telephone"><?php echo $item->telephone; ?></td>
			<?php endif; ?>
			<?php if ($this->params->get('show_mobile_headings')) : ?>
			<td class="jsn-table-column-mobile"><?php echo $item->mobile; ?></td>
			<?php endif; ?>
			<?php if ($this->params->get('show_fax_headings')) : ?>
			<td class="jsn-table-column-fax"><?php echo $item->fax; ?></td>
			<?php endif; ?>
			<?php if ($this->params->get('show_position_headings')) : ?>
			<td class="jsn-table-column-position"><?php echo $item->con_position; ?></td>
			<?php endif; ?>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php if ($this->params->get('show_pagination')) : ?>
	<div class="jsn-pagination-container"> <?php echo $this->pagination->getPagesLinks(); ?>
		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
		<p class="jsn-pageinfo"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	<div>
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	</div>
</form>
<?php endif; ?>
<?php endif; ?>
<div class="item-separator"></div>
