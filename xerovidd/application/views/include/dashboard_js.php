<script>
  $(function() {
    "use strict";
    $(document).ready(function() {

      <?php if (count($rank_stat) > 0) : ?>

        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: [<?php echo $rank_stat['date'] ?>],
            datasets: [
              <?php $i = 0; ?>
              <?php foreach ($rank_stat as $key => $single_stat): ?>
                
                <?php $i++; if ($key == 'date' || $key == 'colors' || $key == 'step_size') { continue; } ?>

                {
                  label: '<?php echo $keyword_id_name_maping[$key]; ?>',
                  data: [<?php echo $single_stat['youtube_position']; ?>],
                  borderWidth: 2,
                  backgroundColor: '<?php echo $rank_stat['colors'][$i]; ?>',
                  borderWidth: 0,
                  borderColor: 'transparent',
                  pointBorderWidth: 0,
                  pointRadius: 3.5,
                  pointBackgroundColor: 'transparent',
                  pointHoverBackgroundColor: '<?php echo $rank_stat['colors'][$i]; ?>',
                },
              <?php endforeach ?>
            ]
          },
          options: {
            legend: {
              display: false
            },
            scales: {
              yAxes: [{
                gridLines: {
                  // display: false,
                  drawBorder: false,
                  color: '#f2f2f2',
                },
                ticks: {
                  beginAtZero: true,
                  stepSize: <?php echo $rank_stat['step_size']; ?>,
                  callback: function(value, index, values) {
                    return value;
                  }
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
      <?php endif; ?>
        
      var balance_chart = document.getElementById("comment_reply-chart").getContext('2d');

      var balance_chart_bg_color = balance_chart.createLinearGradient(0, 0, 0, 70);
      balance_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
      balance_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

      var myChart = new Chart(balance_chart, {
        type: 'line',
        data: {
          labels: [<?php echo implode(',', $comment_reply_stat['date']); ?>],
          datasets: [{
            label: '<?php echo $this->lang->line("Comment Reply"); ?>',
            data: [<?php echo implode(',', $comment_reply_stat['total']); ?>],
            backgroundColor: balance_chart_bg_color,
            borderWidth: 3,
            borderColor: 'rgba(63,82,227,1)',
            pointBorderWidth: 0,
            pointBorderColor: 'transparent',
            pointRadius: 3,
            pointBackgroundColor: 'transparent',
            pointHoverBackgroundColor: 'rgba(63,82,227,1)',
          }]
        },
        options: {
          layout: {
            padding: {
              bottom: -1,
              left: -1
            }
          },
          legend: {
            display: false
          },
          scales: {
            yAxes: [{
              gridLines: {
                display: false,
                drawBorder: false,
              },
              ticks: {
                beginAtZero: true,
                display: false
              }
            }],
            xAxes: [{
              gridLines: {
                drawBorder: false,
                display: false,
              },
              ticks: {
                display: false
              }
            }]
          },
        }
      });



      var sales_chart = document.getElementById("subscription-chart").getContext('2d');

      var sales_chart_bg_color = sales_chart.createLinearGradient(0, 0, 0, 80);
      balance_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
      balance_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

      var myChart = new Chart(sales_chart, {
        type: 'line',
        data: {
          labels: [<?php echo implode(',', $subscription_stat['date']); ?>],
          datasets: [{
            label: '<?php echo $this->lang->line("Subscription"); ?>',
            data: [<?php echo implode(',', $subscription_stat['total']); ?>],
            borderWidth: 2,
            backgroundColor: balance_chart_bg_color,
            borderWidth: 3,
            borderColor: 'rgba(63,82,227,1)',
            pointBorderWidth: 0,
            pointBorderColor: 'transparent',
            pointRadius: 3,
            pointBackgroundColor: 'transparent',
            pointHoverBackgroundColor: 'rgba(63,82,227,1)',
          }]
        },
        options: {
          layout: {
            padding: {
              bottom: -1,
              left: -1
            }
          },
          legend: {
            display: false
          },
          scales: {
            yAxes: [{
              gridLines: {
                display: false,
                drawBorder: false,
              },
              ticks: {
                beginAtZero: true,
                display: false
              }
            }],
            xAxes: [{
              gridLines: {
                drawBorder: false,
                display: false,
              },
              ticks: {
                display: false
              }
            }]
          },
        }
      });

    });
  });
</script>