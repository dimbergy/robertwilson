ALTER TABLE `#__imageshow_theme_masonry`
ADD COLUMN `pagination_type` char(150) NOT NULL DEFAULT 'all';
ALTER TABLE `#__imageshow_theme_masonry`
ADD COLUMN `number_load_image` char(150) NOT NULL DEFAULT '6';