<?php
	$conn = mysqli_connect("localhost", "root", "", "sor_term3");

	
	if($conn === false){
		die("ERROR: Could not connect. "
			. mysqli_connect_error());
	}

	$productName=$_POST['productName'];
	$productDesc=$_POST['productDesc'];
	$productImg=$_POST['productImg'];
$productPrice=$_POST['productPrice'];


	
	$sql_query= "INSERT INTO products VALUES (DEFAULT, '$productName','$productDesc','$productImg','$productPrice')";
	
	if (mysqli_query($conn, $sql_query)) 
{
	header('Location: arraySearch1.php');
} 
else
{
   echo "Error: " . $sql . "" . mysqli_error($conn);
}
mysqli_close($conn);

?>