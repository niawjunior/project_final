<?php
	require_once("connect.php");
	require_once("config.php");
?>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<?php
session_start();
    if($_SESSION["STATUS"]=='')
    {
      header('Location: 404.php');
      exit();
    }
$POST = $_SESSION["USER"];
$connect = mysqli_connect($host,$user,$pass,$db) or die("เชื่อมต่อไม่สำเร็จ");
if(isset($_POST['submit']))
{

  mysqli_query($connect,"INSERT INTO activity (user,time,date,atvt,note) VALUES  ('$POST','$time',' $date','เพิ่มแผนที่',' เพิ่มข้อมูล | สถานที่ ".$_POST["txtAddh1"]."') ");
  mysqli_query($connect,"UPDATE member SET lastactivity = 'เพิ่มแผนที่ | สถานที่ ".$_POST["txtAddh1"]."'  where user = '$POST'");
  mysqli_query($connect,"UPDATE member SET countatvt = countatvt+1 where user = '$POST'");

$fileupload = $_REQUEST['fileupload'];  
$upload=$_FILES['fileupload'];
if($upload <> '') 
  { 
    $path="uploadphoto/";  
    $remove_these = array(' ','`','"','\'','\\','/','_');
    $newname = str_replace($remove_these, '', $_FILES['fileupload']['name']);
    $newname = time().'-'.$newname;
    $path_copy=$path.$newname;
    $path_link="uploadphoto/".$newname;

    move_uploaded_file($_FILES['fileupload']['tmp_name'],$path_copy);   
  }

  if($_FILES['fileupload']['name'] =='')
  {
    $objQuery = mysqli_query($connect,"INSERT INTO data (h1,h2,la,lo,deep,url) VALUES ('".$_POST["txtAddh1"]."','".$_POST["txtAddh2"]."','".$_POST["txtAddla"]."','".$_POST["txtAddlo"]."','".$_POST["txtAdddeep"]."','map.jpg')");
  }
  else
  {
      $objQuery = mysqli_query($connect,"INSERT INTO data (h1,h2,la,lo,deep,url) VALUES ('".$_POST["txtAddh1"]."','".$_POST["txtAddh2"]."','".$_POST["txtAddla"]."','".$_POST["txtAddlo"]."','".$_POST["txtAdddeep"]."','$newname')");
  }

  ?>
  <script>
    window.top.location.replace("profile.php?Action=Multiple");
  </script>
  <?php

}
if(isset($_POST['btnUpdate']))
{

  $mysql_query8 = mysqli_query($connect,"SELECT * FROM data WHERE ID = '".$_POST["txtID"]."'");
  while($objResult8 = mysqli_fetch_array($mysql_query8))
  {
    $data1 = $objResult8['ID'];
    $data2 = $objResult8['h1'];
    $data3 = $objResult8['url'];
  }
    mysqli_query($connect,"INSERT INTO activity (user,time,date,atvt,note) VALUES  ('$POST','$time',' $date','แก้ไขแผนที่',' แก้ไขข้อมูล | สถานที่ ".$_POST["txth1"]." | ไอดี $data1') ");
    mysqli_query($connect,"UPDATE member SET lastactivity = 'แก้ไขแผนที่ | สถานที่ ".$_POST["txth1"]."'  where user = '$POST'");
    mysqli_query($connect,"UPDATE member SET countatvt = countatvt+1 where user = '$POST'");
    $fileupload_edit = $_REQUEST['fileupload_edit']; 

    $upload=$_FILES['fileupload_edit'];
    if($upload <> '') 
    {  
        $path="uploadphoto/";  
        $remove_these = array(' ','`','"','\'','\\','/','_');
        $newname = str_replace($remove_these, '', $_FILES['fileupload_edit']['name']);
        $newname = time().'-'.$newname;
        $path_copy=$path.$newname;
        $path_link="uploadphoto/".$newname;
      if(move_uploaded_file($_FILES['fileupload_edit']['tmp_name'],$path_copy))
      {
        $objQuery = mysqli_query($connect,"UPDATE data SET h1 = '".$_POST["txth1"]."',h2 = '".$_POST["txth2"]."',la = '".$_POST["txtla"]."',lo = '".$_POST["txtlo"]."',deep = '".$_POST["txtdeep"]."',url = '$newname' WHERE ID = '".$_POST["txtID"]."'");
      }  
      else
      {
           $objQuery = mysqli_query($connect,"UPDATE data SET h1 = '".$_POST["txth1"]."',h2 = '".$_POST["txth2"]."',la = '".$_POST["txtla"]."',lo = '".$_POST["txtlo"]."',deep = '".$_POST["txtdeep"]."',url = '$data3' WHERE ID = '".$_POST["txtID"]."'");
      }
    }
?>
  <script>
    window.top.location.replace("profile.php?Action=Multiple");
  </script>
<?php
}
$objQuery3 = mysqli_query($connect,"SELECT * FROM showdata");
while($objResult3 = mysqli_fetch_array($objQuery3))
{
      $f111 = $objResult3['showh1'];
      $f222 = $objResult3['ID'];

}


if($_GET["Action"] == "Use")
{
    $name = $_GET['name'];
    $objQuery4 = mysqli_query($connect,"SELECT * FROM water_table WHERE place='$name' ORDER BY ID DESC LIMIT 1 ");
    while($objResult4 = mysqli_fetch_array($objQuery4))
    {
          $top_level = $objResult4['level'];

    }
    $objQuery = mysqli_query($connect,"UPDATE data SET h1 = '".$_POST["txth1"]."',h2 = '".$_POST["txth2"]."',la = '".$_POST["txtla"]."',lo = '".$_POST["txtlo"]."',deep = '".$_POST["txtdeep"]."'WHERE ID = '".$_POST["txtID"]."' ");
    mysqli_query($connect,"UPDATE data SET level = '$top_level' WHERE h1 = '$name' ");
  	$objQuery = mysqli_query($connect,"SELECT * FROM data WHERE ID = '".$_GET["ID"]."'");
  while($objResult = mysqli_fetch_array($objQuery))

  {
        $f00 = $objResult['ID'];
        $f11 = $objResult['h1'];
        $f22 = $objResult['h2'];
        $f33 = $objResult['la'];
        $f44 = $objResult['lo'];
        $f55 = $objResult['url'];
        $f66 = $objResult['deep'];
  }
  	$objQuery = mysqli_query($connect,"DELETE FROM showdata WHERE ID = '$f222'");
  	$objQuery = mysqli_query($connect,"INSERT INTO showdata (showh1,showh2,showla,showlo,showurl,showdeep) VALUES  ('$f11','$f22','$f33','$f44','$f55','$f66') ");
    mysqli_query($connect,"UPDATE member SET countatvt = countatvt+1 where user = '$POST'");
    mysqli_query($connect,"INSERT INTO activity (user,time,date,atvt,note) VALUES  ('$POST','$time',' $date','เลือกเป็นแผนที่หลัก',' เลือกใช้ข้อมูล | สถานที่ $f11 | ไอดี $f00') ");
    mysqli_query($connect,"UPDATE member SET lastactivity = 'ใช้เป็นแผนที่หลัก | สถานที่ $f11'  where user = '$POST'");
    ?>
  <script>
    window.top.location.replace("profile.php?Action=Multiple");
  </script>
    <?php
}

if($_GET["Action"] == "UnUse")
{
    $objQuery7 = mysqli_query($connect,"SELECT * FROM data WHERE ID = '".$_GET["ID"]."'");

    while($objResult7 = mysqli_fetch_array($objQuery7))

    {
          $f111 = $objResult7['h1'];
    }


    $objQuery8 = mysqli_query($connect,"SELECT * FROM data WHERE h1 = '$f111'");

    while($objResult8 = mysqli_fetch_array($objQuery8))

    {
          $f000 = $objResult8['ID'];
    }

    mysqli_query($connect,"UPDATE member SET countatvt = countatvt+1 where user = '$POST'");
    mysqli_query($connect,"INSERT INTO activity (user,time,date,atvt,note) VALUES  ('$POST','$time',' $date','ยกเลิกเป็นแผนที่หลัก',' ยกเลิกการใช้ข้อมูล | สถานที่ $f111 | ไอดี $f000') ");
    mysqli_query($connect,"UPDATE member SET lastactivity = 'เลิกใช้เป็นแผนที่หลัก | สถานที่ $f111'  where user = '$POST'");

  	$objQuery = mysqli_query($connect,"DELETE FROM showdata WHERE ID = '".$_GET["ID"]."'");

?>
 <script>
    window.top.location.replace("profile.php?Action=Multiple");
  </script>
<?php
}

if($_GET["Action"] == "Del")
{
  $mysql_query8 = mysqli_query($connect,"SELECT * FROM data WHERE ID = '".$_GET["ID"]."'");
  while($objResult8 = mysqli_fetch_array($mysql_query8))
  {
    $data1 = $objResult8['ID'];
    $data2 = $objResult8['h1'];
    $url = $objResult8['url'];
  }
    mysqli_query($connect,"UPDATE member SET countatvt = countatvt+1 where user = '$POST'");
    mysqli_query($connect,"INSERT INTO activity (user,time,date,atvt,note) VALUES  ('$POST','$time',' $date','ลบแผนที่','ลบข้อมูล | สถานที่ $data2 | ไอดี $data1') ");
    mysqli_query($connect,"UPDATE member SET lastactivity = 'ลบแผนที่ | สถานที่ $data2'  where user = '$POST'");



  	$objQuery = mysqli_query($connect,"DELETE FROM data  WHERE ID = '".$_GET["ID"]."'");
  
    if($objQuery)
    {

    ?>
      <script>
    window.top.location.replace("profile.php?Action=Multiple");
  </script>
    <?php
    }
}
$objQuery1 = mysqli_query($connect,"SELECT * FROM data");
$objQuery = mysqli_query($connect,"SELECT * FROM data ORDER BY ID DESC ");
?>
<form name="" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" enctype="multipart/form-data">
<table class="table table-hover"  id="bootstrap-table">
  <tr>
  <thead class="thead-inverse">
    <th class="default" width="20%" height="50"> <div align="center"><strong><?php echo $_SESSION["strh27"];?></strong></div></th>
    <th class="default" width="28%" height="50"> <div align="center"><strong><?php echo $_SESSION["strh34"];?></strong></div></th>
    <th class="default" width="12%" height="50"> <div align="center"><strong><?php echo $_SESSION["strh35"];?></strong></div></th>
    <th class="default" width="8%" height="50"> <div align="center"><strong><?php echo $_SESSION["strh36"];?></strong></div></th>
    <th class="default" width="11%" height="50"> <div align="center"><strong><?php echo $_SESSION["strh37"];?></strong></div></th>
      <?php if($_SESSION["status"] == 'ADMIN')
        {
        ?>
    <th class="default" width="5%" height="50"> <div align="center"><strong><?php echo $_SESSION["strh38"];?></strong></div></th>
    <th class="default" width="5%" height="50"> <div align="center"><strong><?php echo $_SESSION["strh39"];?></strong></div></th>
    <th class="default" width="5%" height="50"> <div align="center"><strong><?php echo $_SESSION["strh32"];?></strong></div></th>
    <th class="default" width="15%" height="50"> <div align="center"><strong><?php echo $_SESSION["strh33"];?></strong></div></th>
        <?php
        }
      ?>

</thead>
  </tr>
<?php
while($objResult = mysqli_fetch_array($objQuery))
{
      $f0 = $objResult['ID'];
      $f1 = $objResult['h1'];
      $f2 = $objResult['h2'];
      $f3 = $objResult['la'];
      $f4 = $objResult['lo'];
      $f5 = $objResult['deep'];
?>
<?php
	if($objResult["ID"] == $_GET["ID"] and $_GET["Action"] == "Edit")
	{
  ?>
  <tr>
    <td width="27%"><center>
      <input autofocus="autofocus" class="form-control" style="text-align:center;" name="txth1" type="text" value="<?php echo $f1?>" required>
    </center></td>
      <td width="40%"><center>
      <input class="form-control" style="text-align:center;" name="txth2" type="text" value="<?php echo $f2?>" required>
    </center></td>
      <td width="12%"><center>
      <input class="form-control" style="text-align:center;" name="txtla" type="text" value="<?php echo $f3?>" required>
    </center></td>
      <td width="10%"><center>
      <input class="form-control" style="text-align:center;" name="txtlo" type="text" value="<?php echo $f4?>" required>
    </center></td>
      <td width="12%"><center>
      <input class="form-control" style="text-align:center;" name="txtdeep" type="text" value="<?php echo $f5?>" required>
    </center></td>
    <td ><center><label class="btn btn-primary btn-file" data-toggle="tooltip" title="เลือกรูปภาพ">
        รูปภาพ <span class="glyphicon glyphicon-picture"></span><input type="file" name="fileupload_edit" id="fileupload_edit" style="display: none;">
    </label></center></td>
      <td ><center><button data-toggle="tooltip" title="บันทึกข้อมูล" name="btnUpdate" class="btn btn-success" id="btnUpdate" value="">บันทึก <span class="glyphicon glyphicon-ok-sign"></button></center></td>
      <td ><center><button data-toggle="tooltip" title="ยกเลิก" name="btnAdd" class="btn btn-warning" id="btnCancel" value="" OnClick="window.location='<?php echo $_SERVER["PHP_SELF"];?>?Page=<?php echo $Page?>';">ยกเลิก <span class="glyphicon glyphicon-share-alt"></button></center></td>
      
        </div></td>
        
           <input name="txtID" size="0" type="hidden" id="txtID" value="<?php echo $f0?>">
  </tr>
  <?
	}
  else
	{
  ?>
  <tr>
  <td width="20%"><center><a class="text-primary" href="water_level.php?PAGE=<?php echo $f1?>" target="_blank"><?php echo substr($f1,0,50);?></a></center></td>
  <td><center><?php echo substr($f2,0,25); ?></center></td>
  <td><center><?php echo Round($f3,2); ?></center></td>
  <td><center><?php echo Round($f4,2); ?></center></td>
  <td><center><?php echo $f5 ?></center></td>

  <?php if($_SESSION["status"] == 'ADMIN')
    {

      if($f1==$f111)
      {
        $ICON='<span class="glyphicon glyphicon-minus-sign" style="color:#f76c6c"></span>';
        $icon='<span class="glyphicon glyphicon-record" style="color:#7ff97f"></span> ';
      }
      else
      {
        $ICON='<span class="glyphicon glyphicon-plus-sign"></span>';
          $icon='<span class="glyphicon glyphicon-record" style="color:#f76c6c"></span>';
        }
      ?>
<?php 
if($f1==$f111){$TT='ต้องการยกเลิกการใช้แผนที่หรือไม่?';$text1='ยกเลิกแผนที่หลัก';}else{$TT='ต้องการใช้เป็นแผนที่หลักหรือไม่?';$text1 ='ใช้เป็นแผนที่หลัก';}?>
<td align="center"><a data-toggle="tooltip" title="<?php echo $text1?>" href="JavaScript:if(confirm('<?php echo $TT?>')==true){window.location='<?php echo $_SERVER["PHP_SELF"];?>?Page=<?php echo $Page?>&Action=<?if($f1==$f111){$UU='UnUse';$II=$f222;}else{$UU='Use';$II=$f0;}?><?php echo $UU?>&ID=<?php echo $II?>&name=<?php echo $f1;?>';}"><?php echo $ICON?></a></td>
<td align="center" > <?php echo $icon?></td>
<td align="center"><a data-toggle="tooltip" title="แก้ไขข้อมูล" href="JavaScript:if(confirm('ต้องการจะแก้ไขหรือไม่?')==true){window.location='<?php echo $_SERVER["PHP_SELF"];?>?Page=<?php echo $Page?>&Action=Edit&ID=<?php echo $objResult["ID"];?>';}"> <span class="glyphicon glyphicon-edit"></span></a></td>
<td align="center"><a data-toggle="tooltip" title="ลบข้อมูล" href="JavaScript:if(confirm('ต้องการจะลบหรือไม่?')==true){window.location='<?php echo $_SERVER["PHP_SELF"];?>?Action=Del&ID=<?php echo $f0?>';}"> <span class="glyphicon glyphicon-trash"></span></a></td>
</tr>
  <?php 
	}
}
}
?>
</table>
</form>
  <?php if($_SESSION["status"] == 'ADMIN')
    {
      if($_GET["Action"] == "Edit")
      {
         $close = 'disabled';
      }
      ?>
      <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" enctype="multipart/form-data">
        <table class="table table-hover"  id="bootstrap-table">
        <tr>
          <td width="20%"><center>
          <input <?php echo  $close;?> class="form-control" style="text-align:center;" name="txtAddh1" type="text" placeholder="ชื่อสถานที่" required>
        </center></td>
          <td width="30%"><center>
          <input <?php echo  $close;?> class="form-control" style="text-align:center;" name="txtAddh2" type="text" placeholder="คำอธิบายสั้นๆ" required >
        </center></td>
          <td width="10%"><center>
          <input <?php echo  $close;?> class="form-control" style="text-align:center;" name="txtAddla" type="text" required>
        </center></td>
          <td width="10%"><center>
          <input <?php echo  $close;?> class="form-control" style="text-align:center;" name="txtAddlo" type="text" required>
        </center></td>
          <td width="10%"><center>
          <input <?php echo  $close;?> class="form-control" style="text-align:center;" name="txtAdddeep" type="text" required>
        </center></td>
        <td><center><label  <?php echo  $close;?> class="btn btn-primary btn-file btn-sm" data-toggle="tooltip" title="เลือกรูปภาพ">
            รูปภาพ <span class="glyphicon glyphicon-picture"></span> <input <?php echo  $close;?> data-placement="top" type="file" name="fileupload" id="fileupload" style="display: none;">
        </label></center></td>
          <td><center><button <?php echo  $close;?> data-toggle="tooltip" title="บันทึกข้อมูล" data-placement="top" name="submit" class="btn btn-success btn-sm" id="submit" value="">บันทึก <span class="glyphicon glyphicon-ok-sign"></span></button></center></td>
          <td><center><button <?php echo  $close;?> data-toggle="tooltip" title="ล้างข้อมูล" data-placement="top" class="btn btn-warning btn-sm" type = "reset" width="20%" value="">เคลียร์ <span class="glyphicon glyphicon-remove-sign"></span></button></center></td>
          </tr>
        </table>
      </form>
      <?php
    }
    ?>
<?php 
mysqli_close($connect);
?>
</body>
</html>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>