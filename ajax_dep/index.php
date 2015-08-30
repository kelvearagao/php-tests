<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<style type="text/css">
	.hotels {
	
	}

	.hotel {
		background-color: pink;
	}
</style>
<body>
	<h1 class="load-hotels">Hotels</h1>
	<div class="hotels">
	</div>

<script src="jquery-1.11.3.min.js"></script>
<script type="text/javascript">

	$('.load-hotels').click(function() {
		var date = new Date();
		var cont_cities = 5;
		var cities = ['sobral', 'fortaleza', 'forquilha', 'ibiapina', 'canide'];
		
		requestHotels(date, cont_cities, cities);
		
	});

	function requestHotels(date, cont_cities, cities)
	{
		requestHotel(date, cont_cities, cities);
	};

	function requestHotel(date, cont_cities, cities)
	{
		var city = cities[cities.length - cont_cities];

		$.ajax({
			type:"POST",
			url: "hotels.php",
			data: { 'city' : city },
			async: true,
			beforeSend: function(xhr) {
				console.log(cont_cities + ' Inicio');
				$('.hotels').append('<h5 class="hotel">'+ cont_cities + ' carregando..</h5>');
			}
		})
		.done(function(min_days){
			console.log(cont_cities + ' Fim');
			var new_date = dateAddDays(date, parseInt(min_days));

			$('.hotels')
				.find('.hotel').last()
				.empty()
				.text(city +' - '+ format(date) +' / '+ format(new_date) +' ('+min_days+ 'dia(s))');

			if(cont_cities > 1)
				requestHotel(new_date, cont_cities - 1, cities);
			else
				console.log('Fim de totas!');
		})
		.fail(function(data){
			console.log(data);
			console.log('Erro!');
		});
	};

	function dateAddDays(date, days)
	{
		var new_date = new Date();
		var days_time = days * 24 * 60 * 60 * 1000;
		
		new_date.setTime(date.getTime() + days_time);

		return new_date;
	}

	function format(date)
	{
		var day = date.getDate();
		var month = date.getMonth() + 1;
		var year = date.getFullYear();

		return year+'-'+month+'-'+day;
	}
</script>
</body>
</html>