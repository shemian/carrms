<?php
function updateBookingStatus(Int $booking_id, Int $booking_status, $dbh){
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

?>