<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>


    <?php header("Content-Type:text/html;charset=UTF-8");
    $servername ="localhost";
    $username = "root";
    $passcode = "";
    $dbname = "instagram";

    //create connection
    $conn = new mysqli($servername,$username,$passcode,$dbname);
    mysqli_query($conn, "set session character_set_connection=utf8;");
    mysqli_query($conn, "set session character_set_results=utf8;");
    mysqli_query($conn, "set session character_set_client=utf8;");


    $search = $_GET['searchbox'];


    if($search == 'tag'||$search == 'province'){
    die("problem");
    }



    $sql = "create table $search (id int not null auto_increment, name varchar(30), count int, score int ,  primary key(id))";
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
          if(strpos($namelist[$i], $search)===false){

          }else{$check = 1;break;}
       }

       if($check ==1){

          for($j=1;$j<count($namelist);$j++){
             $name = $namelist[$j];

             $sql = "select exists(
             select *
             from $search
             where name = "."'".addslashes($name) ."'"."
             )";
             $result2 =  $conn -> query($sql);
             $flag = $result2 -> fetch_array();

                if($flag[0]==0){

                $sql = "insert into $search (name,count,score) values("."'".addslashes($name) ."'".", 1,$row[1] )";

                $conn -> query($sql);
                }else{
                $sql = "update $search set count = count+1, score = score+$row[1] where name = "."'".$name."'";
                $conn -> query($sql);
                }
          }

       }

    }
      $exception = array("맞팔","좋아요","최고","daily","첫줄","$search", "맛집", "존맛", "먹스타그램", "맛스타그램", "JMT", "jmt", "먹방", "여행", "점심", "아침", "일상", "저녁");

      $name_list = array();
      $count_list = array();
      $score_list = array();

       $sql = "SELECT name, count, score from ".$search." order by count DESC";
       $result = $conn -> query($sql);

       $count = 0;
       while($row = mysqli_fetch_array($result)){
         $row[0] = preg_replace('/\r\n|\r|\n/','',$row[0]);
         if(strpos($row[0], "맛집")  === false){
           //echo count("");

         }else {
           continue;
         }

         if(empty($row[0]))continue;

         $flag = false;
         for($i = 0; $i < count($exception); $i++){
           if($row[0] == $exception[$i]){
            $flag = true;
            break;
           }
         }
         if($flag)continue;
         $count++;
         array_push($name_list, $row[0]);
         array_push($count_list, $row[1]);
         array_push($score_list, $row[2]);
         if($count == 10)break;
       }


       $sql = "drop table $search";
       $conn -> query($sql);

     ?>
    <script type='text/javascript' src='http://www.google.com/jsapi'></script>
     <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type='text/javascript'>google.load('visualization', '1', {'packages': ['geochart']});

    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    google.charts.setOnLoadCallback(drawChart2);

    var name_list = <?php echo  json_encode($name_list);?>;
    var count_list = <?php echo  json_encode($count_list);?> ;
    var score_list = <?php echo  json_encode($score_list);?> ;

    function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Tag', 'number'],

          [name_list[0], parseInt(count_list[0])],
          [name_list[1], parseInt(count_list[1])],
          [name_list[2], parseInt(count_list[2])],
          [name_list[3], parseInt(count_list[3])],
          [name_list[4], parseInt(count_list[4])],
          [name_list[5], parseInt(count_list[5])],
          [name_list[6], parseInt(count_list[6])],
          [name_list[7], parseInt(count_list[7])],
          [name_list[8], parseInt(count_list[8])],
          [name_list[9], parseInt(count_list[9])]
        ]);




        var options = {
          title: 'Tag frequency',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
      function drawChart2() {
          var data = google.visualization.arrayToDataTable([
            ['Tag', 'number'],

            [name_list[0], parseInt(score_list[0])],
            [name_list[1], parseInt(score_list[1])],
            [name_list[2], parseInt(score_list[2])],
            [name_list[3], parseInt(score_list[3])],
            [name_list[4], parseInt(score_list[4])],
            [name_list[5], parseInt(score_list[5])],
            [name_list[6], parseInt(score_list[6])],
            [name_list[7], parseInt(score_list[7])],
            [name_list[8], parseInt(score_list[8])],
            [name_list[9], parseInt(score_list[9])]
          ]);




          var options = {
            title: 'Like Ranking',
            is3D : true,
            pieHole: 0.4,
          };

          var chart = new google.visualization.PieChart(document.getElementById('donutchart2'));
          chart.draw(data, options);
        }


     </script>
     <table>
       <tr>
         <td><div id="donutchart" style="width: 700px; height: 500px;"></div></td><td><div id="donutchart2" style="width: 700px; height: 500px;"></div></td>
       </tr>
     </table>



  </body>
</html>
