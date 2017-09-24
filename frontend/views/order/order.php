<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>填写核对订单信息</title>
	<link rel="stylesheet" href="/style/base.css" type="text/css">
	<link rel="stylesheet" href="/style/global.css" type="text/css">
	<link rel="stylesheet" href="/style/header.css" type="text/css">
	<link rel="stylesheet" href="/style/fillin.css" type="text/css">
	<link rel="stylesheet" href="/style/footer.css" type="text/css">

	<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="/js/cart2.js"></script>

</head>
<body>
	<!-- 顶部导航 start -->
	<div class="topnav">
		<div class="topnav_bd w990 bc">
			<div class="topnav_left">
				
			</div>
			<div class="topnav_right fr">
                <ul>
                    <?php  if(Yii::$app->user->isGuest): ?>
                        <li>
                            欢迎来到南美蝶独家商城！[<a href="<?=\yii\helpers\Url::to(['member/login'])?>">登录</a>]
                            [<a href="<?=\yii\helpers\Url::to(['member/register'])?>">免费注册</a>]
                        </li>
                    <?php else: ?>
                        <li>
                            欢迎来到南美蝶独家商城！当前用户:    <?=Yii::$app->user->identity->username?>
                            [<a href="<?=\yii\helpers\Url::to(['member/logout'])?>">注销</a>]
                        </li>
                    <?php endif;?>
                    <li class="line">|</li>
                    <li>我的订单</li>
                    <li class="line">|</li>
                    <li>客户服务</li>

                </ul>
			</div>
		</div>
	</div>
	<!-- 顶部导航 end -->
	
	<div style="clear:both;"></div>
	
	<!-- 页面头部 start -->
	<div class="header w990 bc mt15">
		<div class="logo w990">
			<h2 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h2>
			<div class="flow fr flow2">
				<ul>
					<li>1.我的购物车</li>
					<li class="cur">2.填写核对订单信息</li>
					<li>3.成功提交订单</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- 页面头部 end -->
	
	<div style="clear:both;"></div>

	<!-- 主体部分 start -->
    <form action="<?=yii\helpers\Url::to(['order/order'])?>" method="post" id="signupForm">


	<div class="fillin w990 bc mt15">
		<div class="fillin_hd">
			<h2>填写并核对订单信息</h2>
		</div>

		<div class="fillin_bd">
			<!-- 收货人信息  start-->
			<div class="address">
				<h3>收货人信息</h3>
                <?php foreach ($ares as $are):?>
				<div class="address_info">
				<p>
                    <input type="radio" value="<?=$are->id?>" name="address_id" class="ren"/> <?=$are->name?>&nbsp;
                    <?=$are->tel?>&nbsp;<?=$are->s_province?>&nbsp;<?=$are->s_city?>&nbsp;
                    <?=$are->s_county?>&nbsp;<?=$are->area?>&nbsp;
                </p>

				</div>
                <?php endforeach;?>

			</div>
			<!-- 收货人信息  end-->

			<!-- 配送方式 start -->
			<div class="delivery">
				<h3>送货方式 </h3>


				<div class="delivery_select">
					<table>
						<thead>
							<tr>
								<th class="col1">送货方式</th>
								<th class="col2">运费</th>
								<th class="col3">运费标准</th>
							</tr>
						</thead>
						<tbody>
                        <?php
                      //  $freight='';
                        foreach ($deliverys as $del=>$delivery) :?>
                            <tr class="cur">
                                <td>
                                    <input type="radio" name="delivery" class="freight"   checked="checked" data-id="<?=$delivery[1]?>" value="<?=$del?>"/><?=$delivery[0]?>

                                </td>
                                <td>￥<?=$delivery[1].'.00'?></td>
                                <td><?=$delivery[2]?></td>
                            </tr>
                        <?php endforeach;?>

						</tbody>
					</table>

				</div>
			</div> 
			<!-- 配送方式 end --> 

			<!-- 支付方式  start-->
			<div class="pay">
				<h3>支付方式 </h3>


				<div class="pay_select">
					<table>
                        <?php foreach ($payments as $pay=>$payment) :?>
                            <tr class="cur">
                                <td class="col1"><input type="radio" name="pay" class="zhi" value="<?=$pay?>"/><?=$payment[0]?></td>
                                <td class="col2"><?=$payment[1]?></td>
                            </tr>
                        <?php endforeach;?>

					</table>

				</div>
			</div>
			<!-- 支付方式  end-->

			<!-- 发票信息 start-->
			<div class="receipt none">
				<h3>发票信息 </h3>


				<div class="receipt_select ">
					<form action="">
						<ul>
							<li>
								<label for="">发票抬头：</label>
								<input type="radio" name="type" checked="checked" class="personal" />个人
								<input type="radio" name="type" class="company"/>单位
								<input type="text" class="txt company_input" disabled="disabled" />
							</li>
							<li>
								<label for="">发票内容：</label>
								<input type="radio" name="content" checked="checked" />明细
								<input type="radio" name="content" />办公用品
								<input type="radio" name="content" />体育休闲
								<input type="radio" name="content" />耗材
							</li>
						</ul>						
					</form>

				</div>
			</div>
			<!-- 发票信息 end-->

			<!-- 商品清单 start -->
			<div class="goods">
				<h3>商品清单</h3>
				<table>
					<thead>
						<tr>
							<th class="col1">商品</th>
							<th class="col3">价格</th>
							<th class="col4">数量</th>
							<th class="col5">小计</th>
						</tr>	
					</thead>
					<tbody>
                    <?php
                   $total='';
                    $amount='';
                    foreach ($carts as $cart) :?>
                        <tr>
                            <td class="col1"><a href=""><img src="<?=$cart->goods['logo']?>" alt="" /></a>
                                <strong><a href=""><?=$cart->goods['name']?></a></strong></td>
                            <td class="col3"><?=$cart->goods['shop_price']?></td>
                            <td class="col4"><?=$cart->amount?></td>
                            <td class="col5"><span><?=($cart->goods['shop_price'])*($cart->amount).'.00'?></span></td>
                        </tr>
                    <?php

                    $amount+=$cart['amount'];
                    $total+=$cart->goods['shop_price']*$cart['amount'];
                    endforeach;?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5">
								<ul>
									<li>
										<span><?=$amount?>件商品，总商品金额：</span>
										<em><?=$total?></em>
									</li>
									<li>
										<span>返现：</span>
										<em></em>
									</li>
									<li>
										<span>运费：</span>
										<em class="gross"></em>
									</li>
									<li>
										<span>应付总额：</span>
										<em class="money" data-id="<?=$total?>"></em>
									</li>
								</ul>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
			<!-- 商品清单 end -->
		
		</div>

		<div class="fillin_ft">
            <p>应付总额：<strong class="money" data-id="<?=$total?>"></strong></p>
            <span>
                <input type="submit" value="提交表单"/>
            </span>

			
		</div>
	</div>
	<!-- 主体部分 end -->
    </form>
	<div style="clear:both;"></div>
	<!-- 底部版权 start -->
	<div class="footer w1210 bc mt15">
		<p class="links">
			<a href="">关于我们</a> |
			<a href="">联系我们</a> |
			<a href="">人才招聘</a> |
			<a href="">商家入驻</a> |
			<a href="">千寻网</a> |
			<a href="">奢侈品网</a> |
			<a href="">广告服务</a> |
			<a href="">移动终端</a> |
			<a href="">友情链接</a> |
			<a href="">销售联盟</a> |
			<a href="">京西论坛</a>
		</p>
		<p class="copyright">
			 © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号 
		</p>
		<p class="auth">
			<a href=""><img src="/images/xin.png" alt="" /></a>
			<a href=""><img src="/images/kexin.jpg" alt="" /></a>
			<a href=""><img src="/images/police.jpg" alt="" /></a>
			<a href=""><img src="/images/beian.gif" alt="" /></a>
		</p>
	</div>
	<!-- 底部版权 end -->




    <script type="text/javascript">

        $().ready(function() {
// 在键盘按下并释放及提交后验证提交表单
            $("#signupForm").validate({
                rules: {
                    ren:{
                        required: true//地址
                    },
                    freight:{
                        required: true//收货方式
                    },
                    zhi:{
                        required: true//支付方式
                    },
                    messages: {
                        ren:{
                            remote:"地址没选"
                        }
                        freight:{
                            remote:"收货方式没选"
                        }
                        zhi:{
                            remote:"支付方式没选"
                        }
                    },
                    errorElement:'span'//错误信息的标签
                });
        });
    </script>
</body>
</html>
