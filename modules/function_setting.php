<!DOCTYPE html>
<html>
<head>
<link href="assets/css/build.css" rel="stylesheet">
  <title></title>
</head>
<body>
<?php
require_once("connect.php");
$connect = mysqli_connect($host,$user,$pass,$db) or die("เชื่อมต่อไม่สำเร็จ");
$select = mysqli_query($connect,"SELECT * FROM setting");
$result = mysqli_fetch_array($select);
$limit_level = $result["limit_level"];
?>
<div class="row">
    <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-body">
          <form name="form1" method="post" action="save_setting1.php" accept-charset="UTF-8" role="form">
            <fieldset>
              <h4><label><?php echo $_SESSION["strh5"];?></label></h4>
              <h4>
                <div class="radio radio-danger">
                  <input type="radio" name="b1" id="B2" value="B2"/><?php if (isset ($b1) && $b1=="B2")?>
                  <label for="B2"><?php echo $_SESSION["strh10"];?> (PDF)</label></div></h4>
                  <h4>
                    <div class="radio radio-danger">
                      <input type="radio" name="b1" id="B3" value="B3"/><?php if (isset ($b1) && $b1=="B3")?><label for="B3"><?php echo $_SESSION["strh11"];?> (PDF)</label>
                    </div>
                  </h4>
                  <br>
                  <button data-toggle="tooltip" title="กดปุ่มตกลงเพื่อทำรายการ" class="btn btn-sm btn-success " value=""><?php echo $_SESSION["strh15"];?> <span class="glyphicon glyphicon-ok-sign"></button>
                </fieldset>
              </form>
            </div>
          </div>
        </div>
        <?php
        if($_SESSION["lang"]=="th")
        {
          $checked1='checked';
        }
        else
        {
          $checked1='';
        }
        if($_SESSION["lang"]=="en")
        {
          $checked2='checked';
        }
        else
        {
          $checked2='';
        }
        if($_SESSION["lang"]=="")
        {
          $checked1='checked';
        }
        ?>

        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-body">
              <form name="form1" method="post" action="save_setting2.php" accept-charset="UTF-8" role="form">
                <fieldset>
                  <h4><label><?php echo $_SESSION["strh6"];?></label></h4>
                  <h4><div class="radio radio-danger">
                    <input type="radio" name="d1" id="D2" value="D2" <?php echo $checked1?>/><?php if (isset ($d1) && $d1=="D2")?>
                    <label for="D2"><?php echo $_SESSION["strh8"];?> <img title="th. version" alt="japan" src="photo/fagth.png">
                    </label>
                  </div></h4>
                  <h4>
                    <div class="radio radio-danger">
                      <input type="radio" name="d1" id="D3" value="D3" <?php echo $checked2?>/><?php if (isset ($d1) && $d1=="D3")?>
                      <label for="D3"><?php echo $_SESSION["strh9"];?> <img title="en. version" alt="english" src="photo/fagen.png"></label>
                    </div></h4><br>
                    <button data-toggle="tooltip" title="กดปุ่มตกลงเพื่อทำรายการ" class="btn btn-sm btn-success " value=""><?php echo $_SESSION["strh15"];?> <span class="glyphicon glyphicon-ok-sign"></span></button>
                  </fieldset>
                </form>
              </div>
            </div>
          </div>
                       <?php if($_SESSION["status"] == 'ADMIN')
    {
        ?>
          <div class="col-md-4">
            <div class="panel panel-default">
              <div class="panel-body">
                <h4><label><?php echo $_SESSION["strh7"];?></label></h4>
                <a href="profile.php?Action=Setting?Backup"><h4><span class="glyphicon glyphicon-download-alt"></span> <?php echo $_SESSION["strh12"];?> (SQL)</h4></a>
                <a href="profile.php?Action=Setting?Create"><h4><span class="glyphicon glyphicon-info-sign"></span> <?php echo $_SESSION["strh13"];?></h4></a>
                <a href="profile.php?Action=Setting?Status"><h4><span class="glyphicon glyphicon-flash"></span> <?php echo $_SESSION["strh14"];?></h4></a>
                <br><br>
              </div>
            </div>
          </div> 
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-body">
              <form name="form1" method="post" action="save_setting3.php" accept-charset="UTF-8" role="form">
                <fieldset>
                  <h4><label><?php echo $_SESSION["strh26"];?></label></h4>
                  <h4>
                      <input type="number" name="limit_level" min="1" max="100" value="<?php echo $limit_level;?>" class="form-control" >
                      <br>
                      <button data-toggle="tooltip" title="กดปุ่มตกลงเพื่อทำรายการ" class="btn btn-sm btn-success " value=""><?php echo $_SESSION["strh15"];?> <span class="glyphicon glyphicon-ok-sign"></button>
                      <br><br>
                    </fieldset>
              </form>
              <br>
            </div>
          </div>
        </div>
<?php
$remove_date = array();
$select_date = mysqli_query($connect,"SELECT DISTINCT year FROM water_table");
$i = 0;
while ($result_date = mysqli_fetch_array($select_date)) {
  $remove_date[$i] = $result_date["year"];
  $i++;
}
?>
      <div class="col-md-4">
        <div class="panel panel-default">
           <div class="panel-body">
            <form  method="post" action="remove.php" accept-charset="UTF-8" role="form">
              <h4><label><?php echo $_SESSION["strh59"];?></label></h4>
              <div class="form-group">
                <select class="form-control" name="date_range">
                 <option value=""><?php echo $_SESSION["strh60"];?></option>
                  <?php
                  for ($c=0; $c < $i; $c++) { 
                  ?>
                    <option value="<?php echo $remove_date[$c];?>"><?php echo $remove_date[$c];?></option>
                  <?php
                  }
                  ?>
                </select>
                <br>
                <button data-toggle="tooltip" title="กดปุ่มตกลงเพื่อทำรายการ" class="btn btn-sm btn-success " value=""><?php echo $_SESSION["strh15"];?> <span class="glyphicon glyphicon-ok-sign"></button>
                <br><br><br>
              </div>
            </form>
            <h4>*กรุณาสำรองข้อมูลก่อนลบ<a href="profile.php?Action=Setting?Backup" style="color:red"><i class="glyphicon glyphicon-chevron-right"></i>สำรองข้อมูล</h4></a>
          </div>
          </div>
      </div>
      
        <?php
    }
    ?>
          <div class="col-md-12">
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
          </div>
        </div>
      </div>
</body>
</html>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>