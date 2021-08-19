<?php
session_start();
include "../auth_check.php";
include "../../includes/database.php";

if (isset($_GET['id'])) {
	$id = $_GET['id'];


	$view_sql   = "SELECT * FROM clients WHERE id='$id'";
	$view_query = mysqli_query($connect,$view_sql);
	$view_row   = mysqli_fetch_assoc($view_query);
	
	
}else{
  header("location: client-list.php");
}


if (isset($_POST['change-client-btn'])) {
	$client_new_photo = $_FILES['client_photo'];
	$client_explode   = explode('.',$client_new_photo['name']);
	$extension        = strtolower(end($client_explode));
	$allowed          = array('jpg','png','jpeg','gif');

	if ($client_new_photo['name'] == null) {
		$_SESSION['error']  = "client file does not found!!";

	}elseif (!in_array($extension,$allowed)) {
		$_SESSION['error']  = "client file format does not support!!!";

	}elseif ($client_new_photo['size'] > (1024*1024)) {
		$_SESSION['error']  = "client file is too large!!!";

	}else{
		if (unlink('../../uploads/clients-photos/'.$view_row['client_photo'])) {
			$new_file_name = $view_row['id'].'.'.$extension;
			$destination   = '../../uploads/clients-photos/'.$new_file_name;
			if (move_uploaded_file($client_new_photo['tmp_name'],$destination)) {
				$update_client = "UPDATE clients SET client_photo='$new_file_name' WHERE id='$id'";
				$update_query  = mysqli_query($connect,$update_client);

				if ($update_query) {
					$_SESSION['success']  = "Good. client photo change has been successfully";
					header("location: client-view.php");

				}else{
					$_SESSION['error'] = "Opps!!!, client photo deos not change.please try again";

				}
			}else{
				$_SESSION['error'] = "your new FIle does not uploaded!!!";

			}
		}else{
			$_SESSION['error']  = "Your old client file does not deleted!!!";
		}
	}
}

$title = "client view page";

?>

<?php require "../../includes/layouts/header.php"; ?>

<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="card-box">
					<div class="p-2 text-center font-weight-bold"><?= ucwords($view_row['client_name']); ?></div>
					<div class="card-body">


						<?php if(isset($_SESSION['success'])): ?>
                                            <script type="text/javascript">
                                              Swal.fire({
                                                icon: 'success',
                                                html:'<p><b class="text-success"><i class="fa fa-success"></i></b>&nbsp&nbsp&nbsp<span class="text-success"><?php echo $_SESSION['success']; ?></span></p>'
                                              });
                                            </script>

                        <?php endif; unset($_SESSION['success']);  ?>

                        
                        <?php if(isset($_SESSION['error'])): ?>
                                            <script type="text/javascript">
                                              Swal.fire({
                                                icon: 'error',
                                                html:'<p><b class="text-danger"><i class="fa fa-warning"></i></b>&nbsp&nbsp&nbsp<span class="text-warning"><?php echo $_SESSION['error']; ?></span></p>'
                                              });
                                            </script>

                        <?php endif; unset($_SESSION['error']);  ?>


						<table class="table table-bordered table-sm">
							<tr>
								<th>ID</th>
								<td><?= $view_row['id']; ?></td>
							</tr>
							<tr>
								<th>client Photo</th>
								<td>
									<div class="card">
										<img src="../../uploads/clients-photos/<?= $view_row['client_photo'];?>" class="card-img img-fluid" style="width: 100vh;height: 100vh;">
										<div class="card-img-overlay">
											<button class="btn btn-success" id="change-client-click">Change client Photos</button>
											<div id="change-client-photo" class="mt-2" style="display: none;">
												<form action="" method="post" enctype="multipart/form-data">
													<div class="form-group w-25">
														<div class="input-group">
															<div class="input-group-append">
																<span class="input-group-text"><i class="fa fa-photo"></i></span>
															</div>
															<input type="file" name="client_photo" class="form-control form-control-file">
														</div>
													</div>
													<div class="form-group">
														<input type="submit" name="change-client-btn" class="btn btn-pink w-25">
													</div>
											    </form>
											</div>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<th>client Occupation</th>
								<td><?= $view_row['client_occupation']; ?></td>
							</tr>
							<tr>
								<th>client Comments</th>
								<td><?= $view_row['client_comments']; ?></td>
							</tr>
							<tr>
								<th>Created Date</th>
								<td><?= $view_row['created_at']; ?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require "../../includes/layouts/footer.php"; ?>