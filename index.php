<?php
$title = "Dashboard";
include('./includes/header.php');
?>
<?php
session_start();
include_once('./config/dbConfig.php');
if(!isset($_SESSION['username'])){
    header("location:login.php");
}
?>
<?php
$os = shell_exec('cat /etc/os-release | grep PRETTY_NAME | cut -d\'=\' -f2');

?>

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <?php include('./includes/sidebar.php')?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <?php include('./includes/navbar.php'); ?>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                </div>

                <!-- Content Row -->

                <div class="row">

                    <!-- Area Chart -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div
                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">CPU Usage</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-line">
                                <canvas id="myLineChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">System Information</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                       <p>Host Name:&nbsp;<span class="font-weight-bold"><?php echo gethostname()?></span></p>
                       <p>IP Address:&nbsp;<span class="font-weight-bold"><?php echo $_SERVER['REMOTE_ADDR']?></span></p>
                       <p>Operating System:&nbsp;<span class="font-weight-bold"><?php echo $os?></span></p>
                       <p>PHP Version:&nbsp;<span class="font-weight-bold"><?php echo phpversion()?></span></p>
                   </div>
               </div>
           </div>
       </div>
       <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Disk Usage</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i> Used Space
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i> Free Space
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Memory Usage</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <div class="chart-line">
                <canvas id="memory"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>


<script src="vendor/jquery/jquery.min.js"></script>
<script>
    function displayDiskMetrics(){
        $.ajax({
            url:'getDiskMetrics.php',
            method:'GET',
            dataType:'JSON',
            success:function(response){
                var ctx = document.getElementById("myPieChart");
                var myPieChart = new Chart(ctx, {
                  type: 'doughnut',
                  data: {
                    labels: ["Used Space","Free Space"],
                    datasets: [{
                      data: [response?.used,response?.free],
                      backgroundColor: ['#4e73df', '#1cc88a'],
                      hoverBackgroundColor: ['#2e59d9', '#17a673'],
                      hoverBorderColor: "rgba(234, 236, 244, 1)",
                  }],
              },
              options: {
                maintainAspectRatio: false,
                tooltips: {
                  backgroundColor: "rgb(255,255,255)",
                  bodyFontColor: "#858796",
                  borderColor: '#dddfeb',
                  borderWidth: 1,
                  xPadding: 15,
                  yPadding: 15,
                  displayColors: false,
                  caretPadding: 10,
              },
              legend: {
                  display: false
              },
              cutoutPercentage: 80,
          },
      });
            }

        })
    }


    let cpuUsageChart,memoryUsageChart;
    const cpuData = {
        labels: [],
        datasets: [{
            label:'CPU Usage',
            data: [],
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            fill:false,
        }]
    };
    const memoryData = {
        labels: [],
        datasets: [{
            label:'Memory Usage',
            data: [],
            borderColor: 'rgba(192, 75, 75, 1)',
            backgroundColor: 'rgba(192, 75, 75, 0.2)',
            fill:false
        }]
    };

    function fetchUsage() {
        $.ajax({
            url: 'get_metrics.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const now = new Date();
                const timeLabel = now.getHours() + ":" + now.getMinutes();

                cpuData.labels.push(timeLabel);
                cpuData.datasets[0].data.push(data.cpu_usage);
                memoryData.labels.push(timeLabel);
                memoryData.datasets[0].data.push(data.memory_usage);

                if (cpuData.labels.length > 20) {
                    cpuData.labels.shift();
                    cpuData.datasets[0].data.shift();
                    memoryData.labels.shift();
                    memoryData.datasets[0].data.shift();
                }

                cpuUsageChart.update();
                memoryUsageChart.update();
            },
            error: function(error) {
                console.error("Error fetching System Metrics:", error);
            }
        });
    }


    $(document).ready(function(){
       const ctx = document.getElementById('myLineChart');
       const ctx1 = document.getElementById('memory');
       cpuUsageChart = new Chart(ctx, {
        type: 'line',
        data: cpuData,
        options: {
            responsive: true,
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'second'
                    }
                },
                y: {
                    beginAtZero: true,
                    suggestedMax: 1
                }
            },
            plugins:{
                legend:{
                    display:false
                },
                title:{
                    display:false
                }
            }
        }
    });
       memoryUsageChart = new Chart(ctx1, {
        type: 'line',
        data: memoryData,
        options: {
            responsive: true,
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'second'
                    }
                },
                y: {
                    beginAtZero: true,
                    suggestedMax: 1
                }
            },
            plugins:{
                legend:{
                    display:false
                },
                title:{
                    display:false
                }
            }
        }
    });

       setInterval(fetchUsage, 10000);
       displayDiskMetrics();
   })
</script>



<!-- Logout Modal-->
<?php include('./includes/logoutModal.php')?>
<?php
include('./includes/footer.php');
?>