<script>
	$(function() {
		"use strict";
		$(document).ready(function() {

			/**
			 * date range picker
			 */
			$('.daterange-cus_1').daterangepicker({
				autoUpdateInput: false,
			});

			$('.daterange-cus_1').on('apply.daterangepicker', function(ev, picker) {
				$(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
			});

			$('.daterange-cus_1').on('cancel.daterangepicker', function(ev, picker) {
				$(this).val('');
			});


			/**
			 * search within date range 		
			 */
			String.prototype.replaceAll = function(search, replacement) {
			    var target = this;
			    return target.replace(new RegExp(search, 'g'), replacement);
			};

			$(document).on('click', '#search_submit', function(event) {
				event.preventDefault();
				
				let date_value = $(".daterange-cus_1").val().split("-");

				if ($(".daterange-cus_1").val() != "") {

					let redirect_url = '<?php echo base_url('social_accounts/video_analytics/' . $channel_table_id. '/'. $video_table_id . '/'); ?>' + date_value[0].replaceAll('/', '-').trim() + '/' + date_value[1].replaceAll('/', '-').trim();
					// console.log(redirect_url);
					window.location.href = redirect_url;
				}
			});


			/**
			 * views
			 */
			let views_info = $("#views_info").val();
			views_info = JSON.parse(views_info);
			
			let views_info_step_size = (views_info.max_val + 2) / 5;

			let views_chart = document.getElementById("viewChart").getContext('2d');
			let views = new Chart(views_chart, {
			  type: 'line',
			  data: {
			    labels: views_info.date,
			    datasets: [{
			      label: '<?php echo $this->lang->line("Views"); ?>',
			      data: views_info.views,
			      borderWidth: 2,
			      backgroundColor: 'transparent',
			      borderColor: 'rgba(63,82,227,.8)',
			      borderWidth: 2.5,
			      pointBackgroundColor: 'transparent',
			      pointBorderColor: 'transparent',
			      pointRadius: 4
			    }
			    ]
			  },
			  options: {
			    legend: {
			      display: false
			    },
			    scales: {
			      yAxes: [{
			        gridLines: {
			          drawBorder: false,
			          color: '#f2f2f2',
			        },
			        ticks: {
			          beginAtZero: true,
			          stepSize: views_info_step_size
			        }
			      }],
			      xAxes: [{
			        gridLines: {
			          display: false
			        }
			      }]
			    },
			  }
			});		




			/**
			 * minute watch
			 */
			let estimated_minutes_watched = $("#estimated_minutes_watched").val();
			estimated_minutes_watched = JSON.parse(estimated_minutes_watched);
			
			let estimated_minutes_watched_step_size = (estimated_minutes_watched.max_val + 2) / 5;

			let estimated_minutes_watched_chart = document.getElementById("estimated_minutes_watchedChart").getContext('2d');
			new Chart(estimated_minutes_watched_chart, {
			  type: 'bar',
			  data: {
			    labels: estimated_minutes_watched.date,
			    datasets: [{
			      label: '<?php echo $this->lang->line("Mintue watched"); ?>',
			      data: estimated_minutes_watched.minute_watch,
			      borderWidth: 1,
			      backgroundColor: 'transparent',
			      borderColor: 'rgba(63,82,227,.8)',
			      pointBackgroundColor: 'transparent',
			      pointBorderColor: 'transparent',
			      pointRadius: 4
			    }
			    ]
			  },
			  options: {
			    legend: {
			      display: false
			    },
			    scales: {
			      yAxes: [{
			        gridLines: {
			          drawBorder: false,
			          color: '#f2f2f2',
			        },
			        ticks: {
			          beginAtZero: true,
			          stepSize: estimated_minutes_watched_step_size
			        }
			      }],
			      xAxes: [{
			        gridLines: {
			          display: false
			        }
			      }]
			    },
			  }
			});




			/**
			 * second watch
			 */
			let average_view_duration = $("#average_view_duration").val();
			average_view_duration = JSON.parse(average_view_duration);
			
			let average_view_duration_step_size = (average_view_duration.max_val + 2) / 5;

			let average_view_duration_chart = document.getElementById("average_view_durationChart").getContext('2d');
			new Chart(average_view_duration_chart, {
			  type: 'bar',
			  data: {
			    labels: average_view_duration.date,
			    datasets: [{
			      label: '<?php echo $this->lang->line("Second watched"); ?>',
			      data: average_view_duration.second_watch,
			      borderWidth: 1,
			      backgroundColor: 'transparent',
			      borderColor: 'rgba(254,86,83,.8)',
			      pointBackgroundColor: 'transparent',
			      pointBorderColor: 'transparent',
			      pointRadius: 4
			    }
			    ]
			  },
			  options: {
			    legend: {
			      display: false
			    },
			    scales: {
			      yAxes: [{
			        gridLines: {
			          drawBorder: false,
			          color: '#f2f2f2',
			        },
			        ticks: {
			          beginAtZero: true,
			          stepSize: average_view_duration_step_size
			        }
			      }],
			      xAxes: [{
			        gridLines: {
			          display: false
			        }
			      }]
			    },
			  }
			});




			/**
			 * subscriber vs unsubscriber
			 */
			let subscriber_vs_unsubscriber_info = $("#subscriber_vs_unsubscriber_info").val();
			subscriber_vs_unsubscriber_info = JSON.parse(subscriber_vs_unsubscriber_info);

			let subscriber_vs_unsubscriber_step_size = (subscriber_vs_unsubscriber_info.max_val + 2) / 5;


			let subscriberUnsubscriberChart = document.getElementById("subscriberUnsubscriberChart").getContext('2d');
			new Chart(subscriberUnsubscriberChart, {
			  type: 'line',
			  data: {
			    labels: subscriber_vs_unsubscriber_info.date,
			    datasets: [{
			      label: '<?php echo $this->lang->line("Subscriber"); ?>',
			      data: subscriber_vs_unsubscriber_info.subscriber,
			      borderWidth: 2,
			      backgroundColor: 'rgba(63,82,227,.8)',
			      borderWidth: 0,
			      borderColor: 'transparent',
			      pointBorderWidth: 0,
			      pointRadius: 3.5,
			      pointBackgroundColor: 'transparent',
			      pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
			    },{
			      label: '<?php echo $this->lang->line("Unsubscriber"); ?>',
			      data: subscriber_vs_unsubscriber_info.unsubscriber,
			      borderWidth: 2,
			      backgroundColor: 'rgba(254,86,83,.7)',
			      borderWidth: 0,
			      borderColor: 'transparent',
			      pointBorderWidth: 0 ,
			      pointRadius: 3.5,
			      pointBackgroundColor: 'transparent',
			      pointHoverBackgroundColor: 'rgba(254,86,83,.8)',
			    }]
			  },
			  options: {
			    legend: {
			      display: false
			    },
			    scales: {
			      yAxes: [{
			        gridLines: {
			          drawBorder: false,
			          color: '#f2f2f2',
			        },
			        ticks: {
			          beginAtZero: true,
			          stepSize: subscriber_vs_unsubscriber_step_size
			        }
			      }],
			      xAxes: [{
			        gridLines: {
			          display: false,
			          tickMarkLength: 15,
			        }
			      }]
			    },
			  }
			});




			/**
			 * Like vs Dislikes
			 */
			let likes_vs_dislikes_info = $("#likes_vs_dislikes_info").val();
			likes_vs_dislikes_info = JSON.parse(likes_vs_dislikes_info);

			let likes_vs_dislikes_step_size = (likes_vs_dislikes_info.max_val + 2) / 5;

			let likesDislikesChart = document.getElementById("likesDislikesChart").getContext('2d');
			new Chart(likesDislikesChart, {
			  type: 'bar',
			  data: {
			    labels: likes_vs_dislikes_info.date,
			    datasets: [{
			      label: '<?php echo $this->lang->line("Likes"); ?>',
			      data: likes_vs_dislikes_info.likes,
			      borderWidth: 2,
			      backgroundColor: 'rgba(254,86,83,.7)',
			      borderColor: 'rgba(254,86,83,.7)',
			      borderWidth: 2.5,
			      pointBackgroundColor: '#ffffff',
			      pointRadius: 4
			    },{
			      label: '<?php echo $this->lang->line("Dislikes"); ?>',
			      data: likes_vs_dislikes_info.dislikes,
			      borderWidth: 2,
			      backgroundColor: 'rgba(63,82,227,.8)',
			      borderColor: 'transparent',
			      borderWidth: 0,
			      pointBackgroundColor: '#999',
			      pointRadius: 4
			    }]
			  },
			  options: {
			    legend: {
			      display: false
			    },
			    scales: {
			      yAxes: [{
			        gridLines: {
			          drawBorder: false,
			          color: '#f2f2f2',
			        },
			        ticks: {
			          beginAtZero: true,
			          stepSize: likes_vs_dislikes_step_size
			        }
			      }],
			      xAxes: [{
			        gridLines: {
			          display: false
			        }
			      }]
			    },
			  }
			});





			/**
			 * comments
			 */
			let comments_info = $("#comments_info").val();
			comments_info = JSON.parse(comments_info);
			
			let comments_step_size = (comments_info.max_val + 2) / 5;

			let commentsChart = document.getElementById("commentsChart").getContext('2d');
			new Chart(commentsChart, {
			  type: 'bar',
			  data: {
			    labels: comments_info.date,
			    datasets: [{
			      label: '<?php echo $this->lang->line("Comments"); ?>',
			      data: comments_info.comments,
			      borderWidth: 2,
			      backgroundColor: 'transparent',
			      borderColor: 'rgba(63,82,227,.8)',
			      pointBackgroundColor: 'transparent',
			      pointBorderColor: 'transparent',
			      pointRadius: 4
			    }
			    ]
			  },
			  options: {
			    legend: {
			      display: false
			    },
			    scales: {
			      yAxes: [{
			        gridLines: {
			          drawBorder: false,
			          color: '#f2f2f2',
			        },
			        ticks: {
			          beginAtZero: true,
			          stepSize: comments_step_size
			        }
			      }],
			      xAxes: [{
			        gridLines: {
			          display: false
			        }
			      }]
			    },
			  }
			});




			/**
			 * shares
			 */
			let shares_info = $("#shares_info").val();
			shares_info = JSON.parse(shares_info);
			
			let shares_step_size = (shares_info.max_val + 2) / 5;

			let sharesChart = document.getElementById("sharesChart").getContext('2d');
			new Chart(sharesChart, {
			  type: 'bar',
			  data: {
			    labels: shares_info.date,
			    datasets: [{
			      label: '<?php echo $this->lang->line("shares"); ?>',
			      data: shares_info.shares,
			      borderWidth: 2,
			      backgroundColor: 'transparent',
			      borderColor: 'rgba(254,86,83,.8)',
			      pointBackgroundColor: 'transparent',
			      pointBorderColor: 'transparent',
			      pointRadius: 4
			    }
			    ]
			  },
			  options: {
			    legend: {
			      display: false
			    },
			    scales: {
			      yAxes: [{
			        gridLines: {
			          drawBorder: false,
			          color: '#f2f2f2',
			        },
			        ticks: {
			          beginAtZero: true,
			          stepSize: shares_step_size
			        }
			      }],
			      xAxes: [{
			        gridLines: {
			          display: false
			        }
			      }]
			    },
			  }
			});



			/**
			 * device report
			 */
			let device_type_info = $("#device_type_info").val();
			device_type_info = JSON.parse(device_type_info);

			var deviceTypeChart = document.getElementById("deviceTypeChart").getContext('2d');
			new Chart(deviceTypeChart, {
			  type: 'pie',
			  data: {
			    datasets: [{
			      data: device_type_info.value,
			      backgroundColor: device_type_info.color,
			      label: 'Dataset 1'
			    }],
			    labels: device_type_info.label,
			  },
			  options: {
			    responsive: true,
			    legend: {
			      // display: false,
			      position: 'bottom',
			    },
			  }
			});


			/**
			 * operating report
			 */
			let operating_system_info = $("#operating_system_info").val();
			operating_system_info = JSON.parse(operating_system_info);

			var operatingSystemChart = document.getElementById("operatingSystemChart").getContext('2d');
			new Chart(operatingSystemChart, {
			  type: 'pie',
			  data: {
			    datasets: [{
			      data: operating_system_info.value,
			      backgroundColor: operating_system_info.color,
			      label: 'Dataset 1'
			    }],
			    labels: operating_system_info.label,
			  },
			  options: {
			    responsive: true,
			    legend: {
			      // display: false,
			      position: 'bottom',
			    },
			  }
			});




			/**
			 * annotation impressions info
			 */
			let annotation_impressions_info = $("#annotation_impressions_info").val();
			annotation_impressions_info = JSON.parse(annotation_impressions_info);
			
			let annotation_impressions_step_size = (annotation_impressions_info.max_val + 2) / 5;

			let annotationImpressionsChart = document.getElementById("annotationImpressionsChart").getContext('2d');
			new Chart(annotationImpressionsChart, {
			  type: 'line',
			  data: {
			    labels: annotation_impressions_info.date,
			    datasets: [{
			      label: '<?php echo $this->lang->line("Views"); ?>',
			      data: annotation_impressions_info.annotation_impressions,
			      borderWidth: 2,
			      backgroundColor: 'transparent',
			      borderColor: 'rgba(63,82,227,.8)',
			      borderWidth: 2.5,
			      pointBackgroundColor: 'transparent',
			      pointBorderColor: 'transparent',
			      pointRadius: 4
			    }
			    ]
			  },
			  options: {
			    legend: {
			      display: false
			    },
			    scales: {
			      yAxes: [{
			        gridLines: {
			          drawBorder: false,
			          color: '#f2f2f2',
			        },
			        ticks: {
			          beginAtZero: true,
			          stepSize: annotation_impressions_step_size
			        }
			      }],
			      xAxes: [{
			        gridLines: {
			          display: false
			        }
			      }]
			    },
			  }
			});	








			/**
			 * annotation close vs click impressions
			 */
			let annotation_close_click_impressions_info = $("#annotation_close_click_impressions_info").val();
			annotation_close_click_impressions_info = JSON.parse(annotation_close_click_impressions_info);

			let annotation_close_click_impressions_step_size = (annotation_close_click_impressions_info.max_val + 2) / 5;


			let annotationCloseClickImpressionsChart = document.getElementById("annotationCloseClickImpressionsChart").getContext('2d');
			new Chart(annotationCloseClickImpressionsChart, {
			  type: 'line',
			  data: {
			    labels: annotation_close_click_impressions_info.date,
			    datasets: [{
			      label: '<?php echo $this->lang->line("Click Impression"); ?>',
			      data: annotation_close_click_impressions_info.click_impression,
			      borderWidth: 2,
			      backgroundColor: 'rgba(63,82,227,.8)',
			      borderWidth: 0,
			      borderColor: 'transparent',
			      pointBorderWidth: 0,
			      pointRadius: 3.5,
			      pointBackgroundColor: 'transparent',
			      pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
			    },{
			      label: '<?php echo $this->lang->line("Close Impression"); ?>',
			      data: annotation_close_click_impressions_info.close_impression,
			      borderWidth: 2,
			      backgroundColor: 'rgba(254,86,83,.7)',
			      borderWidth: 0,
			      borderColor: 'transparent',
			      pointBorderWidth: 0 ,
			      pointRadius: 3.5,
			      pointBackgroundColor: 'transparent',
			      pointHoverBackgroundColor: 'rgba(254,86,83,.8)',
			    }]
			  },
			  options: {
			    legend: {
			      display: false
			    },
			    scales: {
			      yAxes: [{
			        gridLines: {
			          drawBorder: false,
			          color: '#f2f2f2',
			        },
			        ticks: {
			          beginAtZero: true,
			          stepSize: annotation_close_click_impressions_step_size
			        }
			      }],
			      xAxes: [{
			        gridLines: {
			          display: false,
			          tickMarkLength: 15,
			        }
			      }]
			    },
			  }
			});





			/**
			 * annotation clicks vs closes
			 */
			let annotation_clicks_closes_info = $("#annotation_clicks_closes_info").val();
			annotation_clicks_closes_info = JSON.parse(annotation_clicks_closes_info);

			let annotation_clicks_closes_step_size = (annotation_clicks_closes_info.max_val + 2) / 5;

			let annotationClicksClosesChart = document.getElementById("annotationClicksClosesChart").getContext('2d');
			new Chart(annotationClicksClosesChart, {
			  type: 'bar',
			  data: {
			    labels: annotation_clicks_closes_info.date,
			    datasets: [{
			      label: '<?php echo $this->lang->line("Annotation Click"); ?>',
			      data: annotation_clicks_closes_info.annotation_click,
			      borderWidth: 2,
			      backgroundColor: 'rgba(254,86,83,.7)',
			      borderColor: 'rgba(254,86,83,.7)',
			      borderWidth: 2.5,
			      pointBackgroundColor: '#ffffff',
			      pointRadius: 4
			    },{
			      label: '<?php echo $this->lang->line("Annotation Close"); ?>',
			      data: annotation_clicks_closes_info.annotation_close,
			      borderWidth: 2,
			      backgroundColor: 'rgba(63,82,227,.8)',
			      borderColor: 'transparent',
			      borderWidth: 0,
			      pointBackgroundColor: '#999',
			      pointRadius: 4
			    }]
			  },
			  options: {
			    legend: {
			      display: false
			    },
			    scales: {
			      yAxes: [{
			        gridLines: {
			          drawBorder: false,
			          color: '#f2f2f2',
			        },
			        ticks: {
			          beginAtZero: true,
			          stepSize: annotation_clicks_closes_step_size
			        }
			      }],
			      xAxes: [{
			        gridLines: {
			          display: false
			        }
			      }]
			    },
			  }
			});






			/**
			 * retention
			 */
			let retention_info = $("#retention_info").val();
			retention_info = JSON.parse(retention_info);

			let retention_step_size = (retention_info.max_val + 2) / 5;


			let retentionChart = document.getElementById("retentionChart").getContext('2d');
			new Chart(retentionChart, {
			  type: 'line',
			  data: {
			    labels: retention_info.video_length,
			    datasets: [{
			      label: '<?php echo $this->lang->line("Retention"); ?>',
			      data: retention_info.audience,
			      borderWidth: 2,
			      backgroundColor: 'rgba(63,82,227,.8)',
			      borderWidth: 0,
			      borderColor: 'transparent',
			      pointBorderWidth: 0,
			      pointRadius: 3.5,
			      pointBackgroundColor: 'transparent',
			      pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
			    }]
			  },
			  options: {
			    legend: {
			      display: false
			    },
			    scales: {
			      yAxes: [{
			        gridLines: {
			          drawBorder: false,
			          color: '#f2f2f2',
			        },
			        ticks: {
			          beginAtZero: true,
			          stepSize: retention_step_size
			        }
			      }],
			      xAxes: [{
			        gridLines: {
			          display: false,
			          tickMarkLength: 15,
			        }
			      }]
			    },
			  }
			});


		});
	});
</script>