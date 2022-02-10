<?php
include("config.php");
if (!loggedin()) {
	header("Location: login.php");
	exit();
}
if (($_SESSION['role'] != 'admin')) {
	header("Location: error.php");
	exit();
}

$per_page_record = 20;
$page = (isset($_GET['page']))? $_GET['page'] : 1;
$start_from = ($page-1) * $per_page_record;
$visitors = visitors_info($start_from, $per_page_record);
$visitors_count = $visitors['count'];
?>
<!DOCTYPE html>
<html>
<?php include("include/heads.php"); ?>
<style>
    .pagination {
        display: inline-block;
    }

    .pagination a {
        color: black;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
    }

    .pagination a.active {
        background-color: #4CAF50;
        color: white;
    }

    .pagination a:hover:not(.active) {background-color: #ddd;}
    .info-box {
        -webkit-text-size-adjust: 100%;
        -webkit-tap-highlight-color: rgba(0,0,0,0);
        font-weight: 400;
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        font-size: 14px;
        line-height: 1.42857143;
        color: #333;
        box-sizing: border-box;
        display: block;
        min-height: 90px;
        background: #fff;
        width: 100%;
        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        border-radius: 2px;
        margin-bottom: 15px;
    }
    .info-box-icon {
        border-top-left-radius: 2px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 2px;
        display: block;
        float: left;
        height: 90px;
        width: 90px;
        text-align: center;
        font-size: 45px;
        line-height: 90px;
        background: rgba(0,0,0,0.2);
    }
    .info-box-content {
        padding: 5px 10px;
        margin-left: 90px;
    }
    .info-box-text {
        text-transform: uppercase;
        display: block;
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .info-box-number {
        display: block;
        font-weight: bold;
        font-size: 18px;
    }
    .bg-aqua {
        background-color: #00c0ef !important;
    }
    .bg-green {
        background-color: #00a65a !important;
    }
    .bg-yellow {
        background-color: #f39c12 !important;
    }
    .bg-aqua, .bg-green, .bg-yellow {
        color: #fff !important;
    }
    .bg-white {
        background-color: #fff !important;
    }
    .box {
        position: relative;
        border-radius: 3px;
        background: #ffffff;
        border-top: 3px solid #d2d6de;
        margin-bottom: 20px;
        width: 100%;
        box-shadow: 0 1px 1px rgb(0 0 0 / 10%);
    }
    .box.box-info {
        border-top-color: #00c0ef;
    }
    .box-header.with-border {
        border-bottom: 1px solid #f4f4f4;
    }
    .box-header {
        color: #444;
        display: block;
        padding: 10px;
        position: relative;
    }
    .box-header>.fa, .box-header>.glyphicon, .box-header>.ion, .box-header .box-title {
        display: inline-block;
        font-size: 18px;
        margin: 0;
        line-height: 1;
    }
    .box-header>.box-tools {
        position: absolute;
        right: 10px;
        top: 5px;
    }
    .box-body {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
        padding: 10px;
    }
    .box-footer {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
        border-top: 1px solid #f4f4f4;
        padding: 10px;
        background-color: #fff;
    }
    .box.box-primary {
        border-top-color: #3c8dbc;
    }
</style>
<body class="fixed-left">
<div id="wrapper">
    <!-- Top Bar Start -->
	<?php include("include/topbar.php"); ?>
    <!-- Top Bar End -->
    <div class="container-fluid p-0">

        <!-- Left Sidebar Start -->
		<?php include("include/leftsidebar.php"); ?>
        <!-- Left Sidebar End -->
        <div class="col-xs-12 col-lg-10">

            <!-- Start right Content here -->
            <div class="deleteData"></div>
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">
                        <!-- Page-Title -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="page-title"> <?php echo $languages[$lang]["analytics"];   ?></h4>
                                <ol class="breadcrumb">
                                    <li><a href="analytics.php?lang=<?php echo $lang; ?>"> <?php echo $languages[$lang]["analytics"];   ?> </a></li>
                                    <li class="active">  <?php echo $languages[$lang]["analytics"];   ?> </li>
                                </ol>
                            </div>
                        </div>
                        <div class="row">
                        <input type="hidden" value="<?php echo $lang;   ?>" id="familyLang">
                        <div class="panel p-30">
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-aqua"><i class="fa fa-line-chart"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?= $languages[$lang]["pageviews"] ?></span>
                                            <span id="total_visitors" class="info-box-number"><?= $visitors_count ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-aqua"><i class="fa fa-line-chart"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?= $languages[$lang]["unique_pageviews"] ?></span>
                                            <span id="unique_visitors" class="info-box-number"><?= $visitors['unique_count'] ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-green"><i class="fa fa-users" aria-hidden="true"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?= $languages[$lang]["total_users"] ?></span>
                                            <span id="total_posts" class="info-box-number"><?= total_users() ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-yellow"><i class="fa fa-users"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?= $languages[$lang]["users_added_last_month"] ?></span>
                                            <span id="total_subscribers" class="info-box-number"><?= users_added_last_month() ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-yellow"><i class="fa fa-tree" aria-hidden="true"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?= $languages[$lang]["total_families"] ?></span>
                                            <span id="total_user" class="info-box-number"><?= total_families() ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-green"><i class="fa fa-tree" aria-hidden="true"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?= $languages[$lang]["families_added_last_month"] ?> </span>
                                            <span id="total_authors" class="info-box-number"><?= families_added_last_month() ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix visible-sm-block"></div>
                            </div>
                            <div class="panel-body">
                                <hr>
                                <div class="">
                                    <table class="table table-striped" id="">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php  echo $languages[$lang]["ip"];    ?></th>
                                            <th colspan="2"><?php  echo $languages[$lang]["number_of_visits"];    ?></th>
                                            <th><?php echo $languages[$lang]["city"];   ?></th>
                                            <th><?php  echo $languages[$lang]["country"];   ?></th>
                                            <th><?php  echo $languages[$lang]["page"];   ?></th>
                                            <th><?php  echo $languages[$lang]["device"];   ?></th>
                                            <th><?php  echo $languages[$lang]["date"];   ?></th>
                                            <th><?php  echo $languages[$lang]["leaves_at"];   ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
										<?php
										$x = $start_from;
										foreach($visitors['data'] as $visitor){
											?>
                                            <tr>
                                                <td><?= $x ?></td>
                                                <td><?= $visitor['ip'] ?></td>
                                                <td colspan="2"><?= $visitor['number_of_visits'] ?></td>
                                                <td><?= $visitor['city'] ?></td>
                                                <td><?= $visitor['country'] ?></td>
                                                <td><?= $visitor['page'] ?></td>
                                                <td><?= $visitor['device'] ?></td>
                                                <td><?= $visitor['created_at'] ?></td>
                                                <td><?= $visitor['leave_at'] ?></td>
                                            </tr>
											<?php
											$x++;
										}
										?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="pagination">
									<?php
									$total_pages = ceil($visitors_count / $per_page_record);
									$pagLink = "";

									if($page>=2){
										echo "<a href='analytics.php?page=".($page-1)."'>Prev</a>";
									}

									for ($i=1; $i<=$total_pages; $i++) {
										if ($i == $page) {
											$pagLink .= "<a class = 'active' href='analytics.php?page="
													.$i."'>".$i." </a>";
										}
                                        elseif($i < $page+4)  {
											$pagLink .= "<a href='analytics.php?page=".$i."'>   
                                                                                     ".$i." </a>";
										} elseif($i == $total_pages-1)  {
											$pagLink .= "<a>...</a>";
											$pagLink .= "<a href='analytics.php?page=".$i."'>  
                                                                                     ".$i." </a>";
										} elseif($i == $total_pages)  {
											$pagLink .= "<a href='analytics.php?page=".$i."'>  
                                                                                     ".$i." </a>";
										}
									};
									echo $pagLink;

									if($page<$total_pages){
										echo "<a href='analytics.php?page=".($page+1)."'>Next</a>";
									}
									?>

                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-12 col-12">
                            <!-- LEFT SIDE -->
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Site views</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse" status="0"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body" style="display: none;">
                                    <canvas id="siteViews" width="400" height="400"></canvas>
                                </div>
                                <div class="box-footer clearfix">
                                </div>
                            </div>
                        </div>
                        <!-- RIGHT SIDE -->
                        <div class="col-md-6 col-xs-12 col-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Unique site views</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse" status="0"><i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body" style="display: none;">
                                    <canvas id="unique_siteViews" width="400" height="400"></canvas>
                                </div>
                                <div class="box-footer text-center">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-12 col-12">
                            <!-- LEFT SIDE -->
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Users added all year</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse" status="0"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body" style="display: none;">
                                    <canvas id="users_all_year" width="400" height="400"></canvas>
                                </div>
                                <div class="box-footer clearfix">
                                </div>
                            </div>
                        </div>
                        <!-- RIGHT SIDE -->
                        <div class="col-md-6 col-xs-12 col-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Families added all year</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse" status="0"><i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body" style="display: none;">
                                    <canvas id="family_all_year" width="400" height="400"></canvas>
                                </div>
                                <div class="box-footer text-center">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-12 col-12">
                            <!-- LEFT SIDE -->
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Sessions' Countries</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse" status="0"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body" style="display: none;">
                                    <canvas id="sessions_countries" width="400" height="400"></canvas>
                                </div>
                                <div class="box-footer clearfix">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
				<?php include("include/footer_text.php"); ?>
            </div>
            <!-- end Modal -->
            <!-- End Right content here -->

        </div>
    </div>
</div>
<!-- END wrapper -->
<?php include("include/footer.php"); ?>
<script type="text/javascript">
    $(document).ready(function () {

        $('body').on('click', '.btn-box-tool', function(){
            let status = $(this).attr('status');
            if(status == 1){
                $(this).attr('status', 0);
                $(this).closest('.box-header').siblings('.box-body').hide(1000);
                $(this).find('i').removeClass('fa-minus');
                $(this).find('i').addClass('fa-plus');
            } else {
                $(this).attr('status', 1);
                $(this).closest('.box-header').siblings('.box-body').show(1000);
                $(this).find('i').removeClass('fa-plus');
                $(this).find('i').addClass('fa-minus');
            }
        });

        $.ajax({
            type: 'GET',
            url: 'functions/family_functions.php',
            data: {
                get_info_for_charts: 1
            },
            dataType: 'JSON',
            cache: false
        }).done(function(res){
            // console.log(res);
            // site views
            let visitors_this_year = res.visitors_this_year;
            let visitors_this_year_keys = [];
            let visitors_this_year_values = [];
            for (var property in visitors_this_year) {

                if (visitors_this_year.hasOwnProperty(property)) {
                    visitors_this_year_keys.push(property);
                    visitors_this_year_values.push(visitors_this_year[property]);
                }

            }

            new Chart(document.getElementById("siteViews"), {
                type: 'line',
                data: {
                    labels: visitors_this_year_keys,
                    datasets: [
                        {
                            data: visitors_this_year_values,
                            label: "Site Views",
                            borderColor: "#3e95cd",
                            fill: false
                        }
                    ]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Site Views'
                    }
                }
            });

            // unique site views
            let unique_visitors_this_year = res.unique_visitors_this_year;
            let unique_visitors_this_year_keys = [];
            let unique_visitors_this_year_values = [];
            for (var property in unique_visitors_this_year) {

                if (unique_visitors_this_year.hasOwnProperty(property)) {
                    unique_visitors_this_year_keys.push(property);
                    unique_visitors_this_year_values.push(unique_visitors_this_year[property]);
                }

            }

            new Chart(document.getElementById("unique_siteViews"), {
                type: 'line',
                data: {
                    labels: unique_visitors_this_year_keys,
                    datasets: [
                        {
                            data: unique_visitors_this_year_values,
                            label: "Unique Site Views",
                            borderColor: "#3e95cd",
                            fill: false
                        }
                    ]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Unique Site Views'
                    }
                }
            });

            // users all year
            let users_all_year = res.users_all_year;
            let users_all_year_keys = [];
            let users_all_year_values = [];
            for (var property in users_all_year) {

                if (users_all_year.hasOwnProperty(property)) {
                    users_all_year_keys.push(property);
                    users_all_year_values.push(users_all_year[property]);
                }

            }

            new Chart(document.getElementById("users_all_year"), {
                type: 'line',
                data: {
                    labels: users_all_year_keys,
                    datasets: [
                        {
                            data: users_all_year_values,
                            label: "Users all year",
                            borderColor: "#3e95cd",
                            fill: false
                        }
                    ]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Users all year'
                    }
                }
            });

            // family all year
            let family_all_year = res.family_all_year;
            let family_all_year_keys = [];
            let family_all_year_values = [];
            for (var property in family_all_year) {

                if (family_all_year.hasOwnProperty(property)) {
                    family_all_year_keys.push(property);
                    family_all_year_values.push(family_all_year[property]);
                }

            }

            new Chart(document.getElementById("family_all_year"), {
                type: 'line',
                data: {
                    labels: family_all_year_keys,
                    datasets: [
                        {
                            data: family_all_year_values,
                            label: "Families all year",
                            borderColor: "#3e95cd",
                            fill: false
                        }
                    ]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Families all year'
                    }
                }
            });

            // sessions' countries
            let sessions_countries = res.sessions_countries;
            let sessions_countries_keys = [];
            let sessions_countries_values = [];
            for (var property in sessions_countries) {

                if (sessions_countries.hasOwnProperty(property)) {
                    sessions_countries_keys.push(property);
                    sessions_countries_values.push(sessions_countries[property]);
                }

            }

            new Chart(document.getElementById("sessions_countries"), {
                type: 'line',
                data: {
                    labels: sessions_countries_keys,
                    datasets: [
                        {
                            data: sessions_countries_values,
                            label: "Sessions' Countries",
                            borderColor: "#3e95cd",
                            fill: false
                        }
                    ]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Sessions\' Countries'
                    }
                }
            });

        });

    });



</script>
<script>
    $(document).ready(function () {
        $("#cssmenu ul>li").removeClass("active");
        $("#item33").addClass("active");
    });
</script>
</body>
</html>
