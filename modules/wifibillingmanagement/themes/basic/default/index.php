<?php
$this->title = 'Wifi Billing Management';
use app\modules\wifibillingmanagement\themes\basic\myasset\ThemeAsset;

ThemeAsset::register($this);
$baseUrl = $this->assetBundles[ThemeAsset::className()]->baseUrl . '/';

//$assets = '@app/modules/membermanagement/themes/basic/static';
//$baseUrl = Yii::$app->assetManager->publish($assets);

?>

<!-- content start -->
<div class="r content" id="user_content">
    <div class="topNav">Route Manage&nbsp;&gt;&gt;&nbsp;<a href="#">User</a></div>
    <div class="search">
        <label>
            <span>User Name:</span>
            <input type="text"></input>
        </label>
        <label>
            <span>Role:</span>
            <select>
                <option>公司经理</option>
            </select>
        </label>
        <label>
            <span>Status:</span>
            <select>
                <option>All</option>
            </select>
        </label>
        <span class="btn"><input type="button" value="SEARCH"></input></span>
    </div>
    <div class="searchResult">
        <table>
            <thead>
            <tr>
                <th><input type="checkbox"></input></th>
                <th>Number</th>
                <th>Account</th>
                <th>User Name</th>
                <th>Role</th>
                <th>Last Log In</th>
                <th>Last Modified PWD Time</th>
                <th>Status</th>
                <th>Operate</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><input type="checkbox"></input></td>
                <td>1</td>
                <td>BiSheng</td>
                <td>毕升科技</td>
                <td>公司经理</td>
                <td>17/03/2016 16:17:50</td>
                <td>17/03/2016 16:17:50</td>
                <td>可用</td>
                <td>
                    <a href="#"><img src="<?=$baseUrl ?>images/write.png"></a>
                    <a href="#"><img src="<?=$baseUrl ?>images/delete.png"></a>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></input></td>
                <td>1</td>
                <td>BiSheng</td>
                <td>毕升科技</td>
                <td>公司经理</td>
                <td>17/03/2016 16:17:50</td>
                <td>17/03/2016 16:17:50</td>
                <td>可用</td>
                <td>
                    <a href="#"><img src="<?=$baseUrl ?>images/write.png"></a>
                    <a href="#"><img src="<?=$baseUrl ?>images/delete.png"></a>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></input></td>
                <td>1</td>
                <td>BiSheng</td>
                <td>毕升科技</td>
                <td>公司经理</td>
                <td>17/03/2016 16:17:50</td>
                <td>17/03/2016 16:17:50</td>
                <td>可用</td>
                <td>
                    <a href="#"><img src="<?=$baseUrl ?>images/write.png"></a>
                    <a href="#"><img src="<?=$baseUrl ?>images/delete.png"></a>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></input></td>
                <td>1</td>
                <td>BiSheng</td>
                <td>毕升科技</td>
                <td>公司经理</td>
                <td>17/03/2016 16:17:50</td>
                <td>17/03/2016 16:17:50</td>
                <td>可用</td>
                <td>
                    <a href="#"><img src="<?=$baseUrl ?>images/write.png"></a>
                    <a href="#"><img src="<?=$baseUrl ?>images/delete.png"></a>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></input></td>
                <td>1</td>
                <td>BiSheng</td>
                <td>毕升科技</td>
                <td>公司经理</td>
                <td>17/03/2016 16:17:50</td>
                <td>17/03/2016 16:17:50</td>
                <td>可用</td>
                <td>
                    <a href="#"><img src="<?=$baseUrl ?>images/write.png"></a>
                    <a href="#"><img src="<?=$baseUrl ?>images/delete.png"></a>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></input></td>
                <td>1</td>
                <td>BiSheng</td>
                <td>毕升科技</td>
                <td>公司经理</td>
                <td>17/03/2016 16:17:50</td>
                <td>17/03/2016 16:17:50</td>
                <td>可用</td>
                <td>
                    <a href="#"><img src="<?=$baseUrl ?>images/write.png"></a>
                    <a href="#"><img src="<?=$baseUrl ?>images/delete.png"></a>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></input></td>
                <td>1</td>
                <td>BiSheng</td>
                <td>毕升科技</td>
                <td>公司经理</td>
                <td>17/03/2016 16:17:50</td>
                <td>17/03/2016 16:17:50</td>
                <td>可用</td>
                <td>
                    <a href="#"><img src="<?=$baseUrl ?>images/write.png"></a>
                    <a href="#"><img src="<?=$baseUrl ?>images/delete.png"></a>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="records">Records:<span>26</span></p>
        <div class="btn">
            <input type="button" value="Add"></input>
            <input type="button" value="Del Selected"></input>
        </div>
        <div class="pageNum">
					<span>
						<a href="#" class="active">1</a>
						<a href="#">2</a>
						<a href="#">》</a>
						<a href="#">Last</a>
					</span>
        </div>
    </div>
</div>
<!-- content end -->
