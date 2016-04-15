<?php
$this->title = 'Wifi Billing Management';


use app\modules\wifibillingmanagement\themes\basic\myasset\ThemeAsset;

ThemeAsset::register($this);
$baseUrl = $this->assetBundles[ThemeAsset::className()]->baseUrl . '/';

//$assets = '@app/modules/membermanagement/themes/basic/static';
//$baseUrl = Yii::$app->assetManager->publish($assets);

?>
<?php 
	use yii\helpers\Html;

?>

<!DOCTYPE html>
<html>
<head>
	<title>上网</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl?>css/public.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl?>css/edit.css">
</head>
<body>
	<!-- header start -->
	<!-- header end -->
	<!-- main start -->
	<main id="main" style="margin-left:1%">
		<!-- asideNav start -->
		<aside id="asideNav" class="l"></aside>
		<!-- asideNav end -->
		<!-- content start -->
		<div class="search" style="width: 400px;margin-left:auto;margin-right:auto;">
				<form method="post">
				<label>
					<span >Name :</span>
					<input type="text" name="wifi_name" id="wifi_name" value="<?php echo isset($data[0]['wifi_name'])?$data[0]['wifi_name']:'' ?>"></input>
				</label>
				<br/><br/><br/>
				<label>
					<span>Flow :</span>
				
					<input type="text" name="wifi_flow" id='wifi_flow' onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"  value="<?php echo isset($data[0]['wifi_flow'])?$data[0]['wifi_flow']:'' ?>" ></input>
					
				</label>
				<br/><br/><br/>
					<label>
					<span>Price :</span>
					
					<input type="text" name="sale_price" id='sale_price' onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"  value="<?php echo isset($data[0]['sale_price'])?$data[0]['sale_price']:'' ?>" ></input>
					<!--onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"   -->
				</label>
					<br/><br/><br/>
				<label>
					<span>Status : </span>
					
					<input type="radio" name="status" value="0" checked="checked" />Enable  &nbsp;
					<input type="radio" name="status" value="1" <?php if (isset($data[0]['status'])){if ($data[0]['status']==1){echo "checked='checked'";}} ?>>Disable
					
				</label>
					
				<br/><br/><br/>
				<input type="hidden" name="wifi_id" value="<?php  echo isset($data[0]['wifi_id'])?$data[0]['wifi_id']:''?>">
				<span class="btn"><input id="mysubmit" type="button" value="submit" onclick="numbertest();"></input></span>
		<!-- content end -->
		</form>
		</div>
	</main>
	<!-- main end -->
	<script type="text/javascript" src="<?php echo $baseUrl?>js/jquery-2.2.2.min.js"></script>
	<script type="text/javascript" src="<?php echo $baseUrl?>js/public.js"></script>
	<script type="text/javascript">
      function numbertest(){
       var wifi_flow= document.getElementById("wifi_flow").value;
       var sale_price=document.getElementById("sale_price").value;
       var reg= new RegExp("^[0-9]*$");
       if(!reg.test(wifi_flow)){
           alert("Flow must be to the number...");
           return false;
           }
       else if(!reg.test(sale_price)){
    	   alert("Price must be to the number...");
    	   return false;
           }
       else{
    	 document.getElementById("mysubmit").type="submit";
           }
          }
	    $(function(){
    	$("#mysubmit").click(function(){
//     		var reg ="/^d+$/";
//         	var wifi_flow= $('#wifi_flow').val();
//         	var sale_price= $('#sale_price').val();
        	
//     		if(!reg.test(wifi_flow)){
//         		alert(wifi_flow);
//                 alert("输入Flow只能为数字");
//                 return false;
//         	}
//     		if(!reg.test(sale_price)){
//                 alert("输入price只能为数字");
//                 return false;
//         	}


    		 var wifi_name = $("#wifi_name").val();
    		 var wifi_flow = $("#wifi_flow").val();
    		 var sale_price = $("#sale_price").val();
        	 if(wifi_name==''){
                alert("wifi_name can't empty");
                return false;
             }
        	 else if(wifi_flow==''){
        		 alert("flow can't empty");
        		 return false;
            	 }
        	 else if(sale_price==''){
        		 alert("price can't empty");
        		 return false;
            	 }
             
        })
    	
 })
	</script>
</body>
</html>