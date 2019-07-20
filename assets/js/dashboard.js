var options = {
	chart: {
	  height: 350,
	  type: 'line',
	  zoom: {
		enabled: false
	  }
	},
	dataLabels: {
	  enabled: false
	},
	stroke: {
	  curve: 'straight'
	},
	series: [{
	  name: "Sales",
	  data: [10, 41, 35, 51, 49, 62, 69, 91, 80, 90, 85, 65]
	}],
	title: {
	  text: 'Sales by month',
	  align: 'left'
	},
	grid: {
	  row: {
		colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
		opacity: 0.5
	  },
	},
	xaxis: {
	  categories: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
	}
  }

  var chart = new ApexCharts(
	document.querySelector("#chart"),
	options
  );

  chart.render();

