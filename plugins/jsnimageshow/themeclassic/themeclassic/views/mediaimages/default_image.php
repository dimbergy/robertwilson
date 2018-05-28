<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow - Theme Classic
 * @version $Id: default_image.php 6649 2011-06-08 10:29:57Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
$objPlgMediaHelper = new MediaHelper();
?>
<div class="item jsn-graphic">
	<a href="javascript:JSNISImageManager.populateFields('<?php echo $this->_tmp_img->path_relative; ?>')">
		<img src="<?php echo $this->baseURL.'/'.$this->_tmp_img->path_relative; ?>" class="jsn-graphic-showcase" width="<?php echo $this->_tmp_img->width_60; ?>" height="<?php echo $this->_tmp_img->height_60; ?>" alt="<?php echo $this->_tmp_img->name; ?> - <?php echo $objPlgMediaHelper->parseSize($this->_tmp_img->size); ?>" />
		<span>
			<?php echo $this->_tmp_img->name; ?>
			<br/>
			<?php echo $this->_tmp_img->width .'x'.$this->_tmp_img->height;?>
		</span>
	</a>
</div>
