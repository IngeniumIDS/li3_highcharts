Highcharts Helper for Lithium
-----------------------------

The helper has seven functions that generate different types of charts (area, areaSpline, bar, column, line, pie and spline). These functions accept three parameters (title, data and options).

Parameter formatting:

* The title should either be a string or null.
* The data for the area, areaSpline, bar, column, line and spline functions is a PHP array version of the [series.data JavaScript array]("http://api.highcharts.com/highcharts#series.data").
* The data for the pie function is an associative array where the keys are the series names and the values are integers.
* The options is a PHP array version of the [JavaScript object]("http://api.highcharts.com/highcharts") that would normally be passed to the highcharts JavaScript function.

Some options have been added to make a few things easier:

* Series names can be set as the keys of the data array.
* Values of the data array that aren't arrays are applied to all series as options.
* The pointStart option can be set to any [PHP supported date and time format]("http://www.php.net/manual/en/datetime.formats.php").
* The pointInterval option can be set to second, minute, hour, day or week.

Area chart:

	echo $this->highcharts->area(
		'Daily Page Clicks',
		array(
			'pointStart' => '28 August 2013',
			'pointInterval' => 'day',
			'Home' => array(
				'data' => array(1,3,2,7,5,4,6,2)
			),
			'Contact' => array(
				'data' => array(0,1,1,4,5,0,4,1)
			)
		),
		array(
			'xAxis' => array(
				'type' => 'datetime'
			),
			'yAxis' => array(
				'title' => array(
					'text' => 'Clicks'
				)
			)
		)
	);

Pie chart:

	echo $this->highcharts->pie(
		'Browser Share',
		array(
			'Chrome' => 61,
			'Safari' => 38,
			'IE' => 6,
			'Others' => 4,
			'Opera' => 3
		)
	);

Anonymous JavaScript functions can be used.

Area spline chart:

	echo $this->highcharts->areaSpline(
		'Daily Page Clicks',
		array(
			'pointStart' => '(function(){return Date.UTC(2013,7,28,0,0,0);})()',
			'pointInterval' => '(function(){return 24 * 3600 * 1000;})()',
			'Home' => array(
				'data' => array(1,3,2,7,5,4,6,2)
			),
			'Contact' => array(
				'data' => array(0,1,1,4,5,0,4,1)
			)
		),
		array(
			'xAxis' => array(
				'type' => 'datetime'
			),
			'yAxis' => array(
				'title' => array(
					'text' => 'Clicks'
				)
			)
		)
	);

The helper has one more function (chart) which can be used with only the options parameter to allow full configuration.

Spline chart:

	echo $this->highcharts->chart(array(
		'chart' => array(
			'type' => 'spline'
		),
		'title' => array(
			'text' => 'Daily Page Clicks'
		),
		'xAxis' => array(
			'type' => 'datetime'
		),
		'yAxis' => array(
			'title' => array(
				'text' => 'Clicks'
			)
		),
		'series' => array(
			'pointStart' => '28 August 2013',
			'pointInterval' => 'day',
			'Home' => array(
				'data' => array(1,3,2,7,5,4,6,2)
			),
			array(
				'data' => array(0,1,1,4,5,0,4,1)
			)
		)
	));
