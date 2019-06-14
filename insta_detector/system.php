<?php header("Content-Type:text/html;charset=UTF-8");
//$servername ="localhost";
//$username = "root";
//$passcode = "";
//$dbname = "instagram";
$conn=new mysqli("localhost","root","","instagram");
//create connection
//$conn = new mysqli($servername,$username,$passcode,$dbname);
mysqli_query($conn, "set session character_set_connection=utf8;");
mysqli_query($conn, "set session character_set_results=utf8;");
mysqli_query($conn, "set session character_set_client=utf8;");

//검색어로 tag를 입력하지 않도록 예외 처리 해야한다.

$search = $_GET["searchbox"];
/*
//connection check
if($conn->connect_error){
die("connection failed : ".$conn->connect_error);
}else{
echo "connection completed";
}*/

//테이블 만들기
$sql = "create table $search (id int not null auto_increment, name varchar(30), count int, primary key(id))";
$f = $conn->query($sql);
echo $conn->error;

$sql = "select * from tag";
$result = $conn->query($sql);
$num = $result->num_rows;
for($p=0;$p<$num;$p++)
{
	$row = $result->fetch_array();
	$namelist = explode("#",$row[0]);
	$check = 0;
	for ($i=0 ; $i<count($namelist) ; $i++){
		if(strpos($namelist[$i], $search)==false){
			continue;
		}else{$check = 1;break;}
	}
	if($check ==1){
	print_r($namelist);
		for($j=1;$j<count($namelist);$j++){
			$name = $namelist[$j];
			//로우가 있는지 확인
			$sql = "select exists(
			select *
			from $search
			where name = "."'".addslashes($name) ."'"."
			)";
			$result2 =  $conn -> query($sql);
			echo $sql;
			$flag = $result2 -> fetch_array();
			//flag 1이면 로우 존재, 0이면 없음
			//로우가 없을때
				if($flag[0]==0){
				//카운트 1로 고정
				$sql = "insert into $search (name,count) values("."'".addslashes($name) ."'".", 1 )";
				$conn -> query($sql);
				}else{
				$sql = "update $search set count = count+1 where name = "."'".$name."'";
				$conn -> query($sql);
				}
		}
		
	}
	
}

	//쿼리로 생성된 테이블 가져와서 보여주기
	/*
	$slq = 
	$conn -> query($sql);
	*/

	//테이블 드랍
	$sql = "drop table $search";
	$conn -> query($sql);
?>
