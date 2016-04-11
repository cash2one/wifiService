
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
<head>
			<?=Html::cssFile('@web/assets/css/bootstrap.css')?>
			<script type="text/javascript" src="<?php echo $baseUrl?>js/jquery-2.2.2.min.js"></script>
			<script src="<?php echo $baseUrl?>js/jqPaginator.js"></script>	
</head>

	<body>
	
	
											<form id='member_list' method="post">
										 <input type='hidden' name='page' value="1">
                                        <input type='hidden' name='isPage' value="1">
                                            <div class="center" id="page_div"></div> 
	                                 </form>
			
									<script type="text/javascript">
								
									jQuery(function($) {
										  
									/* 获取参数 */
										//分页
									     var page = 1;
									        $('#page_div').jqPaginator({
									            totalPages: 2,
									            visiblePages: 5,
									            currentPage: page,
									            wrapper:'<ul class="pagination"></ul>',
									            first:  '<li class="first"><a href="javascript:void(0);">首页</a></li>',
									            prev:   '<li class="prev"><a href="javascript:void(0);">«</a></li>',
									            next:   '<li class="next"><a href="javascript:void(0);">»</a></li>',
									            last:   '<li class="last"><a href="javascript:void(0);">尾页</a></li>',
									            page:   '<li class="page"><a href="javascript:void(0);">{{page}}</a></li>',
									            onPageChange: function (num) {
									                var val = $("input[name='page']").val();
									                if(num != val)
									                {
									                    $("input[name='page']").val(num);
									                    $("input[name='isPage']").val(2);
									                    $("form#member_list").submit();
									                }
									            }
									        });
									     
									     
								
													
												});
									</script>                                         

									</body>
									