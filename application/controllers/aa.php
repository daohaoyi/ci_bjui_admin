<?php

/**
 * 编辑个人资料接口
 *
 */
class aa
{


    public function edit_profile($param)
    {
        $result = array(); //输出结果
        $error = array(); //错误码定义,array(error_code,error(可选))
        $config = array();//定义图片处理数组
        $configs = array();//定义图片处理数组
        $data = array();
        do {

            //待验证的数据
            $this->form_validation->set_data($param);

            //判断uid是否为空
            $this->form_validation->set_rules('uid', '', 'required');

            //判断login_token是否为空
            $this->form_validation->set_rules('login_token', '', 'required');

            //判断姓名是否为空
            $this->form_validation->set_rules('realname', '', 'required');

            //判断性别是否为空
            $this->form_validation->set_rules('sex', '', 'required');
            if ($this->form_validation->run() == FALSE) { //验证
                $error = array('error_code' => EC_REQUEST_PARAM, 'error' => implode(" && ", $this->form_validation->error_array()));
                break;
            }

            //获得用户数据
            //获得用户id
            $uid = $param['uid'];

            //获得用户姓名
            $realname = $param['realname'];

            //获得用户性别
            $sex = $param['sex'];

            //判断是否上传了头像文件
            if (!empty($_FILES)) {
                if ($_FILES['file']['error'] !== 0) {//需要确认名字
                    //1; 超过了文件大小php.ini中即系统设定的大小。
                    //2; 超过了文件大小MAX_FILE_SIZE 选项指定的值。
                    //3; 文件只有部分被上传。
                    //4; 没有文件被上传。
                    //5; 上传文件大小为0。
                    $error = array('error_code' => EC_FILES);
                    break;
                }


                //拼合文件存储路径
                $config['upload_path'] = UPLOAD_HEAD_PATH . create_upload_dir();
                if (!file_exists($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, TRUE);
                }

                //控制文件类ixng
                $config['allowed_types'] = 'jpeg|jpg|png';
                //控制文件大小
                //$config['max_size'] = '300';//php.ini
                //拼合原头像名
                $config['file_name'] = create_guid();
                //上传配置
                $this->load->library('upload', $config);//初始化文件上传类
                //上传文件
                $res = $this->upload->do_upload('file');


                //判断原图是否上传生成
                if ($res == false) {
                    $error = array('error_code' => EC_OLD_PICTURE);
                    break;
                }

                //生成缩略图
                $head = $this->upload->data();

                //给缩略图后拼接默认后缀_thumb
                $exp = explode('.', $head['file_name']);
                $exp[0] = 's_' . $exp[0] . '_thumb.';
                $imp = implode($exp);

                //拼合小头像名
                $configs['new_image'] = 's_' . $head['file_name'];
                //加载GD2库
                $configs['image_library'] = 'gd2';
                //获取原图绝对路径
                $configs['source_image'] = $head['full_path'];
                //设置图像处理函数生成缩略图
                $configs['create_thumb'] = TRUE;
                //设置图片缩放为等比缩放
                $configs['maintain_ratio'] = TRUE;
                //设置宽
                $configs['width'] = 100;
                //设置高
                $configs['height'] = 100;
                //加载图像处理类
                $small = $this->load->library('image_lib', $configs);
                //创建缩略图
                $this->image_lib->resize();
                if (!$small) {
                    $error = array('error_code' => EC_SMALL_PICTURE);
                    break;
                }
                //拼合添加到user表的数据
                $data['head_icon'] = create_upload_dir() . $head['file_name'];
                $data['small_head_icon'] = create_upload_dir() . $imp;
            }

            //拼合添加到user表的数据
            $data['realname'] = $realname;
            $data['sex'] = $sex;

            $where = array('id' => $uid);
            $ret = $this->User_model->save($where, $data);
            if ($ret == FALSE) {
                $error = array('error_code' => EC_ALBUM);
                break;
            }

            //查询编辑后的用户数据
            $datas = $this->User_model->get_one(array('id' => $uid));

            //拼合图片文件地址
            $datas['head_icon'] = base_url() . HEAD_FILE_DIR . $datas['head_icon'];
            $datas['small_head_icon'] = base_url() . HEAD_FILE_DIR . $datas['small_head_icon'];

            //释放password
            unset($datas['password']);
            //返回数据
            $result = array('user' => $datas);

        } while (FALSE);

        if (!empty($error)) {
            return $error;
        }

        return $result;
    }
}