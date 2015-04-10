<?php

namespace li3_highcharts\extensions\helper;

class Highcharts extends \lithium\template\Helper {

	protected $_defaults = [
		'area' => [
			'chart' => ['type' => 'area'],
			'credits' => ['enabled' => false]
		],
		'areaspline' => [
			'chart' => ['type' => 'areaspline'],
			'credits' => ['enabled' => false]
		],
		'bar' => [
			'chart' => ['type' => 'bar'],
			'tooltip' => ['shared' => true]
		],
		'column' => [
			'chart' => ['type' => 'column'],
			'tooltip' => ['shared' => true]
		],
		'line' => [
			'chart' => ['type' => 'line'],
			'credits' => ['enabled' => false]
		],
		'pie' => [
			'chart' => ['type' => 'pie'],
			'credits' => ['enabled' => false],
			'plotOptions' => [
				'pie' => [
					'allowPointSelect' => true,
					'cursor' => 'pointer',
					'dataLabels' => ['enabled' => false],
					'showInLegend' => true
				]
			],
			'tooltip' => ['pointFormat' => '<b>{point.y} ({point.percentage:.1f}%)</b>']
		],
		'spline' => [
			'chart' => ['type' => 'spline'],
			'credits' => ['enabled' => false]
		]
	];

	protected $_strings = [
		'chart' => '<div id="{:id}"></div><script type="text/javascript">$("#{:id}").highcharts({:options});</script>',
		'date' => '(function(){return Date.UTC({:year},{:month},{:day},{:hour},{:min},{:sec});})()',
		'id' => 'HighchartsChart{:id}'
	];

	public function area($title, array $data, array $options = []) {
		return $this->chart($options, 'area', $title, $data);
	}

	public function areaSpline($title, array $data, array $options = []) {
		return $this->chart($options, 'areaspline', $title, $data);
	}

	public function bar($title, array $data, array $options = []) {
		return $this->chart($options, 'bar', $title, $data);
	}

	public function column($title, array $data, array $options = []) {
		return $this->chart($options, 'column', $title, $data);
	}

	public function chart(array $options, $type = null, $title = null, array $data = []) {
		$options = $this->_chartOptions($type, $title, $data, $options);
		$id = $this->_id();
		return $this->_render(__METHOD__, 'chart', compact('id', 'options'));
	}

	public function line($title, array $data, array $options = []) {
		return $this->chart($options, 'line', $title, $data);
	}

	public function pie($title, array $data, array $options = []) {
		return $this->chart($options, 'pie', $title, $data);
	}

	public function spline($title, array $data, array $options = []) {
		return $this->chart($options, 'spline', $title, $data);
	}

	private function _chartOptions($type = null, $title = null, array $data = [], array $options = []) {
		$defaults = isset($this->_defaults[$type]) ? $this->_defaults[$type] : [];
		if (!isset($options['title']) && isset($title)) {
			$options['title'] = ['text' => $title];
		}
		if (empty($data)) {
			if (isset($options['series'])) {
				$options['series'] = $this->_series($type, $options['series']);
			}
		} else {
			$options['series'] = $this->_series($type, $data);
		}
		$options = array_replace_recursive($defaults, $options);
		$options = preg_replace_callback(
			'/(?<=:)"\(?function\((?:(?!}(\)\(\))?").)*}(\)\(\))?"/',
			function ($matches) {
				return stripslashes(substr($matches[0], 1, -1));
			},
			json_encode($options)
		);
		return $options;
	}

	private function _pointStart($date) {
		$time = strtotime($date);
		if (!$time) {
			return $date;
		}
		$year = date('Y', $time);
		$month = date('n', $time) - 1;
		$day = date('j', $time);
		$hour = date('G', $time);
		$min = preg_replace('/^0/', '', date('i', $time));
		$sec = preg_replace('/^0/', '', date('s', $time));
		return $this->_render(__METHOD__, 'date', compact('year', 'month', 'day', 'hour', 'min', 'sec'));
	}

	private function _pointInterval($interval) {
		switch ($interval) {
			case 'week':
				return 7 * 24 * 3600 * 1000;
			case 'day':
				return 24 * 3600 * 1000;
			case 'hour':
				return 3600 * 1000;
			case 'minute':
				return 60 * 1000;
			case 'second':
				return 1000;
		}
		return $interval;
	}

	private function _id() {
		$id = uniqid();
		return $this->_render(__METHOD__, 'id', compact('id'));
	}

	private function _series($type = null, array $data) {
		switch ($type) {
			case 'pie':

				$series = [['data' => [], 'type' => 'pie']];
				foreach ($data as $key => $value) {
					$series[0]['data'][] = [(string) $key, $value];
				}

				return $series;

			default:

				$globalOptions = [];
				foreach ($data as $option => $value) {
					if (!is_array($value)) {
						$globalOptions[$option] = $value;
						unset($data[$option]);
					}
				}
				foreach ($data as $name => $options) {
					$data[$name] = $options + $globalOptions;
				}

				$i = 0;
				$series = [];
				foreach ($data as $name => $options) {
					$series[$i] = $options;
					if (is_string($name)) {
						$series[$i]['name'] = $name;
					}
					if (isset($options['pointStart'])) {
						$series[$i]['pointStart'] = $this->_pointStart($options['pointStart']);
					}
					if (isset($options['pointInterval'])) {
						$series[$i]['pointInterval'] = $this->_pointInterval($options['pointInterval']);
					}
					if (isset($options['data'])) {
						$series[$i]['data'] = [];
						foreach ($options['data'] as $pointValue) {
							$series[$i]['data'][] = $pointValue;
						}
					}
					$i++;
				}

				return $series;

		}
	}

}
