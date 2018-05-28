SET QUOTED_IDENTIFIER ON;

IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[#__imageshow_theme_masonry]') AND type in (N'U'))
BEGIN
CREATE TABLE [#__imageshow_theme_masonry](
	[theme_id] [int] IDENTITY(1,1) NOT NULL,
	[image_border] [nvarchar](150) DEFAULT '0',
	[image_rounded_corner] [nvarchar](150) DEFAULT '0',
	[image_border_color] [nvarchar](150) DEFAULT '#eeeeee',
	[image_click_action] [nvarchar](150) DEFAULT '',
	[image_source] [nvarchar](150) DEFAULT '',
	[open_link_in] [nvarchar](150) DEFAULT '',
	[show_caption] [nvarchar](150) DEFAULT 'yes',
	[caption_background_color] [nvarchar](150) DEFAULT '#000000',
	[caption_opacity] [nvarchar](150) DEFAULT '75',
	[caption_show_title] [nvarchar](150) DEFAULT 'yes',
	[caption_title_css] [nvarchar](255) DEFAULT '',
	[caption_show_description] [nvarchar](150) DEFAULT 'yes',
	[caption_description_css] [nvarchar](255) DEFAULT '',
	[caption_description_length_limitation] [nvarchar](150) DEFAULT '50',
	[layout_type] [nvarchar](150) DEFAULT '',
	[column_width] [nvarchar](150) DEFAULT '0',
	[gutter] [nvarchar](150) DEFAULT '0',
	[is_fit_width] [nvarchar](150) DEFAULT 'true',
	[transition_duration] [nvarchar](150) DEFAULT '0.4',
	[feature_image] [nvarchar](150) DEFAULT '',
	[pagination_type] [nvarchar](150) DEFAULT 'all',
	[$number_load_image] [nvarchar](150) DEFAULT '6',
 CONSTRAINT [PK_#__imageshow_theme_masonry_theme_id] PRIMARY KEY CLUSTERED
(
	[theme_id] ASC
)WITH (STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF)
)
END;