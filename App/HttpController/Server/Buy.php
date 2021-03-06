<?php
/**
 * 购买流程
 *
 *
 *
 *
 * @copyright  Copyright (c) 2019 MoJiKeJi Inc. (http://www.fashop.cn)
 * @license    http://www.fashop.cn
 * @link       http://www.fashop.cn
 * @since      File available since Release v1.1
 */

namespace App\HttpController\Server;

use App\Biz\Pay\Notice\Facade as PayNoticeFacade;
use App\Utils\Code;

class Buy extends Server
{
	/**
	 * 计算购买项
	 * @method POST
	 * @param array $cart_ids   购物车id集合
	 * @param int   $address_id 地址id
	 */
	public function calculate()
	{
		if( $this->verifyResourceRequest() !== true ){
			$this->send( Code::user_access_token_error );
		} else{
			try{
				$user = $this->getRequestUser();
				$data = [
					'user_id'    => $user['id'],
					'cart_ids'   => $this->post['cart_ids'],
					'address_id' => $this->post['address_id'],
					'user_info'  => (array)$user,
				];

				$model_buy       = new \App\Biz\Server\Buy\Buy( $data );
				$calculateResult = $model_buy->calculate();

				$this->send( Code::success, [
					'goods_amount'         => $calculateResult->getGoodsAmount(),
					'pay_amount'           => $calculateResult->getPayAmount(),
					'goods_freight_list'   => $calculateResult->getGoodsFreightList(),
					'freight_unified_fee'  => $calculateResult->getFreightUnifiedFee(),
					'freight_template_fee' => $calculateResult->getFreightTemplateFee(),
					'pay_freight_fee'      => $calculateResult->getPayFreightFee(),
				] );
			} catch( \Exception $e ){
				$this->send( Code::server_error, [], $e->getMessage() );
			}
		}
	}

	/**
	 * 创建购买订单
	 * @method POST
	 * @param int    $way        购买途径，`cart` 购物车、`right_now` 立即购买
	 * @param int    $address_id 收货地址id
	 * @param array  $cart_ids   购物车id集合
	 * @param string $message    买家留言
	 *
	 */
	public function create()
	{
		if( $this->verifyResourceRequest() !== true ){
			$this->send( Code::user_access_token_error );
		} else{
			try{
				$user      = $this->getRequestUser();
				$model_buy = new \App\Biz\Server\Buy\Buy( [
					'user_id'    => $user['id'],
					'cart_ids'   => $this->post['cart_ids'],
					'address_id' => $this->post['address_id'],
					'message'    => isset( $this->post['message'] ) ? $this->post['message'] : null,
					'user_info'  => ['id' => $user['id'], 'username' => $user['username']],
				] );
				$result    = $model_buy->createOrder();
				if( $model_buy->getOrderId() ){
					$this->send( Code::success, [
						'order_id' => $result->getOrderId(),
						'pay_sn'   => $result->getPaySn(),
					] );
				} else{
					$this->send( Code::error );
				}
			} catch( \Exception $e ){
				$this->send( Code::server_error, [], $e->getMessage() );
			}
		}
	}

	/**
	 * 支付
	 * @method POST
	 * @param string $order_type        订单支付类型，goods_buy商品购买，predeposit预存款充值
	 * @param string $pay_sn            订单的sn码
	 * @param string $payment_code      支付方式的标识码，在该接口上级的逻辑的接口上有返回，用来调起服务器端的支付方式的策略
	 *                                  支付方式 wechat wechat_app wechat_mini wechat_wap
	 * @param string $openid            微信的openid 非必需 只有微信支付的情况下可能需要
	 * @param string $payment_channel   支付渠道 "wechat"  "wechat_mini" "wechat_app"
	 * @author   韩文博
	 */
	public function pay()
	{
		var_dump($this->post);
		if( $this->verifyResourceRequest() !== true ){
			 $this->send( Code::user_access_token_error );
		} else
			if( $this->validator( $this->post, 'Server/Buy.pay' ) !== true ){
				 $this->send( Code::param_error, [], $this->getValidator()->getError() );
			} else{
				try{
					$user    = $this->getRequestUser();
					$payment = \App\Model\Setting::init()->getSettingInfo( ['key' => $this->post['payment_code'], 'status' => 1] );
					if( !$payment ){
						 $this->send( Code::error, [], '系统不支持选定的支付方式' );
					} else{
						$pay_info   = \App\Model\OrderPay::init()->getOrderPayInfo( [
							'pay_sn'    => $this->post['pay_sn'],
							'user_id'   => $user['id'],
							'pay_state' => 0,
						] );
						$order_info = \App\Model\Order::init()->getOrderInfo( [
							'pay_sn' => $this->post['pay_sn'],
							'state'  => \App\Biz\Order::state_new,
						], 'id,pay_sn,amount,revise_amount' );
						if( empty( $pay_info ) || empty( $order_info ) ){
							 $this->send( Code::error, [], '该订单不存在' );
						} else{
							$pay_amount = ($order_info['revise_amount'] > 0) ? $order_info['revise_amount'] : $order_info['amount'];
							$options    = [];
							switch( $this->post['payment_channel'] ){
							case 'wechat_mini':
								$amount  = $pay_amount * 100;
								$options = Pay::wechat( $this->getWechatPayConfig( $payment['config'], $this->post['payment_channel'] ) )->miniapp( [
									'attach'       => 'goods_buy',
									'out_trade_no' => $order_info['pay_sn'],
									'body'         => '商品购买_'.$pay_info['pay_sn'],
									'total_fee'    => "{$amount}",
									'openid'       => $this->post['openid'] ? $this->post['openid'] : model( 'UserOpen' )->getUserOpenValue( ['user_id' => $user['id'], 'genre' => 1], '', 'mini_openid' ),
								] );
							break;
							case 'wechat_app':
								$amount  = $pay_amount * 100;
								$options = Pay::wechat( $this->getWechatPayConfig( $payment['config'], $this->post['payment_channel'] ) )->app( [
									'attach'       => 'goods_buy',
									'out_trade_no' => $order_info['pay_sn'],
									'body'         => '商品购买_'.$pay_info['pay_sn'],
									'total_fee'    => "{$amount}",
									'openid'       => $this->post['openid'] ? $this->post['openid'] : model( 'UserOpen' )->getUserOpenValue( ['user_id' => $user['id'], 'genre' => 1], '', 'app_openid' ),
								] );

								$data = (array)$options;
								unset( $options );
								$options = json_decode( $data["\0*\0data"] );

							break;
							case 'alipay_app':
								$config = $payment['config'];
								$amount             = $pay_amount;
								$order = new \EasySwoole\Pay\AliPay\RequestBean\App();
								$order->setOutTradeNo($order_info['pay_sn']);
								$order->setTotalAmount("{$amount}");
								$order->setSubject("商品购买_{$pay_info['pay_sn']}");

								$aliConfig = new \EasySwoole\Pay\AliPay\Config();
								$aliConfig->setGateWay(\EasySwoole\Pay\AliPay\GateWay::NORMAL);
								$aliConfig->setAppId($config['app_id']);
								$aliConfig->setPublicKey($config['alipay_public_key']);
								$aliConfig->setPrivateKey($config['merchant_private_key']);

								$pay = new \EasySwoole\Pay\Pay();

								$res = $pay->aliPay($aliConfig)->app($order);

								$options['content'] = $res->toArray();
							break;
							default:
								# code...
							break;
							}

							 $this->send( Code::success, $options );
						}
					}
				} catch( \Exception $e ){
					\EasySwoole\EasySwoole\Logger::getInstance()->log( $e->getMessage() );
					return $this->send( Code::server_error );
				}
			}
	}

	/**
	 * 微信异步通知处理
	 * @method GET+post
	 *
	 */
	public function wechatNotify()
	{
		try{
			$payment = \App\Model\Setting::init()->getSettingInfo( ['key' => 'wechat'] );
			$notice  = PayNoticeFacade::wechat( $this->getWechatPayConfig( $payment['config'], 'wechat' ) );
			if( $notice->check() === true ){
				$data       = $notice->getData();
				$orderLogic = new \App\Biz\Order();
				$result     = $orderLogic->pay( (string)$data->out_trade_no, 'wechat', (string)$data->transaction_id );
				if( $result ){
					$this->response()->write( 'success' );
				} else{
					\EasySwoole\EasySwoole\Logger::getInstance()->log( "微信支付处理订单失败" );
				}
			} else{
				\EasySwoole\EasySwoole\Logger::getInstance()->log( "微信支付通知验证失败" );
			}
		} catch( \Exception $e ){
			\EasySwoole\EasySwoole\Logger::getInstance()->log( "微信支付通知处理失败：".$e->getMessage() );
		}
	}

	/**
	 * todo 需要废弃
	 * 小程序回调
	 */
	public function wechatMiniNotify()
	{
		try{
			$payment = \App\Model\Setting::init()->getSettingInfo( ['key' => 'wechat'] );
			$notice  = PayNoticeFacade::wechat( $this->getWechatPayConfig( $payment['config'], 'wechat_mini' ) );
			if( $notice->check() === true ){
				$data       = $notice->getData();
				$orderLogic = new \App\Biz\Order();
				$result     = $orderLogic->pay( (string)$data->out_trade_no, 'wechat_mini', (string)$data->transaction_id );
				if( $result ){
					// todo 退款成功需要修改订单状态
					$this->response()->write( 'success' );
				} else{
					\EasySwoole\EasySwoole\Logger::getInstance()->log( "微信支付处理订单失败" );
				}
			} else{
				\EasySwoole\EasySwoole\Logger::getInstance()->log( "微信支付通知验证失败" );
			}
		} catch( \Exception $e ){
			\EasySwoole\EasySwoole\Logger::getInstance()->log( "微信支付通知处理失败：".$e->getMessage() );
		}
	}

	/**
	 * 微信app异步通知处理
	 * @method GET+post
	 *
	 */
	public function wechatAppNotify()
	{
		try{
			$payment = \App\Model\Setting::init()->getSettingInfo( ['key' => 'wechat'] );
			$notice  = PayNoticeFacade::wechat( $this->getWechatPayConfig( $payment['config'], 'wechat_app' ) );
			if( $notice->check() === true ){
				$data       = $notice->getData();
				$orderLogic = new \App\Biz\Order();
				$result     = $orderLogic->pay( (string)$data->out_trade_no, 'wechat_app', (string)$data->transaction_id );
				if( $result ){
					$this->response()->write( 'success' );
				} else{
					\EasySwoole\EasySwoole\Logger::getInstance()->log( "微信支付处理订单失败" );
				}
			} else{
				\EasySwoole\EasySwoole\Logger::getInstance()->log( "微信支付通知验证失败" );
			}
		} catch( \Exception $e ){
			\EasySwoole\EasySwoole\Logger::getInstance()->log( "微信支付通知处理失败：".$e->getMessage() );
		}
	}

	/**
	 * 支付宝app异步通知处理
	 * @method GET+post
	 */
	public function alipayAppNotify()
	{
		try{
			$payment = \App\Model\Setting::init()->getSettingInfo( ['key' => 'alipay'] );
			$notice  = PayNoticeFacade::alipay( $this->getAliPayConfig( $payment['config'], 'alipay_app' ) );
			if( $notice->check() === true ){
				$data       = $notice->getData();
				$orderLogic = new \App\Biz\Order();
				$result     = $orderLogic->pay( (string)$data->out_trade_no, 'alipay_app', (string)$data->trade_no );
				if( $result ){
					$this->response()->write( 'success' );
				} else{
					\EasySwoole\EasySwoole\Logger::getInstance()->log( "支付宝支付处理订单失败" );
				}
			} else{
				\EasySwoole\EasySwoole\Logger::getInstance()->log( "支付宝支付通知验证失败" );
			}
		} catch( \Exception $e ){
			\EasySwoole\EasySwoole\Logger::getInstance()->log( "支付宝支付通知处理失败：".$e->getMessage() );
		}
	}

	/**
	 * @param array  $config
	 * @param string $payment_channel 支付渠道
	 * @return array
	 */
	private function getWechatPayConfig( array $config, string $payment_channel ) : array
	{
		$notify_url = "";
		switch( $payment_channel ){
		case 'wechat':
			$notify_url = (isset( $config['callback_domain'] ) ? $config['callback_domain'] : $this->request->domain())."/Server/Buy/wechatNotify";
		break;
		case 'wechat_mini':
			$notify_url = (isset( $config['callback_domain'] ) ? $config['callback_domain'] : $this->request->domain())."/Server/Buy/wechatMiniNotify";
		break;
		case 'wechat_app':
			$notify_url = (isset( $config['callback_domain'] ) ? $config['callback_domain'] : $this->request->domain())."/Server/Buy/wechatAppNotify";
		break;
		}

		return [
			'appid'       => isset( $config['appid'] ) ? $config['appid'] : null,// APP APPID
			'app_id'      => isset( $config['app_id'] ) ? $config['app_id'] : null, // 公众号 APPID
			'miniapp_id'  => isset( $config['mini_app_id'] ) ? $config['mini_app_id'] : null,// 小程序 APPID
			'mch_id'      => isset( $config['mch_id'] ) ? $config['mch_id'] : null,
			'key'         => isset( $config['key'] ) ? $config['key'] : null,
			'notify_url'  => $notify_url,
			'cert_client' => null,
			'cert_key'    => null,
			'log'         => [
				'file'  => EASYSWOOLE_ROOT.'/Log/wechatpay.log',
				'level' => 'debug', // todo
			],
		];
	}

	/**
	 * @param array  $config
	 * @param string $payment_channel 支付渠道
	 * @return array
	 */
	private function getAliPayConfig( array $config, string $payment_channel ) : array
	{
		$notify_url = "";
		switch( $payment_channel ){
		case 'alipay_web':
			$notify_url = (isset( $config['callback_domain'] ) ? $config['callback_domain'] : $this->request->domain())."/Server/Buy/alipayWebNotify";
		break;
		case 'alipay_wap':
			$notify_url = (isset( $config['callback_domain'] ) ? $config['callback_domain'] : $this->request->domain())."/Server/Buy/alipayWapNotify";
		break;
		case 'alipay_app':
			$notify_url = (isset( $config['callback_domain'] ) ? $config['callback_domain'] : $this->request->domain())."/Server/Buy/alipayAppNotify";
		break;
		}
		return [
			'app_id'         => isset( $config['app_id'] ) ? $config['app_id'] : null,// APP APPID,
			'notify_url'     => $notify_url,
			'return_url'     => '',
			'ali_public_key' => $config['alipay_public_key'], //加密方式： **RSA2**
			'private_key'    => $config['merchant_private_key'],
			'log'            => [
				'file'     => EASYSWOOLE_ROOT.'/Runtime/Log/alipay.log',
				'level'    => 'debug', // 建议生产环境等级调整为 info，开发环境为 debug
				'type'     => 'single', // optional, 可选 daily.
				'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
			],
			'http'           => [
				'timeout'         => 5.0,
				'connect_timeout' => 5.0,
				// 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
			],
			// 'mode' => 'dev', // optional,设置此参数，将进入沙箱模式
		];

	}

}
