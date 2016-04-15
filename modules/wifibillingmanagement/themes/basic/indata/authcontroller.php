<?php
$this->title = 'Wifi Billing Management';


use app\modules\wifibillingmanagement\themes\basic\myasset\ThemeAsset;

ThemeAsset::register($this);
$baseUrl = $this->assetBundles[ThemeAsset::className()]->baseUrl . '/';

//$assets = '@app/modules/membermanagement/themes/basic/static';
//$baseUrl = Yii::$app->assetManager->publish($assets);



?>
<?php 
use Yii as myyii;
use yii\helpers\Html;
$weburl=Yii::$app->params['weburl'];
?>

<!DOCTYPE html>
<html>
<head>
	<title>上网</title>
	<meta charset="utf-8">
	<style>
		.btn { display:inline-block; vertical-align: top; margin-left: 50px; }
		.btn input[type='submit'] { background: #3f7fcf; }
	</style>
</head>
<body>
	
	<div class="r content" id="user_content">
	<div class="topNav">Wifi Billging&nbsp;&gt;&gt;&nbsp;<a href="#">Auth Controller</a></div>
	<div>
 					
					<form method="post">
					<center>
		    	    <select  style="width: 250px;height:30px" name="admin_id" id="admin_id" onchange="showHint();">
					<option selected='selected' value="100">None</option>
					<?php foreach ($admininfo as $k=>$v):?>				
					<option value="<?=$v['admin_id']?>" <?php echo $v['admin_id']==$admin_id?"selected='selected'":''?> ><?=$v['admin_name']?></option>
					<?php endforeach;?>
		  		   </select>
		  		   </center>
		  		   <br>
				<label class="label_checkbox">
				<span>Wifi Package:</span>
					<label id="1a" class="btn_checkbox"><!-- "class='btn_checkbox on'":"class='btn_checkbox'" -->
						<input type="checkbox" name="type[]" value="1" id="1b" ></input>
						<span></span>
					</label>
					</label>
				<label class="label_checkbox" style="margin-left: 50px">
				<span>Wifi URL:</span>
					<label id="2a"  class="btn_checkbox">
						<input type="checkbox" name="type[]" value="2" id="2b" ></input>
						<span></span>
					</label>
					</label>
					<label class="label_checkbox" style="margin-left:50px">
				    <span>Curr Card:</span>
					<label id="3a"  class="btn_checkbox">
						<input type="checkbox" name="type[]" value="3" id="3b" ></input>
						<span></span>
					</label>
					</label>
					<br>
					<br>
					<label class="label_checkbox">
				<span>Import Card:</span>
					<label id="4a"  class="btn_checkbox">
						<input type="checkbox" name="type[]" value="4" id="4b" ></input>
						<span></span>
					</label>
					</label>
					<label class="label_checkbox" style="margin-left: 50px">
				<span>IBS Pay Set:</span>
					<label id="5a"  class="btn_checkbox">
						<input type="checkbox" name="type[]" value="5" id="5b" ></input>
						<span></span>
					</label>
					</label>
					<label class="label_checkbox" style="margin-left: 30px">
				<span>Pay Information:</span>
					<label id="6a"  class="btn_checkbox">
						<input type="checkbox" name="type[]" value="6" id="6b" ></input>
						<span></span>
					</label>
					</label>
					<br>
					<br>
					<label class="label_checkbox">
				<span>Report:</span>
					<label id="7a"  class="btn_checkbox">
						<input type="checkbox" name="type[]" value="7" id="7b" ></input>
						<span></span>
					</label>
					</label>
		
				<input type="hidden" name='t' value='1'>
				<br>
				<br>
				
				<span class="btn"><input type="submit" id="mysubmit" value="submit"></input></span>
			
		</form>	
			</div>

		</div>
	
		<script type="text/javascript" src="<?php echo $baseUrl?>js/jquery-2.2.2.min.js"></script>
		<script type="text/javascript">
		/* 新增弹出框 ，使用ajax */
		function showHint()
		{
		var str=document.getElementById("admin_id").value;
		var xmlhttp;
		if (str.length==0)
		  { 
		 
		  return;
		  }
		  xmlhttp=new XMLHttpRequest();
		/*   }
		else
		  {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  } */
		xmlhttp.onreadystatechange=function()
		  {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		    {
					document.getElementById("1a").className="btn_checkbox";
					document.getElementById("1b").checked=""; 
					document.getElementById("2a").className="btn_checkbox";
					document.getElementById("2b").checked=""; 
					document.getElementById("3a").className="btn_checkbox";
					document.getElementById("3b").checked=""; 
					document.getElementById("4a").className="btn_checkbox";
					document.getElementById("4b").checked=""; 
					document.getElementById("5a").className="btn_checkbox";
					document.getElementById("5b").checked=""; 
					document.getElementById("6a").className="btn_checkbox";
					document.getElementById("6b").checked=""; 
					document.getElementById("7a").className="btn_checkbox";
					document.getElementById("7b").checked=""; 
			var role=xmlhttp.responseText;
			    roleinfo=role.split(",");
			    for(i=0;i<roleinfo.length;i++){	
			    
					if(roleinfo[i]==document.getElementById("1b").value){
						
						 document.getElementById("1a").className="btn_checkbox on";
						document.getElementById("1b").checked="checked"; 
						
			    	 }
					if(roleinfo[i]==document.getElementById("2b").value){
						document.getElementById("2a").className="btn_checkbox on";
						document.getElementById("2b").checked="checked"; 
						
			    	 }
					if(roleinfo[i]==document.getElementById("3b").value){
						
						 document.getElementById("3a").className="btn_checkbox on";
						document.getElementById("3b").checked="checked"; 
						
			    	 }
					if(roleinfo[i]==document.getElementById("4b").value){
						
						 document.getElementById("4a").className="btn_checkbox on";
						document.getElementById("4b").checked="checked"; 
						
			    	 }
					if(roleinfo[i]==document.getElementById("5b").value){
						
						 document.getElementById("5a").className="btn_checkbox on";
						document.getElementById("5b").checked="checked"; 
						
			    	 }
					if(roleinfo[i]==document.getElementById("6b").value){
						
						 document.getElementById("6a").className="btn_checkbox on";
						document.getElementById("6b").checked="checked"; 
						
			    	 }
					if(roleinfo[i]==document.getElementById("7b").value){
						
						 document.getElementById("7a").className="btn_checkbox on";
						document.getElementById("7b").checked="checked"; 
						
			    	 }
				    }
			 /*  var dataObj = eval("("+xmlhttp.responseText+")");
			  $.each(dataObj,function(idx,item){  
				   //输出 
				   alert(item.id+"哈哈"+item.name);  
				}) */
		    }
		  }
		xmlhttp.open("GET","<?php echo $weburl?>/indata/ajaxtrain?admin_id="+str,true);
		xmlhttp.send();
		}

		
	$(function(){
		$("#mysubmit").click(function(){
			var selectval=$("#admin_id").val();
			if(selectval=='100'){
				alert("Please select Admin of Edit");
				return false;
				}
			})
    <?php
 	 $massage=isset($_GET['massage'])?$_GET['massage']:'';?>
    <?php if ($massage=="success"){?>
    alert("Option success");
	window.location = "<?php echo $weburl?>/indata/authcontroller";
    <?php }elseif ($massage=="fail"){?>
    alert("Option Fail");
	window.location = "<?php echo $weburl?>/indata/authcontroller";
    <?php }?>
  
    //操作后弹出框
});

		</script>
</body>

</html>