<?php

//action.php

$connect = new PDO("sqlite:alldata.db");

$received_data = json_decode(file_get_contents("php://input"));

$data = array();

if($received_data->query != '')
{
	$query = "
	SELECT * FROM Products
	WHERE product_name LIKE '%".$received_data->query."%' 
	OR image LIKE '%".$received_data->query."%' 
	ORDER BY id DESC
	";
}
else
{
	$query = "
	SELECT * FROM Products
	ORDER BY id DESC
	";
}

$statement = $connect->prepare($query);

$statement->execute();

while($row = $statement->fetch(PDO::FETCH_ASSOC))
{
	$data[] = $row;
}

echo json_encode($data);

?>  