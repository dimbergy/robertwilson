SET QUOTED_IDENTIFIER ON;
IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[#__imageshow_theme_masonry]') AND type in (N'U'))
BEGIN
ALTER TABLE [#__imageshow_theme_masonry]
ADD
	[pagination_type] [nvarchar](150) DEFAULT 'all',
	[number_load_image] [nvarchar](150) DEFAULT '6',
END;
