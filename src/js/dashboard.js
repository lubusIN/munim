var options = {
	chart: {
		height: '96%',
	  type: 'line',
	  zoom: {
		enabled: false
	  },
	  toolbar: {
		show: false,
	  }
	},
	dataLabels: {
	  enabled: false
	},
	stroke: {
	  curve: 'straight'
	},
	series: [{
	  name: 'Turnover',
	  type: 'column',
	  data: munim.monthly_trend_gross // Dynamic data passed via 'wp_localize_script'
	},
	{
		name: 'Revenue',
		type: 'line',
		data: munim.monthly_trend_net // Dynamic data passed via 'wp_localize_script'
	}],
	xaxis: {
	  categories: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
	  labels: {
		show: true,
		rotate: -45,
		rotateAlways: true,
	  },
	},
	yaxis: {
		labels: {
		  formatter: function (value) {
			return new Intl.NumberFormat().format(value);
		  }
		},
	  },
  }

  var munimTrendChart = new ApexCharts(
	document.querySelector("#munim-trend-chart"),
	options
  );

  munimTrendChart.render();

