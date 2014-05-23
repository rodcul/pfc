UPDATE wp_options SET option_value = replace(option_value, 'http://dev.pfc.rodcul.com', 'http://pfc.rodcul.com') WHERE option_name = 'home' OR option_name = 'siteurl' OR option_name = 'videotube';

UPDATE wp_posts SET guid = replace(guid, 'http://dev.pfc.rodcul.com','http://pfc.rodcul.com');

UPDATE wp_posts SET post_content = replace(post_content, 'http://dev.pfc.rodcul.com', 'http://pfc.rodcul.com');

UPDATE wp_postmeta SET meta_value = replace(meta_value,'http://dev.pfc.rodcul.com','http://pfc.rodcul.com');

