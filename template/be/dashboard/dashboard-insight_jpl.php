<div class="page-header">
	<div class="page-header-left d-flex align-items-center">
		<div class="page-header-title">
			<h5 class="m-b-10"><?=$this->pageTitle?></h5>
		</div>
	</div>
</div>

<div class="main-content">
	<div class="card stretch stretch-full">
		<div class="card-header"><h4 class="card-title">Pencarian</h4></div>
		<div class="card-body">
			<form method="get" action="<?=$targetpage?>">
				<div class="form-group row mb-2">
					<label class="col-sm-2 col-form-label" for="tahun">Tahun</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="tahun" name="tahun" value="<?=$tahun?>" />
					</div>
				</div>
				
				<div class="form-group row mb-2">
					<label class="col-sm-2 col-form-label" for="entitas">Entitas</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="entitas" name="entitas" value="<?=$entitas?>" />
					</div>
				</div>
				
				<input class="btn btn-primary" type="submit" value="cari"/>
			</form>
		</div>
	</div>
	
	<div class="card stretch stretch-full">
		<div class="card-body">
			<div class="row">
				<div class="col-6">
					<div class="rounded border border-primary mb-2">
						<div id="chart1"></div>
						<div class="alert alert-info mb-0"><strong>Insight : </strong> Capaian Total JPL sampai dengan bulan xxx adalah xxx atau telah mencapai xxx % dari target tahun xxx sebesar xxx JPL.</div>
					</div>
					<div class="rounded border border-primary mb-2">
						<div id="chart2"></div>
						<div class="alert alert-info mb-0"><strong>Insight : </strong> Capaian JPL Formal Learning sampai dengan bulan xxx adalah xxx atau telah mencapai xxx % dari target tahun xxx sebesar xxx JPL.</div>
					</div>
				</div>
				<div class="col-6">
					<div class="rounded border border-primary mb-2">
						<div id="chart3"></div>
						<div class="alert alert-info mb-0"><strong>Insight : </strong> Capaian JPL Social Learning sampai dengan bulan xxx adalah xxx atau telah mencapai xxx % dari target tahun xxx sebesar xxx JPL.</div>
					</div>
					<div class="rounded border border-primary mb-2">
						<div id="chart4"></div>
						<div class="alert alert-info mb-0"><strong>Insight : </strong> Capaian JPL Learning from Experiences sampai dengan bulan xxx adalah xxx atau telah mencapai xxx % dari target tahun xxx sebesar xxx JPL.</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-4">
			<div class="card stretch stretch-full">
				<div class="card-body">
					<h6>JPL Formal Learning</h6>
					<div class="rounded border border-primary mb-2">
						<div id="chart6"></div>
						<div class="alert alert-info mb-0"><strong>Insight : </strong> Capaian JPL formal learning entitas xxxxx terdiri dari kegiatan workshop dengan jumlah JPL xxxxx, kegiatan in class learning dengan jumlah JPL xxxxx.</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-4">
			<div class="card stretch stretch-full">
				<div class="card-body">
					<h6>JPL Social Learning</h6>
					<div class="rounded border border-primary mb-2">
						<div id="chart7"></div>
						<div class="alert alert-info mb-0"><strong>Insight : </strong> Capaian JPL social learning entitas xxx terdiri dari kegiatan story telling/sharing session dengan jumlah JPL xxx, kegiatan benchmark dengan jumlah JPL xxx.</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-4">
			<div class="card stretch stretch-full">
				<div class="card-body">
					<h6>JPL Learning From Experiences</h6>
					<div class="rounded border border-primary mb-2">
						<div id="chart8"></div>
						<div class="alert alert-info mb-0"><strong>Insight : </strong> Capaian JPL learning from experiences entitas xxx terdiri dari kegiatan coaching dengan jumlah JPL xxx, kegiatan mentoring dengan jumlah JPL xxx.</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="card stretch stretch-full">
		<div class="card-header">
			<h5 class="card-title">Realisasi Anggaran sampai dengan</h5>
		</div>
		<div class="card-body">
			<div class="rounded border border-primary mb-2">
				<div id="chart5"></div>
				<div class="alert alert-info mb-0"><strong>Insight : </strong> Realisasi anggaran menunjukkan tren kenaikan setiap bulan, dengan pencapaian tertinggi pada Desember sebesar 100%, yang terdiri dari 90% anggaran LPP dan 10% anggaran eksternal.</div>
			</div>
		</div>
	</div>
	
	<div class="card stretch stretch-full">
		<div class="card-header">
			<h5 class="card-title">Summary</h5>
		</div>
		<div class="card-body">
			Realisasi serapan anggaran Pengembangan SDM s.d. Juli 2025 sebesar Rp. 70,8 M atau sebesar 37,81% dari RKAP (Rp. 186 M). Realisasi s.d. Juli 2025 mengalami kenaikan 56% dibanding realisasi s.d. Juli 2024, Dimana program pengembangan dijalankan dengan metode workshop, in class learning, self learning (metode 10%). Capaian learning hours s.d. Juli untuk Karyawan Pimpinan sebesar XXXX atau XXX dari target 40 learning hours. Capaian learning hours s.d Juli sebesar XXXX jam atau XXXX dari target 40 learning hours.
		</div>
	</div>
</div>

<script>

function setupChart(judul,ele) {
	new ApexCharts(document.querySelector('#'+ele), {
        chart: {
            height: 250,
            width: "100%",
            stacked: !1,
            toolbar: {
                show: !1
            }
        },
        stroke: {
            width: [1, 2, 3],
            curve: "smooth",
            lineCap: "round"
        },
        plotOptions: {
			bar: {
				columnWidth: "80%",
				endingShape: "rounded",
				colors: {
					backgroundBarColors: ["#EEEEEE"],
					backgroundBarOpacity: 0.6,
					backgroundBarRadius: 9
				}
			}
        },
        colors: ["#0b9f6e"],
        series: [
		/* {
            name: "Target",
            type: "line",
            data: [48, 48, 48, 48, 48, 48, 48, 48, 48, 48, 48, 48]
        },  */
		{
            name: "Realisasi",
            type: "bar",
            data: [10, 10, 15, 18, 22, 26, 29, 30, 35, 38, 48, 48]
        }],
        fill: {
            // opacity: [1, 1],
            type: 'solid',
			/* gradient: {
				shade: 'light',
				// gradientToColors: [ '#FDD835'],
				shadeIntensity: 0.8,
				type: 'vertical',
				opacityFrom: 1,
				opacityTo: 1
			}, */
        },
        markers: {
            size: 0
        },
        xaxis: {
            categories: ["jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
            axisBorder: {
                show: !1
            },
            axisTicks: {
                show: !1
            },
            labels: {
                style: {
                    fontSize: "10px",
                    colors: "#A0ACBB"
                }
            }
        },
        yaxis: {
            min: 0,
			max: 48,
			tickAmount: 8,
			labels: {
                formatter: function(e) {
                    return +e + " JPL"
                },
                offsetX: -5,
                offsetY: 0,
                style: {
                    color: "#A0ACBB"
                }
            }
        },
        grid: {
            xaxis: {
                lines: {
                    show: !1
                }
            },
            yaxis: {
                lines: {
                    show: !1
                }
            }
        },
        dataLabels: {
            enabled: !1
        },
        tooltip: {
            y: {
                formatter: function(e) {
                    return +e + "K"
                }
            },
            style: {
                fontSize: "12px",
                fontFamily: "Inter"
            }
        },
        legend: {
            show: true,
            itemMargin: { horizontal: 20 } ,
			labels: {
                fontSize: "12px",
                colors: "#A0ACBB"
            },
            fontFamily: "Inter"
        },
		title: {
			text: judul,
			align: 'center',
			margin: 10,
			offsetX: 0,
			offsetY: 20,
			floating: false,
			style: {
			  fontSize:  '14px',
			  fontWeight:  'bold',
			  fontFamily:  "Roboto",
			  color:  '#263238'
			},
		}
    }).render();
}

function setupChart2(ele) {
	var options = {
		colors: ["#27548A", "#006A67"],
		series: [{
		  name: '(%) Realisasi Anggaran LPP',
		  data: [5, 20, 30, 40, 50, 60, 70, 80, 80, 90]
		}, {
		  name: '(%) Realisasi Anggaran External',
		  data: [0, 1, 1, 3, 5, 9, 9, 9, 10, 10]
		}],
		  chart: {
		  type: 'bar',
		  height: 350,
		  stacked: true,
		  toolbar: {
			show: false
		  },
		  zoom: {
			enabled: false
		  }
		},
		responsive: [{
		  breakpoint: 480,
		  options: {
			legend: {
			  position: 'bottom',
			  offsetX: -10,
			  offsetY: 0
			}
		  }
		}],
		plotOptions: {
			bar: {
				columnWidth: "98%",
				borderRadiusApplication: 'end', // 'around', 'end'
				borderRadiusWhenStacked: 'last', // 'all', 'last'
				colors: {
					backgroundBarColors: ["#EEEEEE"],
					backgroundBarOpacity: 0.6,
					backgroundBarRadius: 9
				}
			}
		  /* bar: {
			horizontal: false,
			borderRadius: 10,
			borderRadiusApplication: 'end', // 'around', 'end'
			borderRadiusWhenStacked: 'last', // 'all', 'last'
			dataLabels: {
			  total: {
				enabled: true,
				style: {
				  fontSize: '13px',
				  fontWeight: 900
				}
			  }
			}
		  }, */
		},
		xaxis: {
		  categories: ["jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
		},
		yaxis: {max: 100}, 
		legend: {
			show: true,
			position: 'bottom',
			itemMargin: { horizontal: 20 } ,
			labels: {
				fontSize: "12px",
				colors: "#A0ACBB"
			},
			fontFamily: "Inter"
		},
		fill: {
			type: 'solid',
			/* gradient: {
				shade: 'light',
				// gradientToColors: [ '#EEE'],
				shadeIntensity: 0.6,
				type: 'vertical',
				opacityFrom: 1,
				opacityTo: 1
			}, */
		}
	};

	var chart = new ApexCharts(document.querySelector("#"+ele), options);
	chart.render();
}

function setupChart3(ele) {
	var options = {
		colors: ["#27548A", "#006A67"],
	  series: [{
	  name: 'Workshop',
	  data: [2, 2, 2]
	}, {
	  name: 'In Class Learning',
	  data: [4, 4, 4]
	}],
	  chart: {
	  type: 'bar',
	  height: 200,
	  stacked: true,
	  toolbar: false,
	},
	plotOptions: {
	  bar: {
		horizontal: true,
		dataLabels: {
		  total: {
			enabled: true,
			offsetX: 0,
			style: {
			  fontSize: '13px',
			  fontWeight: 900
			}
		  }
		}
	  },
	},
	stroke: {
	  width: 1,
	  colors: ['#fff']
	},
	xaxis: {
	  categories: ['PTPN 4','PTPN 1','PT SGN'],
	  max: 10,
	  labels: {
		show: false,
		formatter: function (val) {
		  return val + " JPL"
		}
	  }
	},
	yaxis: {
	  title: {
		text: undefined
	  },
	},
	tooltip: {
	  y: {
		formatter: function (val) {
		  return val + " JPL"
		}
	  }
	},
	fill: {
		type: 'solid',
		/* gradient: {
			shade: 'light',
			// gradientToColors: [ '#EEE'],
			shadeIntensity: 0.6,
			type: 'vertical',
			opacityFrom: 1,
			opacityTo: 1
		}, */
	},
	legend: {
	  position: 'bottom',
	  horizontalAlign: 'left',
	  offsetX: 20
	}
	};

	var chart = new ApexCharts(document.querySelector("#"+ele), options);
	chart.render();
}

function setupChart4(ele) {
	var options = {
	  colors: ["#27548A", "#006A67"],
	  series: [{
	  name: 'Story Telling/Sharing Session',
	  data: [2, 2, 2]
	}, {
	  name: 'Benchmark',
	  data: [4, 4, 4]
	}],
	  chart: {
	  type: 'bar',
	  height: 200,
	  stacked: true,
	  toolbar: false,
	},
	plotOptions: {
	  bar: {
		horizontal: true,
		dataLabels: {
		  total: {
			enabled: true,
			offsetX: 0,
			style: {
			  fontSize: '13px',
			  fontWeight: 900
			}
		  }
		}
	  },
	},
	stroke: {
	  width: 1,
	  colors: ['#fff']
	},
	xaxis: {
	  categories: ['PTPN 4','PTPN 1','PT SGN'],
	  max: 10,
	  labels: {
		show: false,
		formatter: function (val) {
		  return val + " JPL"
		}
	  }
	},
	yaxis: {
	  title: {
		text: undefined
	  },
	},
	tooltip: {
	  y: {
		formatter: function (val) {
		  return val + " JPL"
		}
	  }
	},
	fill: {
		type: 'solid',
		/* gradient: {
			shade: 'light',
			// gradientToColors: [ '#EEE'],
			shadeIntensity: 0.6,
			type: 'vertical',
			opacityFrom: 1,
			opacityTo: 1
		}, */
	},
	legend: {
	  position: 'bottom',
	  horizontalAlign: 'left',
	  offsetX: 20
	}
	};

	var chart = new ApexCharts(document.querySelector("#"+ele), options);
	chart.render();
}

function setupChart5(ele) {
	var options = {
	  colors: ["#27548A", "#006A67"],
	  series: [{
	  name: 'Coaching',
	  data: [2, 2, 2]
	}, {
	  name: 'Mentoring',
	  data: [4, 4, 4]
	}],
	  chart: {
	  type: 'bar',
	  height: 200,
	  stacked: true,
	  toolbar: false,
	},
	plotOptions: {
	  bar: {
		horizontal: true,
		dataLabels: {
		  total: {
			enabled: true,
			offsetX: 0,
			style: {
			  fontSize: '13px',
			  fontWeight: 900
			}
		  }
		}
	  },
	},
	stroke: {
	  width: 1,
	  colors: ['#fff']
	},
	xaxis: {
	  categories: ['PTPN 4','PTPN 1','PT SGN'],
	  max: 10,
	  labels: {
		show: false,
		formatter: function (val) {
		  return val + " JPL"
		}
	  }
	},
	yaxis: {
	  title: {
		text: undefined
	  },
	},
	tooltip: {
	  y: {
		formatter: function (val) {
		  return val + " JPL"
		}
	  }
	},
	fill: {
		type: 'solid',
		/* gradient: {
			shade: 'light',
			// gradientToColors: [ '#EEE'],
			shadeIntensity: 0.6,
			type: 'vertical',
			opacityFrom: 1,
			opacityTo: 1
		}, */
	},
	legend: {
	  position: 'bottom',
	  horizontalAlign: 'left',
	  offsetX: 20
	}
	};

	var chart = new ApexCharts(document.querySelector("#"+ele), options);
	chart.render();
}

$(document).ready(function() {
    setupChart('Capaian Total JPL','chart1');
	setupChart('Capaian JPL Formal Learning','chart2');
	setupChart('Capaian JPL Social Learning','chart3');
	setupChart('Capaian JPL Learning From Experiences','chart4');
	setupChart2('chart5');
	setupChart3('chart6');
	setupChart4('chart7');
	setupChart5('chart8');
});
</script>