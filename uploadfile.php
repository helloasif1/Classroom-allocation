<?php
include_once 'dbConfig.php';
require_once "Classes/PHPExcel.php";
$message = '';
// $query = "SELECT * FROM test";
// $result = mysqli_query($con, $query);
// $count = mysqli_num_rows($result);



$tmpPath='';
$lastRow='';
$columnCount='';
$columncountNumber='';
$isUploaded=false;
$workSheet='';
$columnNames= array();
$tableName="";

if(isset($_POST['upload_table'])){
	$tableName = $_POST['product_table_name'];
	$fileName = $_FILES['product_table']['name'];
	$fileBaseName =$tableName;
	$dest_path= $_FILES['product_table'][tmp_name];
	$tmpPath=$dest_path;

	$reader= PHPExcel_IOFactory::createReaderForFile($dest_path);
	$excel_Obj = $reader->load($dest_path);

	
	$workSheet = $excel_Obj->getActiveSheet();
	$lastRow = $workSheet->getHighestRow();
	$columnCount = $workSheet->getHighestDataColumn();
	$columncountNumber = PHPExcel_Cell::columnIndexFromString($columnCount);
	
	createTable($con,$workSheet,$lastRow,$columnCount,$columncountNumber,$columnNames,$fileName,$fileBaseName);
	addDataIntoTable($con,$workSheet,$lastRow,$columnCount,$columncountNumber,$columnNames,$fileName,$fileBaseName);

}



function createTable($con,$workSheet,$lastRow,$columnCount,$columncountNumber,$columnNames,$fileName,$fileBaseName){
	
	//Getting the exact row value from the Excel file
	$getStartRowNo=-1;
	for($row=1;$row<$lastRow;$row++){
		$allColumnHasData=false;
		for($col=1;$col<=$columncountNumber;$col++){
			$data = $workSheet->getCell(PHPExcel_Cell::stringFromColumnIndex($col).$row)->getValue();
			if (!empty($data)) {
				$allColumnHasData=true;
			  }
		}
		if($allColumnHasData){$getStartRowNo=$row;  break;}
	}
    $Row = $getStartRowNo;

	//Storing all the columns' name from excel file to make a table in Database
	for($col=0;$col<=$columncountNumber;$col++){
		$data = $workSheet->getCell(PHPExcel_Cell::stringFromColumnIndex($col).$getStartRowNo)->getValue();
		array_push($columnNames,$data);
	}

	//Creating a dynamic sql query based on the excel file's column data
	$subSql=$fileBaseName."(No int auto_increment primary key, ";
	for ($i =0; $i < (count($columnNames)-1); $i++) {
		$subSql.=  $columnNames[$i]." VARCHAR(255) NOT NULL";
		if($i < (count($columnNames)-2))$subSql.= ", ";
	}
	$subSql.=" )";

	
	//Main creat table query
	 $createTableSql ="CREATE TABLE IF NOT EXISTS ".$subSql;
	// echo "<h2>".$createTableSql."</h2>";

	
	// Start quering to create table in Database
	if ($con->multi_query($createTableSql) === TRUE) {
		//header("location: dataoverview.php?".$tableName." created successfully");
		echo "<p>Table created</p>";
	} else {
		echo "Error creating table: " . $con->error;
	}
}

function addDataIntoTable($con,$workSheet,$lastRow,$columnCount,$columncountNumber,$columnNames,$fileName,$fileBaseName){
	
	$getStartRowNo=-1;
	for($row=1;$row<$lastRow;$row++){
		$allColumnHasData=false;
		for($col=1;$col<=$columncountNumber;$col++){
			$data = $workSheet->getCell(PHPExcel_Cell::stringFromColumnIndex($col).$row)->getValue();
			if (!empty($data)) {
				$allColumnHasData=true;
			  }
		}
		if($allColumnHasData){$getStartRowNo=$row;  break;}
	}
    $Row = $getStartRowNo;

	for($col=0;$col<=$columncountNumber;$col++){
		$data = $workSheet->getCell(PHPExcel_Cell::stringFromColumnIndex($col).$getStartRowNo)->getValue();
		array_push($columnNames,$data);
	}

	//echo "<p> getStartRowNo :".$getStartRowNo."</p>";
	$isAllDataADDED=false;
	for($row=$getStartRowNo+1;$row<$lastRow;$row++){
			
		$tableDataSQL = "INSERT INTO ". $fileBaseName." ( ";
		for ($col =0; $col < (count($columnNames)-1); $col++) {
			//echo "<h2>".$columnNames[$i]."</h2>";
			$tableDataSQL.=  $columnNames[$col];
			if($col < (count($columnNames)-2))$tableDataSQL.= ", ";
		}

		$tableDataSQL.=") VALUES (";

		//Getting each row data and storing in to an array
		$columnData =array();
		for($col=0;$col<=$columncountNumber;$col++){
			$data = $workSheet->getCell(PHPExcel_Cell::stringFromColumnIndex($col).$row)->getValue();
			array_push($columnData,$data);
		}

		//Adding data in the sql string for query
		for ($col =0; $col < (count($columnData)-1); $col++) 
		{
			//echo "<p>".$columnData[$col]."</p>";
			$tableDataSQL.=  "'".$columnData[$col]."'";
			if($col < (count($columnData)-2)) $tableDataSQL.= ", ";
		}
		$tableDataSQL.=")";

		if ($con->multi_query($tableDataSQL) === TRUE) {
		
			$isAllDataADDED=true;

		} 
		//echo "<p>".$tableDataSQL."</p>";
		
	}

	header("location: dataoverview.php?data uploaded in successfully");
	
	
}

$con->close();
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
			        <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
			      </li>
			    </ul>
		  </div>
		</nav>
		<div class="homcontainer container">


		<p></p>
		  <div class="container mt-5">
			<h2 text-align="center">Upload Excel file to Create Table & to Upload Data :</h2>
			<!-- <p class="text-warning">Warning:: First upload excel file for creating Table. Then upload again upload excel file for inserting data into table.</p> -->
			<p></p>
			<form action="uploadfile.php" method="post" enctype='multipart/form-data' >
				<p><label>Enter Table Name:</label>
				<input type="text" name="product_table_name"  required/></p>	
				<p></p>
				<p><label>Please Select excel File For Creating Table</label>
				<input type="file" name="product_table" class="btn btn-warning" required/></p>
				<!-- <p><label>Please Select excel File For Uploading Data</label>
				<input type="file" name="product_file" class="btn btn-warning" /></p> -->
				<p></p>
				<input type="submit" name="upload_table" class="btn btn-success" value="Upload" />
			</form>
			<p></p>
			
			<p></p>
			<!-- <form method="POST" action="graph.php">
					<input type="submit" name="graph" class="btn btn-primary" value="Graph" />
			</form>
			<p></p> -->
			
			<!-- <h3 text-align="center">IUB Student Admission Session Report 2013-2019</h3>
			
			<p></p>
			<div class="container-fluid table-responsive">
				<table class="table table-bordered table-striped">
				
					<?php
						// if($count>0){
						// 	echo "<tr>";
						// 	echo '
						// 	 <tr>
						// 	 <td>'."N0.".'</td>
						// 	 <td>'."SCHOOL TITLE".'</td>
						// 	 <td>'."COFFER COURSE_ID".'</td>
						// 	 <td>'."COFFERED WITH".'</td>
						// 	 <td>'."SECTION".'</td>
						// 	 <td>'."CREDIT HOUR".'</td>
						// 	 <td>'."CAPACITY".'</td>
						// 	 <td>'."ENROLLED".'</td>
						// 	 <td>'."ROOM ID".'</td>
						// 	 <td>'."ROOM CAPACITY".'</td>
						// 	 <td>'."BLOCKED".'</td>
						// 	 <td>'."COURSE NAME".'</td>
						// 	 <td>'."FACULTY FULL_NAME".'</td>
						// 	 <td>'."START TIME".'</td>
						// 	 <td>'."END TIME".'</td>
						// 	 <td>'."ST MW".'</td>
						// 	 </tr>
						// 	 ';
						//  echo "</tr>";
						// }
					  
					?>
			
				<?php	
					    // $cnt=0;
						// if($count>0){
						// 	while($row = mysqli_fetch_array($result))
						// 	{
						// 		if($cnt>=1)
						// 		{
						// 			echo '
						// 			<tr>
						// 			<td>'.$row["No"].'</td>
						// 			<td>'.$row["SCHOOL_TITLE"].'</td>
						// 			<td>'.$row["COFFER_COURSE_ID"].'</td>
						// 			<td>'.$row["COFFERED_WITH"].'</td>
						// 			<td>'.$row["SECTION"].'</td>
						// 			<td>'.$row["CREDIT_HOUR"].'</td>
						// 			<td>'.$row["CAPACITY"].'</td>
						// 			<td>'.$row["ENROLLED"].'</td>
						// 			<td>'.$row["ROOM_ID"].'</td>
						// 			<td>'.$row["ROOM_CAPACITY"].'</td>
						// 			<td>'.$row["BLOCKED"].'</td>
						// 			<td>'.$row["COURSE_NAME"].'</td>
						// 			<td>'.$row["FACULTY_FULL_NAME"].'</td>
						// 			<td>'.$row["START_TIME"].'</td>
						// 			<td>'.$row["END_TIME"].'</td>
						// 			<td>'.$row["ST_MW"].'</td>
						// 			</tr>
						// 			';	
						// 		}
						// 		$cnt=$cnt+1;
							
						// 	}
						// }
						
					
				?>
				</table>
			</div>
		  </div>
			
			
	</div>













	<footer class>
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