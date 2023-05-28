<?php
	include_once 'dbConfig.php';

   //COFFERED_WITH CAPACITY

    $dataPoints1 = array();
    $dataPoints2 = array();
    $dataPoints3 = array();
    $countOfSections=array();
    $currentYearData=array();
    $lastYearData=array();
    $diffData=array();
    $semesterWiseWiseTotalClassSize=array();
    $semester="";
    $year="";
    $listYear=array();
    $totalSecInUni="";
    $result="";
    $count = 0;
   
	$uniqueCapacity = "";

   
    //Semester list
    $semesterList = array("Summer","Autumn","Spring");

    $sqlforYearList = "SELECT YEAR FROM all_revenue ";
    $yearResult = $con->query($sqlforYearList);
    $tempYear=array();
    while($row = mysqli_fetch_array($yearResult)){
      array_push($tempYear, $row['YEAR']);
       
   }
   //Getting unique year from list
   $listYear = array_unique($tempYear);    
   //print_r($listYear);

    if (isset($_POST["insert"])) {
        $year = $_POST["selectyear"];
       
          //ALL data   
          $allData = "SELECT * FROM all_revenue WHERE Year='$year'";
          $result =$con->query($allData); 
          $count = mysqli_num_rows($result);

          $listSchools = array("CSE","EEE","PhySci","SETS");

           foreach($listSchools as $school){

              $SchoolData = "SELECT $school, SemesterNo FROM all_revenue WHERE Year='$year'";
              $result2 =$con->query($SchoolData); 

              $cd=array();
              while($row = mysqli_fetch_array($result2)){
                array_push($cd,$row[$school]);
                array_push($dataPoints1,array("label"=>$school."_".$row['SemesterNo'], "y"=>$row[$school]));
              }
              array_push($currentYearData,$cd);

              $ly=$year-1;
              $SchoolData2 = "SELECT $school, SemesterNo FROM all_revenue WHERE Year='$ly'";
              $result3 =$con->query($SchoolData2); 
              $ld=array();
              while($row = mysqli_fetch_array($result3)){
                array_push($ld,$row[$school]);
                array_push($dataPoints2,array("label"=>$school."_".$row['SemesterNo'], "y"=>$row[$school]));
              }
              array_push($lastYearData,$ld);
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
		        text:  "Revenue generated in each school"
		    },
		    axisY: {
		        title: "Grand Total Difference"
		    },
		    data: [{
		        type: "line",
            indexLabel: "{y}",
				    indexLabelPlacement: "outside",
		        dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
		    },
            {
		        type: "line",
            indexLabel: "{y}",
				    indexLabelPlacement: "outside",
		        dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
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
			        <a class="nav-link" href="point8.php" > Revenue SETS<span class="sr-only">(current)</span></a>
			      </li>
			    </ul>
		  </div>
		</nav>
	
		<div class="homcontainer1 container">
            <h2 class="text-primary text-center">  Revenue generated in SETS<?php if($count>0){ echo ", ".$year;}  ?></h2>
            <p></p>
			<div class="row">
				<div class="col-lg-2 col-md-2 col-sm-2"></div>
				<div class="col-lg-8 col-md-8 col-sm-8">
					<div class="card p-5">
					  <form action="point8.php" method="POST">
							<p></p>
                            <label>Select Year</label>
						  	<select class="form-control form-control-lg" name="selectyear" required>
							  <option value=""></option>
                              <?php
                              
                                foreach($listYear as $y){
                                    echo '<option value='.$y.' >'.$y.'</option>';
                                }
                                
                              ?>
							  
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
					
                             <th>'."Semester".'</th>
							               <th>'."CSE".'</th>
                             <th>'."EEE".'</th>
							                <th>'."PhySci".'</th>
                             <th>'."SETS GRAND TOTAL".'</th>
                             <th>'."DIFFERENCE WITH ".($year-1).'</th>
							 ';
						 echo "</tr>";
						}
					  
					?>
			
				<?php	
						if($count>0){
                            //print_r($currentYearData);
                            $lastYearGT=array();
                            //Calculating last year's grandtotal's difference percentage
                            $lYear =$year-1;
                            if($lYear>=$listYear[0]){
                                    $lastYear = "SELECT * FROM all_revenue WHERE Year='$lYear'";
                                    $lastYearResult =$con->query($lastYear); 
                                    $count = mysqli_num_rows($lastYearResult);

                                    while($row = mysqli_fetch_array($lastYearResult)){
                                        array_push($lastYearGT, $row['SETS']); 
                                    }
                                  
                                    while($row = mysqli_fetch_array($result)){
                                  
                                        $sem="";
                                        $lastGT=0;
                                        if($row['SemesterNo']==1) {$sem= $semesterList[0]; $lastGT=$lastYearGT[0]; }
                                        else if($row['SemesterNo']==2){ $sem= $semesterList[1]; $lastGT=$lastYearGT[1];}
                                        else{$sem= $semesterList[2];$lastGT=$lastYearGT[2];}
        
                                       $diffPerccentage = round((($row['SETS']-$lastGT)/$row['SETS']),2);
                                        //round(520.34345, 2);
                                        echo '
                                        <tr>
                                        <td>'. $sem.'</td>
                                        <td>'. $row['CSE'].'</td>
                                        <td>'. $row['EEE'].'</td>
                                        <td>'. $row['PhySci'].'</td>
                                        <td>'. $row['SETS'].'</td>
                                        <td>'. $diffPerccentage.'%</td>
                                        </tr>
                                        
                                        ';
                                    }
                                   
                                   
                                     
                                 
                            }
              
						}
		
				?>
				</table>
			</div>
           
		</div>
    <p></p>
    <div id="chartContainer1" style="width: 100%; height: 215px;display: inline-block; margin: 10px;"></div> 
    

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