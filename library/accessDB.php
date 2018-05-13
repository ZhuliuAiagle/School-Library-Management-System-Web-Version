<?php
// 和数据库进行交互

// 从前端获取请求

$postype = $_GET["PostType"];
$condition_p = $_GET["condition"]; 
$table = $_GET["info"];

$usr = $_GET["usr"];
$pas = $_GET["pas"];

$condition = json_decode($condition_p,true);
$info      = json_decode($table, true);



$serverName = "localhost\MSSQLSERVER01";
$connectionOptions = array(
    "Database" => "library",
    "Uid" => "sa",
    "PWD" => "zijin19990321",
    "CharacterSet"=>"UTF-8"
);
$conn = sqlsrv_connect($serverName, $connectionOptions); 

if($conn == false) 
{
    die("LOGINERROR");
}


// 连接数据库

switch($postype)
{    
    case 0:
        break;
    case 1: 
        selectBookCondition($conn, $condition);
        break;
    case 2:
        insertBook($conn, $info);
        break;
    case 3:
        insertCard($conn, $info);
        break;
    case 4:
        insertBorrow($conn, $info);
        break;
    case 5:
        deleteBorrow($conn, $info);
        break;
    case 6:
        selectBorrow($conn, $condition);
        break;
    case 7:
        verifyAdmin($conn, $usr, $pas);
        break;
    case 8:
        deletecard($conn, $info);
        break;
    case 9:
        UserSelect($conn, $condition);
        break;
    case 10:
        verifyUser($conn, $usr, $pas);
    default:
        die($postype."invalid input!");
}

//////////////////////////////////////////////////////////////
// 验证登录模块 ///////////////////////////////////////////////
//////////////////////////////////////////////////////////////

function verifyUser($conn, $usr, $pas){
    if($conn == false){
        die("LOGINERROR");
    }
    $tsql = "SELECT count(*) from card where cno = (?) and pas = (?)";
    $param = array($usr, $pas);
    $state = sqlsrv_prepare($conn, $tsql, $param);
    if($state)
        $getResults= sqlsrv_query($conn, $tsql, $param);
    if ($getResults == FALSE){
        die("LOGINERROR");
        echo($tsql."\n");
        die(FormatErrors(sqlsrv_errors()));
    }
    $row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC);
    foreach($row as $key=>$element){
        if($element == '1'){
            die('SUCCESS');
        }
    }
    die("LOGINERROR"); 
}

function verifyAdmin($conn, $usr, $pas)
{
    if($conn == false){
        die("LOGINERROR");
    }
    $tsql = "SELECT count(*) from admin where usr = (?) and pas = (?)";
    $param = array($usr, $pas);
    $state = sqlsrv_prepare($conn, $tsql, $param);
    if($state)
        $getResults= sqlsrv_query($conn, $tsql, $param);
    if ($getResults == FALSE){
        die("LOGINERROR");
        echo($tsql."\n");
        die(FormatErrors(sqlsrv_errors()));
    }
    $row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC);
    foreach($row as $key=>$element){
        if($element == '1'){
            die('SUCCESS');
        }
    }
    die("LOGINERROR");
}

//////////////////////////////////////////////////////////////
// 查询书籍模块 ///////////////////////////////////////////////
//////////////////////////////////////////////////////////////

function selectBookCondition($conn, $condition, $priceUpper = 1000000, $priceLower = -1000000)
{
    if($conn == false) {
        die("LOGINERROR");
    }
    $param = array();
    $tsql = "SELECT * FROM BOOK WHERE ";
    if (array_key_exists("bno", $condition)) {
        $tsql .= ("bno = (?) AND ");
        array_push($param, $condition["bno"]);
    }
    if (array_key_exists("category", $condition)) {
        $tsql .= ("category like (?) AND ");
        array_push($param, '%'.$condition["category"].'%');
    }
    if (array_key_exists("title", $condition)) {
        $tsql .= ("title like (?) AND ");
        array_push($param, '%'.$condition["title"].'%');
    }
    if (array_key_exists("press", $condition)) {
        $tsql .= ("press like (?) AND ");
        array_push($param, '%'.$condition["press"].'%');
    }
    if (array_key_exists("year", $condition)) {
        $tsql .= ("year = (?) AND ");
        array_push($param, (int)$condition["year"]);
    }
    if (array_key_exists("author", $condition)) {
        $tsql .= ("author like (?) AND ");
        array_push($param, '%'.$condition["author"].'%');
    }
    if (array_key_exists("upper", $condition)) {
        $priceUpper = (double)$condition["upper"];
    }
    if (array_key_exists("lower", $condition)) {
        $priceLower = (double)$condition["lower"];
    }
    $tsql.= ("price <= (?) AND price >= (?) ORDER BY year desc");
    array_push($param, $priceUpper, $priceLower);
    $state = sqlsrv_prepare($conn, $tsql, $param);
    if($state)
        $getResults= sqlsrv_query($conn, $tsql, $param);
    if ($getResults == FALSE)
        die(FormatErrors(sqlsrv_errors()));
    $resultStr = "";
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC))
    {
        $temp = '<tr>';
        foreach($row as $key=>$element)
        {
                $temp .= ("<td>".$element."</td>");
        }
        $temp .= '</tr>';
        $resultStr .= $temp;
    }
    echo($resultStr);
    sqlsrv_free_stmt($getResults);
    sqlsrv_close($conn);
}

function selectBorrow($conn, $condition)
{
    if($conn == false) {
        die("LOGINERROR");
    }
    $tsql = "SELECT *  FROM BORROW ";
    $param = array();
    if (array_key_exists("cno", $condition)) {
        $tsql .= ("WHERE cno = (?)");
       array_push($param, $condition);
    }
    $tsql .= "order by return_date";
    $state = sqlsrv_prepare($conn, $tsql, $param);
    if($state)
        $getResults= sqlsrv_query($conn, $tsql, $param);
    else die("ERROR");
    if ($getResults == FALSE)
        die($tsql." ".FormatErrors(sqlsrv_errors()));
    $resultStr = "";
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC))
    {
        
        $temp2 = "<tr>";
        foreach($row as $key=>$element)
        {
            if($key != "return_date" && $key != "borrow_date")
                $temp2 .= ("<td>".$element."</td>");
            else
               $temp2 .= ("<td>".json_decode(json_encode($element),true)["date"]."</td>");
        }
        $temp2 .= "</tr>";
        $resultStr .= $temp2;
        var_dump($row);
    
    }
    echo($resultStr);
    sqlsrv_free_stmt($getResults);
    sqlsrv_close($conn);    
}

function insertBook($conn, $info) 
{
    if($conn == false) {
        die("LOGINERROR");
    }
    $psql = "SELECT count(*) FROM BOOK WHERE bno = (?)";
    $param = array($info["bno"]);
    $getResults= sqlsrv_query($conn, $psql, $param);
    $row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC);
    foreach($row as $key=>$element){
        if($element == '1'){
            $psql = "UPDATE BOOK SET total = total + (?) WHERE bno = (?)";
            $param = array((int)$info["total"], $info["bno"]);
            $getResults= sqlsrv_query($conn, $psql, $param);
            if ($getResults == FALSE) {
                die("UPERROR");
            }
            $psql = "UPDATE BOOK SET stock = stock + (?) WHERE bno = (?)";
            $param = array((int)$info["total"], $info["bno"]);
            $getResults= sqlsrv_query($conn, $psql, $param);
            if ($getResults == FALSE) {
                die("UPERROR");
            }
            die("UPSUCCESS");
        }
        else break;
    }
    $tsql = "INSERT INTO BOOK VALUES ((?),(?),(?),(?),(?),(?),(?),(?),(?))";
    $param = array($info["bno"],$info["category"],$info["title"],$info["press"],(int)$info["year"],$info["author"],(double)$info["price"],(int)$info["total"],(int)$info["total"]);
    $getResults= sqlsrv_query($conn, $tsql,$param);
    if ($getResults == FALSE) {
        die("INERROR");
    }
    else{
        echo("INSUCCESS");
    }
    sqlsrv_free_stmt($getResults);
    sqlsrv_close($conn);
}

function insertCard($conn, $info)  
{
    if($conn == false) {
        die("LOGINERROR");
    }
    $tsql = "INSERT INTO CARD VALUES((?),(?),(?),(?),(?))";
    $param = array($info["cno"],$info["name"],$info["department"],$info["type"],$info["pas"]);
    $getResults= sqlsrv_query($conn, $tsql, $param);
    if ($getResults == FALSE){
        echo($tsql."\n");
        die(FormatErrors(sqlsrv_errors()));
    }
    else{
        echo($tsql);
    }
    sqlsrv_free_stmt($getResults);
    sqlsrv_close($conn);
}

function insertBorrow($conn, $info)  
{
    if($conn == false) {
        die("LOGINERROR");
    }
    if ( sqlsrv_begin_transaction( $conn ) === false ) {  
        die( print_r( sqlsrv_errors(), true ));  
        }  
    $tsql = "INSERT INTO BORROW VALUES((?),(?),(?),(?))";
    $borrowdate = date("Y-m-d");
    $param = array($info["cno"],$info["bno"],$borrowdate,$info["returndate"]);
    $getResults= sqlsrv_query($conn, $tsql, $param);
    if ($getResults == FALSE)
    {
        sqlsrv_rollback( $conn );  
        echo $tsql;
        die(FormatErrors(sqlsrv_errors()));
    }
    else{
        $tsql = "UPDATE BOOK SET stock = stock - 1 where bno = (?)";
        $param = array($info["bno"]);
        $getResults= sqlsrv_query($conn, $tsql, $param);
        if ($getResults == FALSE)
        {
            sqlsrv_rollback( $conn );  
            echo $tsql;
            die(FormatErrors(sqlsrv_errors()));
        }
        sqlsrv_commit( $conn );  
    }
    sqlsrv_free_stmt($getResults);
    sqlsrv_close($conn);
}



function deleteBorrow($conn, $info) // 还书
{
    if($conn == false) {
        die("LOGINERROR");
    }
    if ( sqlsrv_begin_transaction( $conn ) === false ) {  
        die( print_r( sqlsrv_errors(), true ));  
        }  
    $tsql = "DELETE FROM BORROW WHERE bno=(?) AND cno=(?)";
    $param = array($info["bno"],$info["cno"]);
    $getResults= sqlsrv_query($conn, $tsql, $param);
    if ($getResults == FALSE)
    {
        sqlsrv_rollback( $conn );  
        echo($tsql."\n");
        echo("无此条借阅记录！");
        die(FormatErrors(sqlsrv_errors()));
    }
    else
    {
        $tsql = "UPDATE BOOK SET stock = stock + 1 where bno = (?)";
        $param = array($info["bno"]);
        $getResults= sqlsrv_query($conn, $tsql, $param);
        if ($getResults == FALSE)
        {
            sqlsrv_rollback( $conn );  
            echo $tsql;
            die(FormatErrors(sqlsrv_errors()));
        }
        sqlsrv_commit( $conn );  
        echo($tsql."\n");
    }
    sqlsrv_free_stmt($getResults);
    sqlsrv_close($conn);

}


function deleteCard($conn, $info)
{
    if($conn == false) {
        die("LOGINERROR");
    }
    $tsql = "SELECT count(*) from borrow where cno = (?)";
    $param = array($info["cno"]);
    $getResults= sqlsrv_query($conn, $tsql, $param);
    if ($getResults == FALSE){
        die("LOGINERROR");
        echo($tsql."\n");
        die(FormatErrors(sqlsrv_errors()));
    }
    $row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC);
    foreach($row as $key=>$element){
        if((int)$element >= 1){
            die('NOTRETURN');
        }
        else if($element == '0'){
            $tsql = "DELETE FROM card where cno = (?)";
            $param = array($info["cno"]);
            $getResults= sqlsrv_query($conn, $tsql,$param);
            if ($getResults == FALSE)
                die(FormatErrors(sqlsrv_errors()));
            else;
            sqlsrv_free_stmt($getResults);
            sqlsrv_close($conn);
            die("SUCCESS");
        }
    }  
}



function UserSelect($conn, $condition, $priceUpper = 1000000, $priceLower = -1000000){
    if($conn == false) {
        die("LOGINERROR");
    }
    $param = array();
    $tsql = "SELECT * FROM BOOK WHERE ";
    if (array_key_exists("bno", $condition)) {
        $tsql .= ("bno = (?) AND ");
        array_push($param, $condition["bno"]);
    }
    if (array_key_exists("category", $condition)) {
        $tsql .= ("category like (?) AND ");
        array_push($param, '%'.$condition["category"].'%');
    }
    if (array_key_exists("title", $condition)) {
        $tsql .= ("title like (?) AND ");
        array_push($param, '%'.$condition["title"].'%');
    }
    if (array_key_exists("press", $condition)) {
        $tsql .= ("press like (?) AND ");
        array_push($param, '%'.$condition["press"].'%');
    }
    if (array_key_exists("year", $condition)) {
        $tsql .= ("year = (?) AND ");
        array_push($param, (int)$condition["year"]);
    }
    if (array_key_exists("author", $condition)) {
        $tsql .= ("author like (?) AND ");
        array_push($param, '%'.$condition["author"].'%');
    }
    if (array_key_exists("upper", $condition)) {
        $priceUpper = (double)$condition["upper"];
    }
    if (array_key_exists("lower", $condition)) {
        $priceLower = (double)$condition["lower"];
    }
    $tsql.= ("price <= (?) AND price >= (?) ORDER BY year desc");
    array_push($param, $priceUpper, $priceLower);
    $state = sqlsrv_prepare($conn, $tsql, $param);
    if($state)
        $getResults= sqlsrv_query($conn, $tsql, $param);
    if ($getResults == FALSE)
        die(FormatErrors(sqlsrv_errors()));
    $resultStr = "";
    $count = 0;
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC))
    {
        $temp = '<tr>';
        foreach($row as $key=>$element)
        {
            if($key == "bno")
                $temp .= ('<td id="Select'.$count.'">'.$element.'</td>');
            else
                $temp .= ('<td>'.$element.'</td>');
        }
        $temp .= '<td><a class="btn btn-primary " id = "select'.$count.'" onclick="BorrowBook(this.id)">借阅此书</a></td></tr>';
        $resultStr .= $temp;
        $count++;
    }
    echo($resultStr);
    sqlsrv_free_stmt($getResults);
    sqlsrv_close($conn);
}


function FormatErrors( $errors )
{
    /* Display errors. */
    echo "Error information: ";

    foreach ( $errors as $error )
    {
        echo "SQLSTATE: ".$error['SQLSTATE']."";
        echo "Code: ".$error['code']."";
        echo "Message: ".$error['message']."";
    }
}
?>