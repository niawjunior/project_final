<?php
require_once("connect.php");
require_once("config.php");
?>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">

  <script>
    setTimeout(function(){
   window.location.reload(1);
}, 15000);
  </script>
</head>
<body>
  <?php 
  session_start();
  $connect = mysqli_connect($host,$user,$pass,$db) or die("เชื่อมต่อไม่สำเร็จ");
  $POST = $_SESSION["USER"];
  $objQuery5 = mysqli_query($connect,"SELECT * FROM data");

  $select = mysqli_query($connect,"SELECT * FROM setting");
  $result_limit = mysqli_fetch_array($select);
  $limit_level = $result_limit["limit_level"]/100;
  ?>
  <form class="navbar-form navbar-left"  method="get" action="<?php $_SERVER["PHP_SELF"];?>?<?$_POST['search'];?>">
    <div class="form-group">
      <select  style="text-align-last:center;" name="search" class="selectpicker show-tick" title="กรุณาเลือกสถานที่" data-live-search="true" required >
        <?php
        while($objResult1 = mysqli_fetch_array($objQuery5)){
          $place = $objResult1['h1'];
          ?>
          <center><option data-tokens="<?php echo $place?>" value="<?php echo $place?>" required><center><?php echo $place?></center></option></center>
          <?php 
        }
        ?>
      </select>
      <button type="submit" class="btn btn-default">ค้นหา <span class="glyphicon glyphicon-search"></span></button>
    </div>
  </form>
  <?
  $objQuery3 = mysqli_query($connect,"SELECT * FROM showdata");
  $Rows = mysqli_num_rows($objQuery3);
  while($objResult = mysqli_fetch_array($objQuery3))
  {
    $f111 = $objResult['showh1'];
    $f222 = $objResult['showdeep'];
  }
  if($Rows == 1)
  {
    $PLACE_ADD = $f111;
    $TXT_PLACE = 'disabled';
    $REAL_LEVEL_ADD = $_GET['LEVEL'];
    $LEVEL_ADD = ($f222-$_GET['LEVEL']);
    $TIME_ADD = date("H:i");
    $DATE_ADD = date("Y-m-d");
    $YEAR = date("Y");
    $DEEP_ADD =  $f222;
    $DEEP_EDIT =  $_POST["txtdeep"];
  }
  else
  {
    $PLACE_ADD = $_POST["txtAddplace"];
    $REAL_LEVEL_ADD = $_POST["txtAddReallevel"];
    $LEVEL_ADD = $_POST["txtAddlevel"];
    $TIME_ADD = $_POST["txtAddtime"];
    $DATE_ADD = $_POST["txtAdddate"];
    $YEAR = date("Y");
  }
  if($_GET["Action"] == "ADD")
  {
    $query_check = mysqli_query($connect,"SELECT * FROM data WHERE h1 = '$f111' ");
    $query_resault = mysqli_fetch_array($query_check);
    $level = $query_resault['level'];
    if($level<0){
      $level == 0;
      echo $level;
      exit();
    }else{
      $level = $query_resault['level'];
    }
    $max = $level+$limit_level;
    $min = $level-$limit_level;

    if($LEVEL_ADD >=($max) or $LEVEL_ADD <=($min))
    {
    mysqli_query($connect,"INSERT INTO water_table (place,real_level,level,deep,time,date,year) VALUES  ('$PLACE_ADD','$REAL_LEVEL_ADD','$LEVEL_ADD','$DEEP_ADD','$TIME_ADD','$DATE_ADD','$YEAR') ");
    mysqli_query($connect,"UPDATE data SET level = '$LEVEL_ADD',time = '$TIME_ADD',date = '$DATE_ADD' WHERE h1 = '$f111' ");
    }
  }
    if(isset($_POST["submit"]))
        {
          $objQuery4 = mysqli_query($connect,"SELECT * FROM data WHERE h1 = '".$_POST["PLACE"]."'");
          while($objResult = mysqli_fetch_array($objQuery4))
          {
            $DEEP = $objResult['deep'];
          }
          mysqli_query($connect,"INSERT INTO water_table (place,real_level,level,deep,time,date,year) VALUES  ('".$_POST["PLACE"]."','$REAL_LEVEL_ADD', '$LEVEL_ADD',' $DEEP','$TIME_ADD','$DATE_ADD','$YEAR') ");
          mysqli_query($connect,"UPDATE data SET level = '$LEVEL_ADD',time = '$TIME_ADD',date = '$DATE_ADD' WHERE h1 = '".$_POST["PLACE"]."' ");
          mysqli_query($connect,"INSERT INTO activity (user,time,date,atvt,note) VALUES  ('$POST','$time',' $date','เพิ่มข้อมูลระดับน้ำ','เพิ่มข้อมูล | สถานที่ ".$_POST["PLACE"]." | ระดับน้ำ $LEVEL_ADD เมตร ') ");
          mysqli_query($connect,"UPDATE member SET lastactivity = 'เพิ่มข้อมูล | สถานที่ ".$_POST["PLACE"]." | ระดับน้ำ $LEVEL_ADD เมตร '  where user = '$POST'");
          mysqli_query($connect,"UPDATE member SET countatvt = countatvt+1 where user = '$POST'");
        }

  if($_POST["hdnCmd"] == "Update")
  {
    mysqli_query($connect,"UPDATE water_table SET place = '".$_POST["txtplace"]."',real_level ='".$_POST["txtReallevel"]."', level = '".$_POST["txtlevel"]."',time = '".$_POST["txttime"]."',date = '".$_POST["txtdate"]."'WHERE ID = '".$_POST["txtID"]."' ");
    mysqli_query($connect,"UPDATE data SET level = '".$_POST["txtlevel"]."',time = '".$_POST["txttime"]."',date = '".$_POST["txtdate"]."' WHERE h1 = '".$_POST["txtplace"]."' ");
    $mysql_query8 = mysqli_query($connect,"SELECT * FROM water_table WHERE ID = '".$_POST["txtID"]."'");
    while($objResult8 = mysqli_fetch_array($mysql_query8))
    {
      $data1 = $objResult8['ID'];
      $data2 = $objResult8['place'];
      $data3 = $objResult8['level'];
    }
    mysqli_query($connect,"INSERT INTO activity (user,time,date,atvt,note) VALUES  ('$POST','$time',' $date','แก้ไขข้อมูลระดับน้ำ','แก้ไขข้อมูล | สถานที่ $data2 | ไอดี $data1 | ระดับน้ำ $data3 เมตร ') ");
    mysqli_query($connect,"UPDATE member SET lastactivity = 'แก้ไขข้อมูล | สถานที่ $data2 | ระดับน้ำ $data3 เมตร '  where user = '$POST'");
    mysqli_query($connect,"UPDATE member SET countatvt = countatvt+1 where user = '$POST'");
?>
    <?php
  }
  if($_GET["Action"] == "Del")
  {
    mysqli_query($connect,"UPDATE member SET countatvt = countatvt+1 where user = '$POST'");
    $mysql_query8 = mysqli_query($connect,"SELECT * FROM water_table WHERE ID = '".$_GET["ID"]."'");
    while($objResult8 = mysqli_fetch_array($mysql_query8))
    {
      $data1 = $objResult8['ID'];
      $data2 = $objResult8['place'];
      $data3 = $objResult8['level'];
    }
    
    mysqli_query($connect,"INSERT INTO activity (user,time,date,atvt,note) VALUES  ('$POST','$time',' $date','ลบข้อมูลระดับน้ำ','ลบข้อมูล | สถานที่ $data2 | ไอดี $data1 | ระดับน้ำ $data3 เมตร ') ");
    mysqli_query($connect,"UPDATE member SET lastactivity = 'ลบข้อมูล | สถานที่ $data2 | ระดับน้ำ $data3 เมตร '  where user = '$POST'");
    mysqli_query($connect,"DELETE FROM water_table WHERE ID = '".$_GET["ID"]."'");
    
    $name = $_GET['name'];
    echo $name;
    $objQuery4 = mysqli_query($connect,"SELECT * FROM water_table WHERE place='$name' ORDER BY ID DESC LIMIT 1 ");
        while($objResult4 = mysqli_fetch_array($objQuery4))
    {
          $top_level = $objResult4['level'];

    }
    mysqli_query($connect,"UPDATE data SET level = '$top_level' WHERE h1 = '$name' ");
  }
  $search= $_GET['search'];
   $objQuery11 = mysqli_query($connect,"SELECT * FROM water_table ORDER BY ID DESC");
   $objQuery14 = mysqli_query($connect,"SELECT * FROM water_table WHERE (place  = '$search') ORDER BY ID DESC");

   if(isset($_GET['search']))
      {
        $objQuery_NUM = $objQuery14;
      }
   else
      {
       $objQuery_NUM = $objQuery11;
      }
  $Num_Rows = mysqli_num_rows($objQuery_NUM);
  $Per_Page =20;
  $Page = $_GET["Page"];
  if(!$_GET["Page"])
  {
    $Page=1;
  }
  $Prev_Page = $Page-1;
  $Next_Page = $Page+1;
  $Page_Start = (($Per_Page*$Page)-$Per_Page);
  if($Num_Rows<=$Per_Page)
  {
    $Num_Pages =1;
  }
  else if(($Num_Rows % $Per_Page)==0)
  {
    $Num_Pages =($Num_Rows/$Per_Page) ;
  }
  else
  {
    $Num_Pages =($Num_Rows/$Per_Page)+1;
    $Num_Pages = (int)$Num_Pages;
  }
  ?>
  <form name="frmMain" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
    <input type="hidden" name="hdnCmd" value="">
    <table class="table table-hover  "  border="0" id="bootstrap-table">
      <tr>
        <thead class="thead-inverse">
          <th class="default" width="15%" height="50"> <div align="center"><strong><?php echo $_SESSION["strh27"];?></strong></div></th>
            <th class="default" width="20%" height="50"> <div align="center"><strong><?php echo $_SESSION["strh28"];?></strong></div>
          <th class="default" width="20%" height="50"> <div align="center"><strong><?php echo $_SESSION["strh29"];?></strong></div>
          </th>
          <th class="default" width="15%" height="50"> <div align="center"><strong><?php echo $_SESSION["strh30"];?></strong></div></th>
          <th class="default" width="15%" height="50"> <div align="center"><strong><?php echo $_SESSION["strh31"];?></strong></div></th>
          <?php if($_SESSION["status"] == 'ADMIN')
          {
            ?>
          <th class="default" width="5%" height="50"> <div align="center"><strong><?php echo $_SESSION["strh32"];?></strong></div></th>
          <th class="default" width="20%" height="50"> <div align="center"><strong><?php echo $_SESSION["strh33"];?></strong></div></th>
            <?php
          }
          ?>
        </thead>
      </tr>
      <?php 
    $objQuery3 = mysqli_query($connect,"SELECT * FROM water_table WHERE (place  = '$search') ORDER BY ID DESC LIMIT $Page_Start , $Per_Page");

    $objQuery4 = mysqli_query($connect,"SELECT * FROM water_table ORDER BY ID DESC LIMIT $Page_Start , $Per_Page");

      if(isset($_GET['search']))
      {
        $objQuery = $objQuery3;
      }
      else
      {
       $objQuery = $objQuery4;
      }

      while($objResult = mysqli_fetch_array($objQuery))
      {
        $f0 = $objResult['ID'];
        $f1 = $objResult['place'];
        $f2 = $objResult['level'];
        $f3 = $objResult['time'];
        $f4 = $objResult['date'];
        $f7 = $objResult['real_level'];
        ?>
        <?php 
        if($objResult["ID"] == $_GET["ID"] and $_GET["Action"] == "Edit")
        {
          ?>
          <tr>
            <td width="27%">
            <select  style="text-align-last:center;" name="txtplace" class="form-control" >
              <?php 
                $objQuery5 = mysqli_query($connect,"SELECT * FROM data");
              while($objResult1 = mysqli_fetch_array($objQuery5))
              {
                $place = $objResult1['h1'];
                ?>
                <center><option value="<?php echo $place?>"><center><?php echo $place?></center></option></center>
                <?php 
              }
              ?>
            </select>
          </td>
           <td width="10%"><center><input class="form-control" type="text" style="text-align:center;" name="txtReallevel"  value="<?php echo $f7?>"></center></td>
            <td width="10%"><center><input class="form-control" type="text" style="text-align:center;" name="txtlevel"  value="<?php echo $f2?>"></center></td>
            <td width="25%"><center><input class="form-control" type="time" style="text-align:center;" name="txttime"   value="<?php echo $f3?>"></center></td>
            <td width="30%"><center><input class="form-control" type="date" style="text-align:center;" name="txtdate"   value="<?php echo $f4?>"></center></td>
            <td><center><button data-toggle="tooltip" title="บันทึกข้อมูล" data-placement="top" name="btnAdd" class="btn btn-success" id="btnUpdate"  OnClick="frmMain.hdnCmd.value='Update';frmMain.submit();">บันทึก <span class="glyphicon glyphicon-ok-sign"></span></button></center></td>
            <td><center><button data-toggle="tooltip" title="ยกเลิก" data-placement="top" name="btnAdd" class="btn btn-warning" id="btnCancel" OnClick="window.location='<?php echo $_SERVER["PHP_SELF"];?>?Page=<?php echo $Page?><?php if(isset($_GET['search'])){?>&search=<?php echo $search?><?php }?>';">ยกเลิก <span class="glyphicon glyphicon-share-alt"></span></button></center></td>
            <tr><input name="txtID" size="0" type="hidden" id="txtID" value="<?php echo $f0?>"></tr>
          </tr>
          <?php 
        }
        else
        {
          ?>
          <tr>
            <td><center><?php echo $f1 ?></center></td>
            <td><center><?php echo $f7 ?></center></td>
            <td><center><?php echo $f2 ?></center></td>
            <td><center><?php echo $f3 ?></center></td>
            <td><center><?php echo $f4 ?></center></td>
                      <?php if($_SESSION["status"] == 'ADMIN')
          {
            ?>
            <td align="center"><a data-toggle="tooltip" title="แก้ไขข้อมูล" data-placement="top" href="JavaScript:if(confirm('ต้องการจะแก้ไขหรือไม่?')==true){window.location='<?php echo $_SERVER["PHP_SELF"];?>?Page=<?php echo $Page?><?php if(isset($_GET['search'])){?>&search=<?php echo $search?><?php }?>&Action=Edit&ID=<?php echo $f0?>';}"> <span class="glyphicon glyphicon-edit"></span></a></td>
            <td align="center"><a  data-toggle="tooltip" title="ลบข้อมูล" data-placement="top" href="JavaScript:if(confirm('ต้องการจะลบหรือไม่?')==true){window.location='<?php echo $_SERVER["PHP_SELF"];?>?Page=<?php echo $Page?><?php if(isset($_GET['search'])){?>&search=<?php echo $search?><?php }?>&Action=Del&ID=<?php echo $f0?>&name=<?php echo $f1;?>';}"> <span class="glyphicon glyphicon-trash"></span></a></td>
            <?php
          }
            ?>
          </tr>
          <?php
        }
      }
      ?>
    </table>
  </form>
<form name="" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
<table class="table table-hover  "  border="0" id="bootstrap-table">
          <?php if($_SESSION["status"] == 'ADMIN')
          {
            if($_GET["Action"] == "Edit")
            {
              $close = 'disabled';
            }
            ?>
 <tr>
        <?php 
        if($Rows == 1)
        {
          ?>
          <td><center><input class="form-control" type="text" style="text-align:center;" name="txtAddplace" value="<?php echo $PLACE_ADD?>" <?php echo $TXT_PLACE ?>></center></td>
          <?php 
        }
        else
        {
          ?>
          <td width="15%">
            <select  style="text-align-last:center;" name="PLACE" class="form-control" <?php echo $close?>>
              <?php 
                $objQuery5 = mysqli_query($connect,"SELECT * FROM data");
              while($objResult1 = mysqli_fetch_array($objQuery5))
              {
                $place = $objResult1['h1'];
                ?>
                <center><option value="<?php echo $place?>"><center><?php echo $place?></center></option></center>
                <?php 
              }
              ?>
            </select>
          </td>
          <?php 
        }
        ?>
            <td width="20%"><center>
            <input  <?php echo $close?> style="text-align:center;" class="form-control" type="text"  name="txtAddReallevel"   required <?php echo $TXT_PLACE ?>/>
          </center>
          </td>

          <td width="20%"><center>
            <input  <?php echo $close?> style="text-align:center;" class="form-control" type="text"  name="txtAddlevel"   required <?php echo $TXT_PLACE ?>/>
          </center>
          </td>
        <td width="15%"><center><input  <?php echo $close?> class="form-control" type="time" style="text-align:center;" name="txtAddtime" required <?php echo $TXT_PLACE ?>></center></td>
        <td width="15%"><center><input <?php echo $close?>  class="form-control" type="date" style="text-align:center;" name="txtAdddate" required <?php echo $TXT_PLACE ?>></center></td>
        <td><center><button <?php echo $close?> data-toggle="tooltip" title="บันทึกข้อมูล" data-placement="top" name="submit" class="btn btn-success" id="submit"  value=""  <?php echo $TXT_PLACE ?>>บันทึก <span class="glyphicon glyphicon-ok-sign"></span></button></center></td>
        <td><center><button <?php echo $close?> data-toggle="tooltip" title="ล้างข้อมูล" data-placement="top" type = "reset" class="btn btn-warning" <?php echo $TXT_PLACE ?>>เคลียร์ <span class="glyphicon glyphicon-remove-sign"></button></center></td>        
      </tr>
            <?php
          }
        ?>
  </table>
  </form>
   <div align="right">
   *ยกเลิกการใช้งานแผนที่ สำหรับกรอกข้อมูล
      <nav>
        <ul class="pagination">
          <li <?php  if($Page==1) echo 'class="disabled"'?>>
            <a href="<?php $_SERVER[SCRIPT_NAME]?>?Page=1<?php if(isset($_GET['search'])){?>&search=<?php echo $search?><?php }?>" aria-label="Previous">
              <span aria-hidden="true">&laquo;</span>
            </a>
          </li>
          <?php 
          for($i=1;$i<=$Num_Pages;$i++)
          {
            if($Page-2>=2 and ($i>2 and $i<$Page-2))
            {
              ?>
              <li><a href="<?php $_SERVER[SCRIPT_NAME]?>?Page=<?php echo $i;?>&search=<?php echo $search?>">...</a></li>
              <?php 
              $i=$Page-2;
            }
            if($Page+5<=$Num_Pages and ($i>=$Page+3 and $i<=$Num_Pages-2))
            {
              ?>
              <li><a href="<?php $_SERVER[SCRIPT_NAME]?>?Page=<?php echo $i; ?><?php if(isset($_GET['search'])){?>&search=<?php echo $search?><?php }?>">...</a></li>
              <?php 
              $i=$Num_Pages-1;
            }
            ?>
            <li <?php  if($Page==$i) echo 'class="active"'?>><a href="<?php $_SERVER[SCRIPT_NAME]?>?Page=<?php echo $i; ?><?php if(isset($_GET['search'])){?>&search=<?php echo $search?><?php }?>"><?php echo $i; $e=$i; ?></a></li>
            <?php
          }
          ?>
          <li <?php  if($Page==$Num_Pages) echo 'class="disabled"'?>>
            <a href="<?php $_SERVER[SCRIPT_NAME]?>?Page=<?php echo $Num_Pages;?><?php if(isset($_GET['search'])){?>&search=<?php echo $search?><?php }?>" aria-label="Next">
              <span aria-hidden="true">&raquo;</span>
            </a>
          </li>
        </ul>
      </nav>
      <a id="back-to-top" href="#" class="btn btn-danger btn-md back-to-top" role="button" title="เลื่อนไปบนสุด" data-toggle="tooltip" data-placement="left"><span class="glyphicon glyphicon-chevron-up"></span></a>
    </div>
  </body>
  </html>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});



</script>

<html>
  