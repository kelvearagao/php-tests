<?php
    function existsRoomTypeInRotelPrices($prices, $room_type)
    {
    	foreach( $prices as $category )
    		if( ! empty($category[$room_type]) )
    			return true;

    	return false;
    }

    function calcDatesPrices($dates)
    {
    	$sum = 0.00;

    	foreach($dates as $date)
			$sum += $date->price;

		return $sum;
    }

    function getRoomCategoryMinPrice($prices, $room_type)
    {
    	$room  = null;
    	$min_price = null;

		foreach( $prices as $category_name => $category )
    		if( ! empty($category[$room_type]) )
    		{
    			$sum = calcDatesPrices($category[$room_type]);

				if( empty($min_price) OR $sum < $min_price )
				{
					$min_price = $sum;
					$room['category'] = $category_name;
					$room['total_price'] = $min_price;
				}
    		}

    	return $room;
    }

    function convertRoom($room_type, $amount)
    {
    	$rooms = [];

    	switch ($room_type) 
    	{
    		case 'TPL':
    				$rooms = ['SGL' => $amount, 'DBL' => $amount];
    			break;

    		case 'QDPL':
    				$rooms = ['DBL' => 2 * $amount];
    				break;
    		
    		default:
    			 echo 'O tipo ' . $room_type . ' nao existe!';
    			 exit;

    			break;
    	}

    	return $rooms;
    }

    function getRoomMinPrice($prices, $room_type, $room_amount)
    {
    	$room = getRoomCategoryMinPrice($prices, $room_type);
  			
		$acm_selected    = ['type'        => $room_type, 
							'category'    => $room['category'], 
							'total_price' => $room['total_price'],
							'amount'      => $room_amount];

		return $acm_selected;
    }

    function normalizeRooms($rooms)
    {
    	$normalized_rooms = [];

    	foreach( $rooms as $room ) 
    	{
    		$type     = $room['type'];
    		$category = $room['category'];
    		
    		if( empty($normalized_rooms[$category.'-'.$type]) )
    			$normalized_rooms[$category.'-'.$type] = $room;
    		else
    			$normalized_rooms[$category.'-'.$type]['amount'] += $room['amount'];
    	}

    	return $normalized_rooms;
    }

	$prices = [
				'standard'  => 	[
							   		'SGL' => [
							   					'2015-07-10' => (object) ['price' => 12]
							   				 ],
							   		'DBL' => [
							   					'2015-07-10' => (object) ['price' => 20]
							   				 ]
								],
				'luxo'		=>  [
									'SGL' => [
												'2015-07-11' => (object) ['price' => 11]
											 ],
									'DBL' => [
												'2015-07-11' => (object) ['price' => 22]
									         ],
									'TPL' => [
												'2015-07-11' => (object) ['price' => 22]
									         ]
								],
				'medium'	=>  [
									'QDPL' => [
												'2015-07-11' => (object) ['price' => 11]
											 ]
								]
			  ];
	
	echo '<pre>';
    print_r($prices);
    echo '</pre>';

    echo '<hr>';

    // Acomodando

    $user_select_rooms = ['SGL' => 1, 'DBL' => 3, 'TPL' => 1, 'QDPL' => 10];

    $acm_selecteds     = [];
    foreach( $user_select_rooms as $room_type => $room_amount )
    {
   		if( existsRoomTypeInRotelPrices($prices, $room_type) )
  		{
  			$acm_selecteds[] = getRoomMinPrice($prices, $room_type, $room_amount);
  		}	
   		else
   		{
   			$converted_rooms = convertRoom($room_type, $room_amount);
   			foreach($converted_rooms as $converted_room_type => $converted_room_amount )
   				$acm_selecteds[] = getRoomMinPrice($prices, $converted_room_type, $converted_room_amount);
   		}

   	}

   	$acm_selecteds = normalizeRooms($acm_selecteds);

   	echo '<pre>'; 
   	print_r($acm_selecteds);
?>