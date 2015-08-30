<?php
	sleep(5);

	$min_days = 1;

	switch ($_POST['city']) 
	{
		case 'sobral':
				$min_days = 3;
			break;
		case 'fortaleza':
				$min_days = 2;
			break;
		case 'ibiapina':
				$min_days = 1;
			break;
		case 'canide':
				$min_days = 2;
			break;
		case 'forquilha':
				$min_days = 1;
			break;
	}

	echo $min_days;
?>