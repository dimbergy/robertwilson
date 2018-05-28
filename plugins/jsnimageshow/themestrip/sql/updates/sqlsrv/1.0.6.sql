SET QUOTED_IDENTIFIER ON;
IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[#__imageshow_theme_strip]') AND type in (N'U'))
BEGIN
ALTER TABLE [#__imageshow_theme_strip] 
ADD 
  	[slideshow_auto_play] [nvarchar](150) DEFAULT 'no',
	[slideshow_delay_time] [nvarchar](150) DEFAULT '3000'
END;