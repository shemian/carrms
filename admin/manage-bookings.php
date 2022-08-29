<?php
session_start();
// error_reporting(0);
include('includes/config.php');
include('../Enums/BookingStatus.php');
if(strlen($_SESSION['alogin'])==0)
	{	
header('location:index.php');
}
else{


if(isset($_REQUEST['booking_id']))
	{
$booking_id=intval($_GET['booking_id']);




function confirmOrCancelBooking(Int $booking_id, Int $booking_status, $dbh){
	$is_booked=1;

	if($booking_status===BookingStatus::CANCELLED || $booking_status===BookingStatus::NOT_CONFIRMED || $booking_status===BookingStatus::RETURNED){
		$is_booked=0;
	}

	$sql = "SELECT VehicleId From tblbooking  WHERE  id=:booking_id";
	$query= $dbh -> prepare($sql);
	$query-> bindParam(':booking_id', $booking_id, PDO::PARAM_STR);
	$query-> execute();
	$result=$query->fetch(PDO::FETCH_OBJ);
	$VehicleId=$result->VehicleId;


	$sql = "UPDATE tblvehicles SET Is_booked=:is_booked WHERE  id=:VehicleId";
	$query = $dbh->prepare($sql);
	$query -> bindParam(':is_booked',$is_booked, PDO::PARAM_STR);
	$query-> bindParam(':VehicleId',$VehicleId, PDO::PARAM_STR);
	$query -> execute();


	$sql = "UPDATE tblbooking SET Status=:booking_status WHERE  id=:booking_id";
	$query = $dbh->prepare($sql);
	$query -> bindParam(':booking_status',$booking_status, PDO::PARAM_STR);
	$query-> bindParam(':booking_id',$booking_id, PDO::PARAM_STR);
	$query -> execute();
}
if(isset($_REQUEST['action']))
{
	$action = $_GET['action'];
	if($action==='confirm'){
		confirmOrCancelBooking($booking_id, BookingStatus::CONFIRMED, $dbh);
		$msg="Booking Successfully Confirmed";
	}else if($action==='return'){
		confirmOrCancelBooking($booking_id, BookingStatus::RETURNED, $dbh);
		$msg="Vehicle returned Successfully";
	}
	else{
		confirmOrCancelBooking($booking_id, BookingStatus::CANCELLED, $dbh);
		$msg="Booking Successfully Cancelled";
    }
}

}




 ?>

<!doctype html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="theme-color" content="#3e454c">
	
	<title>Car Rental Portal |Admin Manage testimonials   </title>

	<!-- Font awesome -->
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- Sandstone Bootstrap CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- Bootstrap Datatables -->
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<!-- Bootstrap social button library -->
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<!-- Bootstrap select -->
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<!-- Bootstrap file input -->
	<link rel="stylesheet" href="css/fileinput.min.css">
	<!-- Awesome Bootstrap checkbox -->
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<!-- Admin Stye -->
	<link rel="stylesheet" href="css/style.css">
  <style>
		.errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
		</style>

</head>

<body>
	<?php include('includes/header.php');?>

	<div class="ts-main-content">
		<?php include('includes/leftbar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">

				<div class="row">
					<div class="col-md-12">

						<h2 class="page-title">Manage Bookings</h2>

						<!-- Zero Configuration Table -->
						<div class="panel panel-default">
							<div class="panel-heading">Bookings Info</div>
							<div class="panel-body">
							<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
								<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
										<th>#</th>
											<th>Name</th>
											<th>Vehicle</th>
											<th>From Date</th>
											<th>To Date</th>
											<th>Message</th>
											<th>Status</th>
											<th>Posting date</th>
											<th>Action</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
										<th>#</th>
										<th>Name</th>
											<th>Vehicle</th>
											<th>From Date</th>
											<th>To Date</th>
											<th>Message</th>
											<th>Status</th>
											<th>Posting date</th>
											<th>Action</th>
										</tr>
									</tfoot>
									<tbody>

									<?php
                                    $sql = "SELECT tblusers.FullName,tblbrands.BrandName,tblvehicles.VehiclesTitle,tblbooking.FromDate,tblbooking.ToDate,tblbooking.message,tblbooking.VehicleId as vid,tblbooking.Status,tblbooking.PostingDate,tblbooking.id  from tblbooking join tblvehicles on tblvehicles.id=tblbooking.VehicleId join tblusers on tblusers.EmailId=tblbooking.userEmail join tblbrands on tblvehicles.VehiclesBrand=tblbrands.id  ";
                                    $query = $dbh -> prepare($sql);
                                    $query->execute();
                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt=1;
                                    if($query->rowCount() > 0)
                                       {
                                         foreach($results as $result)
                                         {				?>
										<tr>
											<td><?php echo htmlentities($cnt);?></td>
											<td><?php echo htmlentities($result->FullName);?></td>
											<td><a href="edit-vehicle.php?id=<?php echo htmlentities($result->vid);?>"><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></td>
											<td><?php echo htmlentities($result->FromDate);?></td>
											<td><?php echo htmlentities($result->ToDate);?></td>
											<td><?php echo htmlentities($result->message);?></td>
											<td>
												<?php
													if($result->Status==0)
													{
													echo htmlentities('Not Confirmed yet');
													} else if ($result->Status==1) {
													echo htmlentities('Confirmed');
													}
													else if ($result->Status==5) {
														echo htmlentities('Returned');
														}
													else{
														echo htmlentities('Cancelled');
													}
												?>
										    </td>

											<td><?php echo htmlentities($result->PostingDate);?></td>
											<td><a href="manage-bookings.php?booking_id=<?php echo htmlentities($result->id);?>&action=confirm" onclick="return confirm('Do you really want to Confirm this booking')"> Confirm</a> /
											<a href="manage-bookings.php?booking_id=<?php echo htmlentities($result->id);?>&action=cancel" onclick="return confirm('Do you really want to Cancel this Booking')"> Cancel</a>/	
											<a href="manage-bookings.php?booking_id=<?php echo htmlentities($result->id);?>&action=return" onclick="return confirm('Do you really want to The Return of  this Car')">Returned</a>
											</td>

										</tr>
										<?php $cnt=$cnt+1; }} ?>
							
									</tbody>
								</table>

						

							</div>
						</div>

					

					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- Loading Scripts -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
</body>
</html>
<?php } ?>
