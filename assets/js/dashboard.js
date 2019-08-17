var options = {
	chart: {
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
	  name: "Turnover",
	  data: munim.monthly_trend // Dynamic data passed via 'wp_localize_script'
	}],
	grid: {
	  row: {
		colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
		opacity: 0.5
	  },
	},
	xaxis: {
	  categories: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
	},
	yaxis: {
		labels: {
		  formatter: function (value) {
			return new Intl.NumberFormat().format(value);
		  }
		},
	  },
  }

  var chart = new ApexCharts(
	document.querySelector("#munim-trend-chart"),
	options
  );

  chart.render();

