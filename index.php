<?php
	if (isset($_POST["insert"])) {
		$option = $_POST["select"]; 

		if($option == "point 1"){
			header("location: point1.php");
		}else if($option == "point 2"){
			header("location: point2.php");
		}else if($option == "point 3"){
			header("location: point3.php");
		}else if($option == "point 4"){
			header("location: point4.php");
		}else if($option == "point 5"){
			header("location: point5.php");
		}else if($option == "point 6"){
			header("location: point6.php");
		}
		else if($option == "point 7"){
			header("location: point7.php");
		}
		else if($option == "point 8"){
			header("location: point8.php");
		}

	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Data Analysis</title>
	<!-- Bootstrap link -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<!-- Custom css-->
	<link rel="stylesheet" type="text/css" href="./css/stylesheet.css">
	<!-- Googlefont link -->
	<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">

</head>
<body>
	<div class="container-fluid">
		<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
		  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
		    <span class="navbar-toggler-icon"></span>
		  </button>
		  <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
			   <a class="navbar-brand" href="#" target="_blank">Data Analysis</a>
			   
			    <ul class="navitem navbar-nav ml-auto mt-2 mt-lg-0">
			      <li class="nav-item active">
			        <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
			      </li>   
			    </ul>
		  </div>
		</nav>

		<div class="homcontainer1 container">

			<div class="row">

				<div class="col-lg-4 col-md-4 col-sm-4"></div>
				
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="card" style="width: 18rem;">
					  
					  <div class="card-body ">
					    <h5 class="card-title">Data Overview</h5>
					    <p class="card-text text-primary">Insert CSV file to Upload Data</p>
					    <a href="uploadfile.php" class="btn btn-info">ADD</a>
					  </div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4"></div>
				
				
			</div>
			<p></p>
			<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-2"></div>
					<div class="col-lg-8 col-md-8 col-sm-8">
						<div class="card p-5">
						
							<form action="index.php" method="POST">
									<label>Select Points:</label>
									<select class="form-control form-control-lg" name="select" required>
										<option value="point 1">1. Classroom requirement as per coursr offering</option>
										<option value="point 2">2. Enrolment wise distribution among the schools</option>
										<option value="point 3">3. Usage of the resources</option>
										<option value="point 4">4. IUB available resources</option>
										<option value="point 5">5. Availability and courses offering comparison</option>
										<option value="point 6">6. Breakdown of courses offered in each school</option>
										<option value="point 7">7. Revenue generated in each school</option>
										<option value="point 8">8. Revenue generated in SETS</option>
									
									</select>
									<br>
									<input type="submit" name="insert" class="btn btn-success" value="Submit" />
							</form>
						</div>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2"></div>
					
				</div>


			</div>


			
		</div>












	<footer>
		<div class="container-fluid">

			<div class="row ">
				<div class="col-4">
					
				</div>
				<div class="footercontent col-8">
					<p>Copyright © 2019 - Dept. of CSE - All Rights Reserved.</p>
					
				</div>
				<div class="col-4">
					
				</div>
			</div>
		</div>
	</footer>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>