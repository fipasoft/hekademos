<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<?php
try{
$serverName = "148.202.65.84\Verex";
//$serverName = "HP16001703618\VEREX";
$connectionOptions = array("Database"=>"Director");

/* Connect using Windows Authentication. */
$conn = sqlsrv_connect( $serverName, $connectionOptions);

/*$tsql = "SELECT TOP 30 UserNumber, UserNumberText, PanelLocalDT, CAST(PanelLocalDT AS DateTime) AS Fecha, CardNumber
FROM         ViewEvents
WHERE     CardNumber='-196092876'";
*/


$tsql="UPDATE AFxUser SET UserInfo3='206556124' WHERE CardNumber='-196092876'";
echo $tsql."<br/> " ;

if( $conn )
{
     echo "Connection established ".$serverName."<br/>";

/* Execute the query. */
$stmt = sqlsrv_query($conn, $tsql);
if( $stmt === false )
{
     echo "Error in statement execution.</br>";
     die( print_r( sqlsrv_errors(), true));
}else{
    echo "Exito 206556124";
}
return; 
echo "<table> "; 
while($row = sqlsrv_fetch_array($stmt)){
echo "<tr> ";
echo "<td> ";
echo $row["UserNumber"];
echo "</td> ";
echo "<td> ";
echo $row["UserNumberText"];
echo "</td> ";
echo "<td> ";
echo $row["PanelLocalDT"];
echo "</td> ";
echo "<td> ";

echo $row["Fecha"]->format("d")."/".$row["Fecha"]->format("m")."/".$row["Fecha"]->format("Y") ;
echo "</td> ";
echo "<td> ";
echo $row["CardNumber"];
echo "</td> ";
echo "</tr> ";
}

echo "</table> ";


/* Close the connection. */
sqlsrv_close( $conn);


}
else
{
     echo "Connection could not be established.\n";
     die( print_r( sqlsrv_errors(), true));
}

         
}catch(Exception $e){
echo $e;
}

?>
</body>
</html>
