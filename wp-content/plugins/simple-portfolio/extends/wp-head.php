<?php

if (get_option('show-credits') == '1') {
	add_action( 'wp_head', 'portolio_wp_head'  );
}

function portolio_wp_head() {
	echo "\n\n\t<!-- This site is based on the simple-portfolio plugin -->\n";
	echo "\t<!-- The plugin is created by Patrick Brouwer (Inlet) -->\n";
	echo "\t<!-- For more information see my blog: blog.inlet.nl or contact me: patrick@inlet.nl -->\n\n\n";
}
	
