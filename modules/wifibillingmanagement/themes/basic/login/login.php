<?php 
	use yii\helpers\Html;
	use app\modules\wifibillingmanagement\themes\basic\myasset\LoginAsset;
	LoginAsset::register($this);
	$baseUrl = $this->assetBundles[LoginAsset::className()]->baseUrl.'/';
	//$assets = '@app/modules/membermanagement/themes/basic/static';
	//$baseUrl = Yii::$app->assetManager->publish($assets);
?>
<?php 
	use Yii as myyii;
	$weburl=Yii::$app->params['weburl'];
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Welcome to Wifi Admin</title>
        <meta name="description" content="User login page" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <!-- basic styles -->
		<?=Html::cssFile('@web/assets/css/bootstrap.min.css')?>
		<?=Html::cssFile('@web/assets/css/font-awesome.min.css')?>
       
        <!--[if IE 7]>
          <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
        <![endif]-->

        <!-- page specific plugin styles -->

        <!-- fonts -->

      
		<?=Html::cssFile('@web/assets/css/ace-fonts.css')?>
		        <!-- ace styles -->
		<?=Html::cssFile('@web/assets/css/ace.min.css')?>
		<?=Html::cssFile('@web/assets/css/ace-rtl.min.css')?>
       
        <!--[if lte IE 8]>
          <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
        <![endif]-->

        <!-- inline styles related to this page -->

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

        <!--[if lt IE 9]>
        <script src="assets/js/html5shiv.js"></script>
        <script src="assets/js/respond.min.js"></script>
        <![endif]-->
        <style>
            .lang *{display: block; height: 100%; line-height: 100%; float: right}
        </style>
    </head>
    <body class="login-layout" style="background-size:cover;background:url('<?php echo $baseUrl?>assets/myimages/skin/login_background_1.jpg') repeat scroll 0 0 / cover  rgba(0, 0, 0, 0)">
        <div class="main-container" style="margin-top:25px;">
            <div class="main-content">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="login-container">
                            <div class="center">
                                <h2>
                                
                                    <span class="black">Welcome to Wifi Admin</span>
                                </h2>
                            </div>

                            <div class="space-6"></div>

                            <div class="position-relative">
                                <div id="login-box" class="login-box visible widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                         
                                            <h4 class="header blue lighter bigger" style="margin-top: 9%">
                                                <i class=""></i>
                                             	Please Login
                                            </h4>      
                                            <?php
                                            if (isset($login_state)) {
                                                echo '<p for="login_error" class="login-error red">' .'name or password was error,pleace input,too!'.'</p>';
                                            }
                                            ?>
                                            <div class="form" id="validation-form">
											 	<?php
												use yii\widgets\ActiveForm;
											
												?>
			                                    <?php
                                                $form = ActiveForm::begin( array(
                                                    'id' => 'login-form',
                                                    //'enableClientValidation' => true,
                                                	  'options' => ['class' => 'form-horizontal'],
                                                ));
                                                ?>
                                                
                                                <fieldset>
                                                    <div class="form-group">
                                                        <label class="block clearfix">
                                                            <span class="block input-icon input-icon-right">
                                                                <input type="text" name="username" class="form-control" placeholder="username" />
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="block clearfix">
                                                            <span class="block input-icon input-icon-right">
                                                                <input type="password" name="password" class="form-control" placeholder="password" />
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <div class="space"></div>

                                                    <div class="clearfix">
                                                     <!--    <label class="inline">
                                                            <input type="checkbox" name="rememberMe" class="ace" />
                                                            <span class="lbl"></span>
                                                        </label> -->
															<!-- echo $model->getAttributeLabel('rememberMe'); -->
                                                        <button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
                                                         		submit
                                                        </button>
                                                        <a href="resetpassword">Reset Password</a>
                                                    </div>

                                                    <div class="space-4"></div>
                                                </fieldset>
                                               <?php ActiveForm::end(); ?>
                                            </div>

                                        </div>
                                    </div><!-- /widget-body -->
                                </div><!-- /login-box -->
                            </div><!-- /position-relative -->
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div>
        </div><!-- /.main-container -->

        <!-- basic scripts -->

        <!--[if !IE]> -->

        <script type="text/javascript">
            window.jQuery || document.write("<script src='<?php echo $baseUrl?>assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
        </script>

        <!-- <![endif]-->

        <!--[if IE]>
        <script type="text/javascript">
         window.jQuery || document.write("<script src='assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
        </script>
        <![endif]-->

        <script type="text/javascript">
            if ("ontouchend" in document)
                document.write( <?=Html::jsFile('@web/assets/js/jquery.mobile.custom.min.js')?>);
        </script>

        <!-- page specific plugin scripts -->
		<?=Html::jsFile('@web/assets/js/jquery.validate.min.js')?>

        <!-- inline scripts related to this page -->

        <script type="text/javascript">
            jQuery(function ($) {

                $('#login-form').validate({
                    errorElement: 'div',
                    errorClass: 'help-block',
                    focusInvalid: false,
                    rules: {
                        username: {
                            required: true
                        },
                        password: {
                            required: true,
                            minlength: 5
                        }
                    },
                    messages: {
                        username: {
                            required: "name can't is empty!"
                        },
                        password: {
                            required: "password can't is empty!",
                            minlength: "password can't small to 5!"
                        }
                    },
                    highlight: function (e) {
                        $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                    },
                    success: function (e) {
                        $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
                        $(e).remove();
                    },
                    errorPlacement: function (error, element) {
                        error.insertAfter(element.parent());
                    },
                    submitHandler: function (form) {
                        form.submit();
                    }
                });
                <?php $massage=isset($_GET['massage'])?$_GET['massage']:''?>
            	<?php if($massage=='success'){/* 重置密码判断*/
        			?>
        			alert('Reset Password is success');
        			window.location = "<?php echo $weburl?>/login/login";
        			<?php
        		}	
        		?>
               
            });
        </script>
    </body>
</html>
