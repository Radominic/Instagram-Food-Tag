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

//�˻���� tag�� �Է����� �ʵ��� ���� ó�� �ؾ��Ѵ�.

$search = $_GET["searchbox"];
/*
//connection check
if($conn->connect_error){
die("connection failed : ".$conn->connect_error);
}else{
echo "connection completed";
}*/

//���̺� �����
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
			//�ο찡 �ִ��� Ȯ��
			$sql = "select exists(
			select *
			from $search
			where name = "."'".addslashes($name) ."'"."
			)";
			$result2 =  $conn -> query($sql);
			echo $sql;
			$flag = $result2 -> fetch_array();
			//flag 1�̸� �ο� ����, 0�̸� ����
			//�ο찡 ������
				if($flag[0]==0){
				//ī��Ʈ 1�� ����
				$sql = "insert into $search (name,count) values("."'".addslashes($name) ."'".", 1 )";
				$conn -> query($sql);
				}else{
				$sql = "update $search set count = count+1 where name = "."'".$name."'";
				$conn -> query($sql);
				}
		}
		
	}
	
}

	//������ ������ ���̺� �����ͼ� �����ֱ�
	/*
	$slq = 
	$conn -> query($sql);
	*/

	//���̺� ���
	$sql = "drop table $search";
	$conn -> query($sql);
?>
