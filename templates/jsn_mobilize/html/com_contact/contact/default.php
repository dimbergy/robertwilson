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
defined('_JEXEC') or die;

// Load template framework


$cparams = JComponentHelper::getParams ('com_media');

$app 		= JFactory::getApplication();
$template 	= $app->getTemplate();

?>
<?php if (JSNMobilizeTemplateHelper::isJoomla3()):
jimport('joomla.html.html.bootstrap');
?>
<div class="contact<?php echo $this->pageclass_sfx?>">
        <?php if ($this->params->get('show_page_heading')) : ?>
                <h1>
                        <?php echo $this->escape($this->params->get('page_heading')); ?>
                </h1>
        <?php endif; ?>
	<?php if ($this->contact->name && $this->params->get('show_name')) : ?>
		<div class="page-header">
			<h2>
				<?php if ($this->item->published == 0): ?>
					<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
				<?php endif; ?>
				<span class="contact-name"><?php echo $this->contact->name; ?></span>
			</h2>
		</div>
	<?php endif;  ?>
	<?php if ($this->params->get('show_contact_category') == 'show_no_link') : ?>
		<h3>
			<span class="contact-category"><?php echo $this->contact->category_title; ?></span>
		</h3>
	<?php endif; ?>
	<?php if ($this->params->get('show_contact_category') == 'show_with_link') : ?>
		<?php $contactLink = ContactHelperRoute::getCategoryRoute($this->contact->catid); ?>
		<h3>
			<span class="contact-category"><a href="<?php echo $contactLink; ?>">
				<?php echo $this->escape($this->contact->category_title); ?></a>
			</span>
		</h3>
	<?php endif; ?>
	<?php if ($this->params->get('show_contact_list') && count($this->contacts) > 1) : ?>
		<form action="#" method="get" name="selectForm" id="selectForm">
			<?php echo JText::_('COM_CONTACT_SELECT_CONTACT'); ?>
			<?php echo JHtml::_('select.genericlist', $this->contacts, 'id', 'class="inputbox" onchange="document.location.href = this.value"', 'link', 'name', $this->contact->link);?>
		</form>
	<?php endif; ?>

	<?php if ($this->params->get('presentation_style') == 'tabs'):?>
                <ul class="nav nav-tabs" id="myTab">
                        <li><a data-toggle="tab" href="#basic-details"><?php echo JText::_('COM_CONTACT_DETAILS'); ?></a></li>
                        <?php if ($this->params->get('show_email_form') && ($this->contact->email_to || $this->contact->user_id)) : ?><li><a data-toggle="tab" href="#display-form"><?php echo JText::_('COM_CONTACT_EMAIL_FORM'); ?></a></li><?php endif; ?>
                        <?php if ($this->params->get('show_links')) : ?><li><a data-toggle="tab" href="#display-links"><?php echo JText::_('COM_CONTACT_LINKS'); ?></a></li><?php endif; ?>
                        <?php if ($this->params->get('show_articles') && $this->contact->user_id && $this->contact->articles) : ?><li><a data-toggle="tab" href="#display-articles"><?php echo JText::_('JGLOBAL_ARTICLES'); ?></a></li><?php endif; ?>
                        <?php if ($this->params->get('show_profile') && $this->contact->user_id && JPluginHelper::isEnabled('user', 'profile')) : ?><li><a data-toggle="tab" href="#display-profile"><?php echo JText::_('COM_CONTACT_PROFILE'); ?></a></li><?php endif; ?>
                        <?php if ($this->contact->misc && $this->params->get('show_misc')) : ?><li><a data-toggle="tab" href="#display-misc"><?php echo JText::_('COM_CONTACT_OTHER_INFORMATION'); ?></a></li><?php endif; ?>
                </ul>
	<?php endif; ?>
 	<?php if ($this->params->get('presentation_style') == 'sliders'): ?>
                <?php echo JHtml::_('bootstrap.startAccordion', 'slide-contact', array('active' => 'basic-details')); ?>
	<?php endif; ?>
	<?php if ($this->params->get('presentation_style') == 'tabs'): ?>
                <?php echo JHtml::_('bootstrap.startPane', 'myTab', array('active' => 'basic-details')); ?>
	<?php endif; ?>
    
	<?php if ($this->params->get('presentation_style') == 'sliders'): ?>
                <?php echo JHtml::_('bootstrap.addSlide', 'slide-contact', JText::_('COM_CONTACT_DETAILS'), 'basic-details'); ?>
	<?php endif; ?>
	<?php if ($this->params->get('presentation_style') == 'tabs'): ?>
                <?php echo JHtml::_('bootstrap.addPanel', 'myTab', 'basic-details'); ?>
	<?php endif; ?>
	<?php if ($this->params->get('presentation_style') == 'plain'):?>
		<?php  echo '<h3>'. JText::_('COM_CONTACT_DETAILS').'</h3>';  ?>
	<?php endif; ?>
    
                <?php if ($this->contact->image && $this->params->get('show_image')) : ?>
                        <div class="thumbnail pull-right">
                                <?php echo JHtml::_('image', $this->contact->image, JText::_('COM_CONTACT_IMAGE_DETAILS'), array('align' => 'middle')); ?>
                        </div>
                <?php endif; ?>

                <?php if ($this->contact->con_position && $this->params->get('show_position')) : ?>
                        <dl class="contact-position dl-horizontal">
                                <dd>
                                        <?php echo $this->contact->con_position; ?>
                                </dd>
                        </dl>
                <?php endif; ?>

                <?php echo $this->loadTemplate('address'); ?>

                <?php if ($this->params->get('allow_vcard')) :	?>
                        <?php echo JText::_('COM_CONTACT_DOWNLOAD_INFORMATION_AS');?>
                        <a href="<?php echo JRoute::_('index.php?option=com_contact&amp;view=contact&amp;id='.$this->contact->id . '&amp;format=vcf'); ?>">
                            <?php echo JText::_('COM_CONTACT_VCARD');?></a>
                <?php endif; ?>
    
	<?php if ($this->params->get('presentation_style') == 'sliders'): ?>
                <?php echo JHtml::_('bootstrap.endSlide'); ?>
	<?php endif; ?>
	<?php if ($this->params->get('presentation_style') == 'tabs'): ?>
                <?php echo JHtml::_('bootstrap.endPanel'); ?>
	<?php endif; ?>
    
	<?php if ($this->params->get('show_email_form') && ($this->contact->email_to || $this->contact->user_id)) : ?>

                <?php if ($this->params->get('presentation_style') == 'sliders'): ?>
                        <?php echo JHtml::_('bootstrap.addSlide', 'slide-contact', JText::_('COM_CONTACT_EMAIL_FORM'), 'display-form'); ?>
                <?php endif; ?>
                <?php if ($this->params->get('presentation_style') == 'tabs'): ?>
                        <?php echo JHtml::_('bootstrap.addPanel', 'myTab', 'display-form'); ?>
                <?php endif; ?>
                <?php if ($this->params->get('presentation_style') == 'plain'):?>
                        <?php echo '<h3>'. JText::_('COM_CONTACT_EMAIL_FORM').'</h3>';  ?>
                <?php endif; ?>

		<?php  echo $this->loadTemplate('form');  ?>
    
                <?php if ($this->params->get('presentation_style') == 'sliders'): ?>
                        <?php echo JHtml::_('bootstrap.endSlide'); ?>
                <?php endif; ?>
                <?php if ($this->params->get('presentation_style') == 'tabs'): ?>
                        <?php echo JHtml::_('bootstrap.endPanel'); ?>
                <?php endif; ?>
    
        <?php endif; ?>

	<?php if ($this->params->get('show_links')) : ?>
		<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>

	<?php if ($this->params->get('show_articles') && $this->contact->user_id && $this->contact->articles) : ?>
    
                <?php if ($this->params->get('presentation_style') == 'sliders'): ?>
                        <?php echo JHtml::_('bootstrap.addSlide', 'slide-contact', JText::_('JGLOBAL_ARTICLES'), 'display-articles'); ?>
                <?php endif; ?>
                <?php if ($this->params->get('presentation_style') == 'tabs'): ?>
                        <?php echo JHtml::_('bootstrap.addPanel', 'myTab', 'display-articles'); ?>
                <?php endif; ?>
                <?php if ($this->params->get('presentation_style') == 'plain'):?>
                        <?php echo '<h3>'. JText::_('JGLOBAL_ARTICLES').'</h3>';  ?>
                <?php endif; ?>
                                            
                <?php echo $this->loadTemplate('articles'); ?>
                                            
                <?php if ($this->params->get('presentation_style') == 'sliders'): ?>
                        <?php echo JHtml::_('bootstrap.endSlide'); ?>
                <?php endif; ?>
                <?php if ($this->params->get('presentation_style') == 'tabs'): ?>
                        <?php echo JHtml::_('bootstrap.endPanel'); ?>
                <?php endif; ?>
                                            
	<?php endif; ?>
        <?php if ($this->params->get('show_profile') && $this->contact->user_id && JPluginHelper::isEnabled('user', 'profile')) : ?>
    
                <?php if ($this->params->get('presentation_style') == 'sliders'): ?>
                        <?php echo JHtml::_('bootstrap.addSlide', 'slide-contact', JText::_('COM_CONTACT_PROFILE'), 'display-profile'); ?>
                <?php endif; ?>
                <?php if ($this->params->get('presentation_style') == 'tabs'): ?>
                        <?php echo JHtml::_('bootstrap.addPanel', 'myTab', 'display-profile'); ?>
                <?php endif; ?>
                <?php if ($this->params->get('presentation_style') == 'plain'):?>
                        <?php echo '<h3>'. JText::_('COM_CONTACT_PROFILE').'</h3>';  ?>
                <?php endif; ?>
                                            
                <?php echo $this->loadTemplate('profile'); ?>
                                            
                <?php if ($this->params->get('presentation_style') == 'sliders'): ?>
                        <?php echo JHtml::_('bootstrap.endSlide'); ?>
                <?php endif; ?>
                <?php if ($this->params->get('presentation_style') == 'tabs'): ?>
                        <?php echo JHtml::_('bootstrap.endPanel'); ?>
                <?php endif; ?>

	<?php endif; ?>
	<?php if ($this->contact->misc && $this->params->get('show_misc')) : ?>
    
                <?php if ($this->params->get('presentation_style') == 'sliders'): ?>
                        <?php echo JHtml::_('bootstrap.addSlide', 'slide-contact', JText::_('COM_CONTACT_OTHER_INFORMATION'), 'display-misc'); ?>
                <?php endif; ?>
                <?php if ($this->params->get('presentation_style') == 'tabs'): ?>
                        <?php echo JHtml::_('bootstrap.addPanel', 'myTab', 'display-misc'); ?>
                <?php endif; ?>
                <?php if ($this->params->get('presentation_style') == 'plain'):?>
                        <?php echo '<h3>'. JText::_('COM_CONTACT_OTHER_INFORMATION').'</h3>';  ?>
                <?php endif; ?>
                                            
				<div class="contact-miscinfo">
					<dl class="dl-horizontal">
						<dt>
							<span class="<?php echo $this->params->get('marker_class'); ?>">
								<?php echo $this->params->get('marker_misc'); ?>
							</span>
						</dt>
						<dd>
							<span class="contact-misc">
								<?php echo $this->contact->misc; ?>
							</span>
						</dd>
					</dl>
				</div>
    
                <?php if ($this->params->get('presentation_style') == 'sliders'): ?>
                        <?php echo JHtml::_('bootstrap.endSlide'); ?>
                <?php endif; ?>
                <?php if ($this->params->get('presentation_style') == 'tabs'): ?>
                        <?php echo JHtml::_('bootstrap.endPanel'); ?>
                <?php endif; ?>
    
	<?php endif; ?>
    
        <?php if ($this->params->get('presentation_style') == 'sliders'): ?>
                <?php echo JHtml::_('bootstrap.endAccordion'); ?>
        <?php endif; ?>
        <?php if ($this->params->get('presentation_style') == 'tabs'): ?>
                <?php echo JHtml::_('bootstrap.endPane'); ?>
        <?php endif; ?>
    
</div>
<?php else : ?>
<div class="com-contact <?php echo $this->params->get('pageclass_sfx') ?>">
	<div class="standard-contact">
		<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<h2 class="componentheading">
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h2>
		<?php endif; ?>

		<?php if ($this->contact->name && $this->params->get('show_name')) : ?>
			<h2 class="contentheading"><?php echo $this->contact->name; ?></h2>
		<?php endif;  ?>

		<?php if ($this->params->get('show_contact_category') == 'show_no_link') : ?>
			<h3>
				<span class="contact-category"><?php echo $this->contact->category_title; ?></span>
			</h3>
		<?php endif; ?>

		<?php if ($this->params->get('show_contact_category') == 'show_with_link') : ?>
			<?php $contactLink = ContactHelperRoute::getCategoryRoute($this->contact->catid);?>
			<h3>
				<span class="contact-category"><a href="<?php echo $contactLink; ?>">
					<?php echo $this->escape($this->contact->category_title); ?></a>
				</span>
			</h3>
		<?php endif; ?>

		<?php if ($this->params->get('show_contact_list') && count($this->contacts) > 1) : ?>
			<form action="#" method="get" name="selectForm" id="selectForm">
				<?php echo JText::_('COM_CONTACT_SELECT_CONTACT'); ?>:
				<?php echo JHtml::_('select.genericlist',  $this->contacts, 'id', 'class="inputbox" onchange="document.location.href = this.value"', 'link', 'name', $this->contact->link);?>
			</form>
		<?php endif; ?>

		<?php  if ($this->params->get('presentation_style')!='plain') : ?>
			<?php  echo  JHtml::_($this->params->get('presentation_style').'.start', 'contact-slider'); ?>	
			<?php  echo JHtml::_($this->params->get('presentation_style').'.panel',JText::_('COM_CONTACT_DETAILS'), 'basic-details'); ?>
		<?php endif; ?>

		<?php if ($this->params->get('presentation_style')=='plain'):?>
			<?php  echo '<h3>'. JText::_('COM_CONTACT_DETAILS').'</h3>';  ?>
		<?php endif; ?>	

		<?php if ($this->contact->image && $this->params->get('show_image')) : ?>
			<div class="jsn-contact-image">
				<?php echo JHTML::_('image',$this->contact->image, JText::_('COM_CONTACT_IMAGE_DETAILS'), array('align' => 'middle')); ?>
			</div>
		<?php endif; ?> 	

		<?php if ($this->contact->con_position && $this->params->get('show_position')) : ?>
			<p class="contact-position"><?php echo $this->contact->con_position; ?></p>
		<?php endif; ?>

		<?php echo $this->loadTemplate('address'); ?>

		<?php if ($this->params->get('allow_vcard')) :	?>
			<p class="contact-vcard">
			<?php echo JText::_('COM_CONTACT_DOWNLOAD_INFORMATION_AS');?>
				<a href="<?php echo JURI::base(); ?>index.php?option=com_contact&amp;view=contact&amp;id=<?php echo $this->contact->id; ?>&amp;format=vcf">
					<?php echo JText::_('COM_CONTACT_VCARD');?></a>
			</p>
		<?php endif; ?>

		<?php if ($this->params->get('show_email_form') && ($this->contact->email_to || $this->contact->user_id)) : ?>

			<?php if ($this->params->get('presentation_style')!='plain'):?>
				<?php  echo JHtml::_($this->params->get('presentation_style').'.panel', JText::_('COM_CONTACT_EMAIL_FORM'), 'display-form');  ?>
			<?php endif; ?>
			<?php if ($this->params->get('presentation_style')=='plain'):?>
				<?php  echo '<h3>'. JText::_('COM_CONTACT_EMAIL_FORM').'</h3>';  ?>
			<?php endif; ?>
			<?php  echo $this->loadTemplate('form');  ?>
		<?php endif; ?>
		
		<?php if ($this->params->get('show_links')) : ?>
			<?php echo $this->loadTemplate('links'); ?>
		<?php endif; ?>

	<?php if ($this->params->get('show_articles') && $this->contact->user_id && $this->contact->articles) : ?>
		<?php if ($this->params->get('presentation_style')!='plain'):?>
			<?php echo JHtml::_($this->params->get('presentation_style').'.panel', JText::_('JGLOBAL_ARTICLES'), 'display-articles'); ?>
			<?php endif; ?>
			<?php if  ($this->params->get('presentation_style')=='plain'):?>
			<?php echo '<h3>'. JText::_('JGLOBAL_ARTICLES').'</h3>'; ?>
			<?php endif; ?>
			<?php echo $this->loadTemplate('articles'); ?>
	<?php endif; ?>

	<?php if ($this->params->get('show_profile') && $this->contact->user_id && JPluginHelper::isEnabled('user', 'profile')) : ?>
		<?php if ($this->params->get('presentation_style')!='plain'):?>
			<?php echo JHtml::_($this->params->get('presentation_style').'.panel', JText::_('COM_CONTACT_PROFILE'), 'display-profile'); ?>
		<?php endif; ?>
		<?php if ($this->params->get('presentation_style')=='plain'):?>
			<?php echo '<h3>'. JText::_('COM_CONTACT_PROFILE').'</h3>'; ?>
		<?php endif; ?>
		<?php echo $this->loadTemplate('profile'); ?>
	<?php endif; ?>

	<?php if ($this->contact->misc && $this->params->get('show_misc')) : ?>
		<?php if ($this->params->get('presentation_style')!='plain'){?>
			<?php echo JHtml::_($this->params->get('presentation_style').'.panel', JText::_('COM_CONTACT_OTHER_INFORMATION'), 'display-misc');} ?>
		<?php if ($this->params->get('presentation_style')=='plain'):?>
			<?php echo '<h3>'. JText::_('COM_CONTACT_OTHER_INFORMATION').'</h3>'; ?>
		<?php endif; ?>
			<div class="contact-miscinfo">
				<span class="<?php echo $this->params->get('marker_class'); ?>">
					<?php echo $this->params->get('marker_misc'); ?>
				</span>
				<?php echo $this->contact->misc; ?>
			</div>
	<?php endif; ?>

	<?php if ($this->params->get('presentation_style')!='plain') : ?>
		<?php echo JHtml::_($this->params->get('presentation_style').'.end'); ?>
	<?php endif; ?>
 
	<div class="clearbreak"></div>
</div>
</div>
<?php endif; ?>