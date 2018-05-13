<?php
$data1 = $_GET["data"];
$len  = $_GET["len"];
$query = json_decode($data1, true);

$serverName = "localhost\MSSQLSERVER01";
$connectionOptions = array(
    "Database" => "library",
    "Uid" => "sa",
    "PWD" => "zijin19990321",
    "CharacterSet"=>"UTF-8"
);
$conn = sqlsrv_connect($serverName, $connectionOptions);
if($conn == false) {
    die("LOGINERROR");
}


for ($i = 0; $i < count($query); $i++) {
    $flag = 0;
    $data = json_decode($query[$i], true);
    if($i == 0) {
        if ( sqlsrv_begin_transaction( $conn ) === false ) {  
            die( print_r( sqlsrv_errors(), true ));  
            }  
    }
    $psql = "SELECT count(*) FROM BOOK WHERE bno = (?)";
    $param = array(trim($data[0]));
    $getResults= sqlsrv_query($conn, $psql, $param);
    $row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC);
    foreach ($row as $key=>$element) {
        if ($element == '1') {
            $psql = "UPDATE BOOK SET total = total + (?) WHERE bno = (?)";
            $param = array((int)trim($data[7]),trim($data[0]));
            $getResults= sqlsrv_query($conn, $psql, $param);
            if ($getResults == false) {
                sqlsrv_rollback( $conn );  
                die("UPERROR");
            }
            $psql = "UPDATE BOOK SET stock = stock + (?) WHERE bno = (?)";
            $param = array((int)trim($data[7]),trim($data[0]));
            $getResults= sqlsrv_query($conn, $psql, $param);
            if ($getResults == false) {
                sqlsrv_rollback( $conn );  
                die("UPERROR");
            }
            $flag = 1;
            break;
        } 
        else {
            break;
        }
    }

    if ($flag == 0) {
        $tsql = "insert into book values(";
        $tsql .= ("'".trim($data[0])."',");
        $tsql .= ("'".trim($data[1])."',");
        $tsql .= ("'".trim($data[2])."',");
        $tsql .= ("'".trim($data[3])."',");
        $tsql .= ("".trim($data[4]).",");
        $tsql .= ("'".trim($data[5])."',");
        $tsql .= ("".trim($data[6]).",");
        $tsql .= ("".trim($data[7]).",");
        $tsql .= ("".trim($data[7]).");");



        $getResults= sqlsrv_query($conn, $tsql);
        if ($getResults == false) {
            sqlsrv_rollback( $conn );  
            die("INERROR");
        }
    }
}
sqlsrv_commit( $conn );  

echo("SUCCESS");

sqlsrv_free_stmt($getResults);
sqlsrv_close($conn);



function FormatErrors( $errors )
{
    /* Display errors. */
    echo "Error information: \n";

    foreach ( $errors as $error )
    {
        echo "  SQLSTATE: ".$error['SQLSTATE']."\n";
        echo "  Code: ".$error['code']."\n";
        echo "  Message: ".$error['message']."\n";
    }
}





