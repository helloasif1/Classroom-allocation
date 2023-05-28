<?php
	include_once 'dbConfig.php';

   //COFFERED_WITH CAPACITY

    $dataPoints1 = array();
    $dataPoints2 = array();
    $dataPoints3 = array();
    $countOfSections=array();
    $semesterWiseWiseTotalClassSize=array();
    $semester="";
    $year="";
    $totalSecInUni="";
    $result="";
    $count = "";
   
	$uniqueCapacity = "";
       
        if (isset($_POST["insert"])) {
		    $semester = $_POST["selectsemester"];
            $year = $_POST["selectyear"];
        
			$schools = array('SLASS','SELS','SETS','SPPH',"SBE");

			$sql = "SELECT  SUM(SECTION) from class_size";
			$result = $con->query($sql);
			$count = mysqli_num_rows($result);
			while($row = mysqli_fetch_array($result)){
					$totalSecInUni=$row['SUM(SECTION)'];
			}
		
			//ALL Room Capacity   
			$allCapacity = "SELECT CAPACITY FROM class_size WHERE  Semester='$semester' AND Year='$year'";
			$result2 = $con->query($allCapacity);
			
			
			//Storing unique capacity numbers for quering
			$capacityArray=array();
			foreach($result2 as $row){
				array_push($capacityArray,$row['CAPACITY']);
			}
			$uniqueCapacity=array_unique($capacityArray);
			

			foreach($uniqueCapacity as $cap){
				
				$allSections = "SELECT COUNT(COFFERED_WITH) FROM class_size WHERE CAPACITY='$cap' AND Semester='$semester' AND Year='$year'";
				$result1 = $con->query($allSections);
			
				$totalSections=0;
				foreach($result1 as $row){
					$totalSections=$row['COUNT(COFFERED_WITH)'];
				}
				//echo $cap." totalSections : ".$totalSections;
				array_push($countOfSections, array($cap,$totalSections));
				array_push($dataPoints1, array("label"=>$cap, "y"=>$totalSections));
			}
			
	    }

//  SBE SELS SETS SPPH

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
<script>
    window.onload = function () {
		
		var chart = new CanvasJS.Chart("chartContainer1", {
			animationEnabled: true,
			//exportEnabled: true,
		    title: {
		        text:  "Classroom requirement as per course offering"
		    },
		    axisY: {
		        title: "Percentage of Sections"
		    },
		    data: [{
		        type: "pie",
				yValueFormatString: "#,##0.00\"%\"",
				indexLabel: "{label} ({y})",
		        dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
		    }]
		});
		chart.render();
   
  
}
</script>


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
			      <li class="nav-item ">
			        <a class="nav-link" href="index.php">Home</a>
			      </li>
			      <li class="nav-item active">
			        <a class="nav-link" href="point1.php" >Classroom Requirement <span class="sr-only">(current)</span></a>
			      </li>
			    </ul>
		  </div>
		</nav>
	
		<div class="homcontainer1 container">
            <h2 class="text-primary text-center">Classroom requirement as per course offering <?php if(Count($countOfSections)>0){ echo ", ".$semester." - ".$year;}  ?></h2>
            <p></p>
			<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-2"></div>
				<div class="col-lg-8 col-md-8 col-sm-8">
					<div class="card p-5">
					  <form action="point1.php" method="POST">
					  		<label>Select Semester</label>
						  	<select class="form-control form-control-lg" name="selectsemester" required>
                              <option value=""></option>
							  <option value="summer">1. Summer</option>
							  <option value="autumn">2. Autumn</option>
							  <option value="spring">3. Spring</option>
							</select>
							<p></p>
                            <label>Select Year</label>
						  	<select class="form-control form-control-lg" name="selectyear" required>
							  <option value=""></option>
							  <option value="2021">2021</option>
							</select>
                            <p></p>
							<input type="submit" name="insert" class="btn btn-info" value="Submit" />
					  </form>
                      
					</div>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2"></div>	
            </div>
            <p></p>
            <div class="container-fluid table-responsive">
				<table class="table table-bordered table-striped">
				
					<?php
						if($count>0){
							echo "<tr>";
							echo '
					
                             <th>'."Number of Students Capacity".'</th>
							 <th>'."Total Number of Sections".'</th>
							
							 ';
						 echo "</tr>";
						}
					  
					?>
			
				<?php	
					    $cnt=0;
						if($count>0){

                            for($i=0;$i<Count($countOfSections);$i++){
                                echo '
                                <tr>
                                <td>'.$countOfSections[$i][0].'</td>
                                <td>'.$countOfSections[$i][1].'</td>
                                </tr>
                                
                                ';	
                            }
                         
						}
		
				?>
				</table>
			</div>
           
		</div>
        <p></p>
        <div id="chartContainer1" style="width: 100%; height: 300px;display: inline-block; margin: 10px;"></div> 
      
       

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
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>