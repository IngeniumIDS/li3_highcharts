Highcharts Helper for Lithium
=============================

Installation
------------

Assuming your project is a clone of `https://github.com/UnionOfRAD/framework.git`, register li3_highcharts as a submodule into the projects' libraries.

```
cd path/to/project
git submodule add https://github.com/IngeniumIDS/li3_highcharts.git libraries/li3_highcharts
```

Make the application aware of the library by adding the following to `app/config/bootstrap/libraries.php`.

```php
Libraries::add('li3_highcharts');
```

Finally load the jQuery and highcharts JavaScript libraries between the `<head>` and `</head>` HTML tags at the top of the layout.

```php
echo $this->html->script([
	'//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js',
	'http://code.highcharts.com/highcharts.js'
]);
```

Usage
-----

The helper has seven functions that generate different types of charts (area, areaSpline, bar, column, line, pie and spline). These functions accept three parameters (title, data and options).

Parameter formatting:

* The title should either be a string or null.
* The data for the area, areaSpline, bar, column, line and spline functions is a PHP array version of the [series.data JavaScript array](http://api.highcharts.com/highcharts#series.data).
* The data for the pie function is an associative array where the keys are the series names and the values are integers.
* The options is a PHP array version of the [JavaScript object](http://api.highcharts.com/highcharts) that would normally be passed to the highcharts JavaScript function.

Some options have been added to make a few things easier:

* Series names can be set as the keys of the data array.
* Values of the data array that aren't arrays are applied to all series as options.
* The pointStart option can be set to any [PHP supported date and time format](http://www.php.net/manual/en/datetime.formats.php).
* The pointInterval option can be set to second, minute, hour, day or week.

Area chart:

```php
echo $this->highcharts->area(
	'Daily Page Clicks',
	[
		'pointStart' => '28 August 2013',
		'pointInterval' => 'day',
		'Home' => ['data' => [1,3,2,7,5,4,6,2]],
		'Contact' => ['data' => [0,1,1,4,5,0,4,1]]
	],
	[
		'xAxis' => ['type' => 'datetime'],
		'yAxis' => ['title' => ['text' => 'Clicks']]
	]
);
```

Pie chart:

```php
echo $this->highcharts->pie(
	'Browser Share',
	[
		'Chrome' => 61,
		'Safari' => 38,
		'IE' => 6,
		'Others' => 4,
		'Opera' => 3
	]
);
```

Anonymous JavaScript functions can be used.

Area spline chart:

```php
echo $this->highcharts->areaSpline(
	'Daily Page Clicks',
	[
		'pointStart' => '(function(){return Date.UTC(2013,7,28,0,0,0);})()',
		'pointInterval' => '(function(){return 24 * 3600 * 1000;})()',
		'Home' => ['data' => [1,3,2,7,5,4,6,2]],
		'Contact' => ['data' => [0,1,1,4,5,0,4,1]]
	],
	[
		'xAxis' => ['type' => 'datetime'],
		'yAxis' => ['title' => ['text' => 'Clicks']]
	]
);
```

The helper has one more function (chart) which can be used with only the options parameter to allow full configuration.

Spline chart:

```php
echo $this->highcharts->chart([
	'chart' => ['type' => 'spline'],
	'title' => ['text' => 'Daily Page Clicks'],
	'xAxis' => ['type' => 'datetime'],
	'yAxis' => ['title' => ['text' => 'Clicks']],
	'series' => [
		'pointStart' => '28 August 2013',
		'pointInterval' => 'day',
		'Home' => ['data' => [1,3,2,7,5,4,6,2]],
		'Contact' => ['data' => [0,1,1,4,5,0,4,1]]
	]
]);
```
