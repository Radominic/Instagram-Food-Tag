<?php header("Content-Type:text/html;charset=UTF-8");
$conn=new mysqli("localhost","root","","instagram");
//create connection
//instagram database 와 data테이블 만 있는 상태에서 시작.
//$conn = new mysqli($servername,$username,$passcode,$dbname);
mysqli_query($conn, "set session character_set_connection=utf8;");
mysqli_query($conn, "set session character_set_results=utf8;");
mysqli_query($conn, "set session character_set_client=utf8;");

//전처리파트 

// 테이블생성 
/*
My sql에서 해주어야 하는 부분.
$sql = "create table data (name varchar(30), heart int ,id int not null auto_increment, primary key(id))";
$conn -> query($sql);
$sql = " LOAD DATA  LOCAL INFILE 'C:/wamp64/www/insta_detector/taglist.txt' into table data";
$conn -> query($sql);
*/
$sql = "create table tag (name varchar(30), heart int ,id int not null auto_increment, primary key(id))";
$conn -> query($sql);

//행간 중복처리.

$ssql="select  name, max(heart)as maxnum ,id  from data group by name";
$put="insert into tag(name,heart,id) values(?,?,?)";
$putt=$conn->prepare($put);
$putt->bind_param("sii",$name,$heart,$id);
$result=$conn->query($ssql);
while($hi=mysqli_fetch_array($result)){
$id=0;
$name=$hi["name"];
$heart=$hi["maxnum"];
$putt->execute();
}



//데이터 카운트

$sql = "create table province (id int not null auto_increment, name varchar(30), count int, primary key(id))";
$f = $conn->query($sql);

$provlist = array("서울","인천","광주","대구","울산","대전","부산","제주");
for($i =0;$i<count($provlist);$i++){
	$provlist[$i] = iconv("EUC-KR","UTF-8",$provlist[$i]);
}

for($i =0;$i<count($provlist);$i++){
$sql = "insert into province (name, count) values('".$provlist[$i]."',0)";
$conn -> query($sql);
}


$sql = "select * from tag";
$result = $conn->query($sql);
$num = $result->num_rows;
for($p=0;$p<$num;$p++){
$row = $result->fetch_array();

$newlist= [];

$namelist = explode("#",$row[0]);
	for ($i=0 ; $i<count($namelist) ; $i++){
	   for ($j=0 ; $j<count($provlist) ; $j++){
		  if(strpos($namelist[$i], $provlist[$j])===false){
			 }else{
				array_push($newlist,$provlist[$j]);
				}
	   $newlist = array_unique($newlist);   
	   }
	}
	if(count($newlist)>0){
		for($i =0;$i<count($newlist);$i++){
		$rawname = $newlist[$i];
		$sql = "update province set count = count +1 where name = '".$rawname."'";
		$conn -> query($sql);
		}
	}
}

$sql = "drop table data";
$conn -> query($sql);
/*
//테이블 드랍
	$sql = "drop table province";
	$conn -> query($sql);
	*/
?>