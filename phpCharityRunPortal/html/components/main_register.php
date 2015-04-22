<?php if(DEBUGMODE == 1) echo '<p>'.__FILE__.'</p>'; ?>
<p>Eine E-Mail mit einem Aktivierungscode wurde an Sie versand. Bitte öffnen Sie den Link mit dem Aktivierungscode, um die Aktivierung Ihres Accounts abzuschließen.</p>
<?php
		call(function(){
			$show_main = true;
			$inset = true;
			require 'main_menu.php';
		});
?>