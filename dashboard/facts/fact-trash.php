<?php
 session_start();
 include "../auth_check.php"; 
 ?>
  <?php  include "../../includes/database.php";
if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$trash_sql   = "UPDATE facts SET deleted='1' WHERE id='$id'";
	$trash_query =  mysqli_query($connect,$trash_sql);
	if ($trash_query) {

		$store = "Fact moved trash has been Successfully <a href='fact-restore.php?id=$id' class='btn btn-info'>Undo</a>";
		
		
		$_SESSION['success'] = $store;
		header("location: fact-list.php");

	}else{
		$_SESSION['error'] = "Ooops !! fact does not trush.please try again";
		header("location: fact-list.php");
	}
}else {
    header("location: fact-list.php");
 }
?>