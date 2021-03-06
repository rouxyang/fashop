<?php

namespace App\HttpController\Admin;

use ezswoole\Db;
use App\Utils\Code;
use EasySwoole\Config;

/**
 * 权限管理
 * Class Auth
 * @package App\HttpController\Admin
 */
class Auth extends Admin
{
	/**
	 * 角色组授权
	 * @method POST
	 * @param array $rule_ids 节点id数组
	 * @param int   $id       组id
	 */
	public function groupAuthorise()
	{
		if( $this->validator( $this->post, 'Admin/AuthGroup.groupAuthorise' ) !== true ){
			return $this->send( Code::param_error, [], $this->getValidator()->getError() );
		} else{
			$auth_group_model = model( 'AuthGroup' );
			$auth_group_model->editAuthGroup( ['id' => $this->post['id']], ['rule_ids' => $this->post['rule_ids']] );
			return $this->send();
		}
	}

	/**
	 * 角色组列表
	 * @method GET
	 */
	public function groupList()
	{
		$model                  = model( 'AuthGroup' );
		$result['list']         = \App\Model\Page::getAuthGroupList( [], '*', 'id desc', $this->getPageLimit() );
		$result['total_number'] = \App\Model\Page::count();
		return $this->send( Code::success, $result );
	}

	/**
	 * 角色组的信息
	 * 包含了这个组的节点权限id
	 * @method  GET
	 * @param int $id
	 */
	public function groupInfo()
	{
		if( $this->validator( $this->get, 'Admin/AuthGroup.groupInfo' ) !== true ){
			return $this->send( Code::param_error, [], $this->getValidator()->getError() );
		} else{
			$info = \App\Model\AuthGroup::getAuthGroupInfo( ['id' => $this->get['id']] );
			return $this->send( Code::success, ['info' => $info] );
		}
	}

	/**
	 * 角色组内成员
	 * @method     GET
	 * @param int $id
	 */
	public function groupMemberList()
	{
		if( $this->validator( $this->get, 'Admin/AuthGroup.groupMemberList' ) !== true ){
			return $this->send( Code::param_error, [], $this->getValidator()->getError() );
		} else{
			$user_ids     = model( 'AuthGroupAccess' )->where( ['group_id' => $this->get['id']] )->column( 'uid' );
			$list         = [];
			if( !empty( $user_ids ) ){
				$condition['id'] = ['in', $user_ids];
				$userModel       = model( 'User' );
				$list            = $userModel->getUserList( $condition, 'id,nickname,phone,name,avatar,email,sex', 'id asc',[1,1000]);
			}
			return $this->send( Code::success, [
				'list'         => $list,
			] );
		}
	}

	/**
	 * 组内成员修改
	 * @param array $member_ids 所有成员id
	 * @param int   $id         组ID
	 * @method POST
	 */
	public function groupMemberEdit()
	{
		if( $this->validator( $this->post, 'Admin/AuthGroupAccess.groupMemberEdit' ) !== true ){
			return $this->send( Code::param_error, [], $this->getValidator()->getError() );
		} else{
			$post  = $this->post;
			$model = model( 'AuthGroupAccess' );
			\App\Model\AuthGroupAccess::init()->delAuthGroupAccess( ['group_id' => $this->post['id']] );
			\App\Model\AuthGroupAccess::init()->addMultiAuthGroupAccess( collect( $this->post['member_ids'] )->map( function( $uid ) use ( $post ){
				return [
					'uid'      => $uid,
					'group_id' => $post['id'],
				];
			} ) );
			return $this->send();
		}
	}

	/**
	 * 角色组添加
	 * @method POST
	 * @param string $name
	 */
	public function groupAdd()
	{
		if( $this->validator( $this->post, 'Admin/AuthGroup.add' ) !== true ){
			return $this->send( Code::param_error, [], $this->getValidator()->getError() );
		} else{
			\App\Model\AuthGroup::init()->addAuthGroup( $this->post );
			return $this->send();
		}
	}

	/**
	 * 角色组修改
	 * @method POST
	 * @param int    $id
	 * @param string $title
	 */
	public function groupEdit()
	{
		if( $this->validator( $this->post, 'Admin/AuthGroup.edit' ) !== true ){
			return $this->send( Code::param_error, [], $this->getValidator()->getError() );
		} else{
			\App\Model\AuthGroup::init()->editAuthGroup( ['id' => $this->post['id']], $this->post );
			return $this->send();
		}
	}

	/**
	 * 角色组删除
	 * @method POST
	 * @param int $id
	 */
	public function groupDel()
	{
		if( $this->validator( $this->post, 'Admin/AuthGroup.del' ) !== true ){
			return $this->send( Code::param_error, [], $this->getValidator()->getError() );
		} else{
			\App\Model\AuthGroup::init()->delAuthGroup( ['id' => $this->post['id']] );
			model( 'AuthGroupAccess' )->delAuthGroupAccess( ['group_id' => $this->post['id']] );
			return $this->send();
		}
	}

	/**
	 * 节点树形结构
	 * @method     GET
	 */
	public function ruleTree()
	{
		$list = \App\Model\AuthRule::getAuthRuleList( [], 'id,sign,title,status,type,pid,is_system,sort,is_display', 'sort asc', '1,5000' );
		$tree = \App\Utils\Tree::listToTree( $list, 'id', 'pid', '_child' );
		return $this->send( Code::success, ['tree' => $tree] );
	}

	/**
	 * 节点详情
	 * @method     GET
	 * @param int $id
	 */
	public function ruleInfo()
	{
		if( $this->validator( $this->get, 'Admin/AuthRule.info' ) !== true ){
			return $this->send( Code::param_error, [], $this->getValidator()->getError() );
		} else{
			$info = \App\Model\AuthRule::getAuthRuleInfo( ['id' => $this->get['id']] );
			return $this->send( Code::success, ['data' => ['info' => $info]] );
		}
	}

	/**
	 * 节点规则添加
	 * @method POST
	 * @param string $sign       节点标识
	 * @param string $title      名称
	 * @param string $pid        父级id
	 * @param int    $is_display 0不显示1显示
	 */
	public function ruleAdd()
	{
		if( $this->validator( $this->post, 'Admin/AuthRule.add' ) !== true ){
			return $this->send( Code::param_error, [], $this->getValidator()->getError() );
		} else{
			\App\Model\AuthRule::init()->addAuthRule( $this->post );
			return $this->send();
		}
	}

	/**
	 * 节点规则修改
	 * @method POST
	 * @param string $id         节点id
	 * @param string $sign       节点标识
	 * @param string $title      名称
	 * @param string $pid        父级id
	 * @param int    $is_display 0不显示1显示
	 */
	public function ruleEdit()
	{
		if( $this->validator( $this->post, 'Admin/AuthRule.edit' ) !== true ){
			return $this->send( Code::param_error, [], $this->getValidator()->getError() );
		} else{
			\App\Model\AuthRule::init()->editAuthRule( ['id' => $this->post['id']], $this->post );
			return $this->send();
		}
	}

	/**
	 * 节点规则删除
	 * @method POST
	 * @param array $id
	 */
	public function ruleDel()
	{
		if( $this->validator( $this->post, 'Admin/AuthRule.del' ) !== true ){
			return $this->send( Code::param_error, [], $this->getValidator()->getError() );
		} else{
			$model        = model( 'AuthRule' );
			$exsist_child = \App\Model\Page::init()->where( ['pid' => $this->post['id']] )->column( 'id' );
			if( $exsist_child )
				return $this->send( Code::error, [], '存在子项不可删除' );
			if( \App\Model\Page::init()->delAuthRule( ['id' => $this->post['id'], 'is_system' => 0] ) ){
				return $this->send();
			} else{
				return $this->send( Code::error, [], '系统节点不可删除或者不存在该节点' );
			}
		}
	}

	/**
	 * 节点排序
	 * @method POST
	 * @param array $sorts [{id:d,index:d}]
	 */
	public function ruleSort()
	{
		if( $this->validator( $this->post, 'Admin/AuthRule.sort' ) !== true ){
			return $this->send( Code::param_error, [], $this->getValidator()->getError() );
		} else{
			$is_display_order = $this->post['sorts'];
			$sql              = "UPDATE ".Config::getInstance()->getConf( 'database.prefix' )."auth_rule SET sort = CASE id ";
			$ids              = [];
			foreach( $is_display_order as $sort ){
				$ids[] = $sort['id'];
				$sql   .= sprintf( "WHEN %d THEN %d ", $sort['id'], $sort['index'] ); // 拼接SQL语句
			}
			$ids_string = implode( ',', $ids );
			$sql        .= "END WHERE id IN ($ids_string)";
			Db::query( $sql );
			return $this->send();
		}
	}

	/**
	 * 生成权限
	 */
	public function genRule()
	{
		try{
			$doc         = new \App\Utils\ControllerAction( 'Admin' );
			$action_list = $doc->getAllFunctionList();
			$db = Db::name('AuthRule');
			if( $action_list ){
				// 清理表
				Db::query( 'truncate table ez_auth_rule' );
				$type = 'admin';
				$sql = "INSERT INTO ez_auth_rule (sign,type,title,pid,is_system,is_display) VALUES ";
				foreach( $action_list as $index => $item ){
					$name = strtolower($item['name']);
					$sql .= "('{$name}','{$type}','{$item['title']}',0,1,1),";
				}
				$pid = 1;
				$node_add = array_merge(\App\Biz\Admin\Auth::$notAuthAction,\App\Biz\Admin\Auth::$noNeedAuthActionCheck);
				foreach( $action_list as $index => $item ){
					foreach( $item['actions'] as $sub ){
						$sign = strtolower($item['name']."/".$sub['name']);
						// 去掉不需要验证的节点
						if(!in_array($sign,$node_add)){
							$sql .= "('{$sign}','{$type}','{$sub['title']}',{$pid},1,1),";
						}
					}
					$pid ++;
				}
				$sql = rtrim($sql,",");
				$db->query($sql);
			}
			$this->send( Code::success );
		} catch( \Exception $e ){
			$this->send( Code::error, [], $e->getMessage() );
		}
	}
}