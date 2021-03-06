<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-4-14
 * Time: 上午10:00
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Ucenter\Model;


use Think\Model;

class UserTagLinkModel extends Model
{
    protected $userTagModel;
    public function _initialize()
    {
        $this->userTagModel=new UserTagModel();
    }

    /**
     * 获取用户标签列表
     * @param int $uid
     * @return null
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getUserTag($uid=0,$pid='')
    {
        !$uid&&$uid=is_login()?is_login():session('temp_login_uid');
        $tag_ids=$this->where(array('uid'=>$uid))->getField('tags');
        if($tag_ids!=''){
            $tag_ids=str_replace('[','',$tag_ids);
            $tag_ids=str_replace(']','',$tag_ids);
            $tag_ids=explode(',',$tag_ids);
            $tags=$this->userTagModel->where(array('id'=>array('in',$tag_ids),'status'=>1,'pid'=>array('neq',0)))->order('sort desc')->select();
            if(count($tags)){
                return $tags;
            }
        }
        return null;
    }
    /**
     * 获取用户标签列表
     * @param int $uid
     * @return null
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getUserOtherTag($uid=0,$pid='')
    {
        !$uid&&$uid=is_login()?is_login():session('temp_login_uid');
        $tag_other=$this->where(array('uid'=>$uid))->find();
        return $tag_other;
    }
    /**
     * 编辑用户标签链接
     * @param string $tags
     * @return bool|mixed|null
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function editData($tags='',$job='',$work_years='',$number_1='',$number_2='')
    {
        $uid=is_login()?is_login():session('temp_login_uid');
        if($tags!='' && $job!='' && $work_years!=''){
            $tags=explode(',',$tags);
            sort($tags);
            foreach($tags as &$tag){
                $tag='['.$tag.']';
            }
            unset($tag);
            $tags=implode(',',$tags);
            if($this->where(array('uid'=>$uid))->count()){
                $result=$this->saveData($tags,$job,$work_years,$number_1,$number_2);
            }else{
                $result=$this->addData($tags,$job,$work_years,$number_1,$number_2);
            }
        }else{
            $result=$this->where(array('uid'=>$uid))->delete();
        }
        return $result;
    }

    public function saveData($tags='',$job='',$work_years='',$number_1='',$number_2='')
    {
        $uid=is_login()?is_login():session('temp_login_uid');
        $data['tags']=$tags;
        $data['uid']=$uid;
        $data['job']=$job;
        $data['work_years']=$work_years;
        $data['number_1']=$number_1;
        $data['number_2']=$number_2;
        $result=$this->where(array('uid'=>$uid))->save($data);
        return $result;
    }

    public function addData($tags='',$job='',$work_years='',$number_1='',$number_2='')
    {
        $uid=is_login()?is_login():session('temp_login_uid');
        $data['tags']=$tags;
        $data['uid']=$uid;
        $data['job']=$job;
        $data['work_years']=$work_years;
        $data['number_1']=$number_1;
        $data['number_2']=$number_2;
        $result=$this->add($data);
        return $result;
    }

    public function getListByMap($map)
    {
        $list=$this->where($map)->limit(999)->select();
        !count($list)&&$list=array();
        return $list;
    }

} 