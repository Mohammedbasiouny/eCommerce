<?php

function lang($phrase)
{

	static $lang = array(

		// Navbar Links

		'HOME_ADMIN' 	=> 'Home',
		'CATEGORIES' 	=> 'Categories',
		'ITEMS' 		=> 'Items',
		'MEMBERS' 		=> 'Members',
		'STATISTICS' 	=> 'Statistics',
		'LOGS' 			=> 'Logs',
		'COMMENTS' 		=> 'Comments'
	);

	return $lang[$phrase];
}
