<?php

namespace app\index\controller;

use app\commom\common;
use app\index\model\Test;
use think\Collection;
use \Firebase\JWT\JWT;
use Redis As Rediss;
use app\commom\Jsonmsg;
use think\Db;
class Index extends common
{

    /**
     * Index constructor.
     * @param array $items
     * 你看这是个测试可视化工具的工具测试 继续搞事 我测试会不会冲突
     */
    public function __construct($items = [])
    {
        parent::__construct($items);
    }


    public function index()
    {


        $user = model('AirportData');
        $data = $user->select();
        echo "<pre>";
        print_r($data);


        exit();
//        $data = Db::name('airport_data')->select();
//        $data = [
//            'yes'=>[1,2,3],
//            'none'=>['o','p','q']
//        ];
        return parent::jsonmag(1, '返回成功', $data);


        exit();
        $key = '344'; //key
        $time = time(); //当前时间
        //公用信息
        $token = [
            'iss' => 'http://www.hello.net', //签发者 可选
            'iat' => $time, //签发时间
            'data' => [ //自定义信息，不要定义敏感信息
                'userid' => 1,
                'username' => '云云'
            ]
        ];
        $access_token = $token;
        $access_token['scopes'] = 'role_access'; //token标识，请求接口的token
        $access_token['exp'] = $time + 7200; //access_token过期时间,这里设置2个小时
        $refresh_token = $token;
        $refresh_token['scopes'] = 'role_refresh'; //token标识，刷新access_token
        $refresh_token['exp'] = $time + (86400 * 30); //access_token过期时间,这里设置30天
        $jsonList = [
            'access_token' => JWT::encode($access_token, $key),
            'refresh_token' => JWT::encode($refresh_token, $key),
            'token_type' => 'bearer' //token_type：表示令牌类型，该值大小写不敏感，这里用bearer
        ];
        Header("HTTP/1.1 201 Created");
        echo json_encode($jsonList); //返回给客户端token信息
        exit();

        /**
         * redis 的出队方法
         */
        $redis = new Rediss();
        $redis->connect('127.0.0.1', 6379);
        $value = $redis->lpop('myqueue');
        if ($value) {
            echo "出队的值" . $value;
        } else {
            echo "出队完成";
        }
        exit();


        /**
         * redis 的入队方法
         */
        $redis = new Rediss();
        $redis->connect('127.0.0.1', 6379);
        $arr = array('c', 'c++', 'php', 'java', 'go', 'python');
        foreach ($arr as $k => $v) {
            $redis->rpush("myqueue", $v);
            echo $k . "号入队成功" . "<br/>";
            /*
             *  0号入队成功
             *  1号入队成功
             *  2号入队成功
             *  3号入队成功
             *  4号入队成功
             *  5号入队成功
             */

        }
        exit();

        /**
         * token的签发方式 含有公钥与私钥
         */
        $key = '344'; //key
        $time = time(); //当前时间
        //公用信息
        $token = [
            'iss' => 'http://www.hello.net', //签发者 可选
            'iat' => $time, //签发时间
            'data' => [ //自定义信息，不要定义敏感信息
                'userid' => 1,
                'username' => '云云'
            ]
        ];
        $access_token = $token;
        $access_token['scopes'] = 'role_access'; //token标识，请求接口的token
        $access_token['exp'] = $time + 7200; //access_token过期时间,这里设置2个小时
        $refresh_token = $token;
        $refresh_token['scopes'] = 'role_refresh'; //token标识，刷新access_token
        $refresh_token['exp'] = $time + (86400 * 30); //access_token过期时间,这里设置30天
        $jsonList = [
            'access_token' => JWT::encode($access_token, $key),
            'refresh_token' => JWT::encode($refresh_token, $key),
            'token_type' => 'bearer' //token_type：表示令牌类型，该值大小写不敏感，这里用bearer
        ];
        Header("HTTP/1.1 201 Created");
        echo json_encode($jsonList); //返回给客户端token信息
        exit();


        $key = '344'; //key
        $time = time(); //当前时间
        $token = [
            'iss' => 'token', //签发者 可选
            'aud' => 'token', //接收该JWT的一方，可选
            'iat' => $time, //签发时间
            'nbf' => $time, //(Not Before)：某个时间点后才能访问，比如设置time+30，表示当前时间30秒后才能使用
            'exp' => $time + 7200, //过期时间,这里设置2个小时
            'data' => [ //自定义信息，不要定义敏感信息
                'userid' => 8,
                'username' => '李小龙'
            ]
        ];
        echo JWT::encode($token, $key); //输出Token
    }

    /**
     * 单表新增
     */
    public function addname()
    {
        $data = [
            'name' => '倪康盛',
            'sex' => '1'
        ];
        $test = new Test($data);

        $test->allowField(['name'])->save();
//        $test->data($data);
//        $test->save();
    }

    /**
     * 单表修改
     */
    public function updataname()
    {
        $data = [
            'name' => '倪康盛',
            'sex' => '1'
        ];
        $where = [
            'id' => 2
        ];
        $test = new Test();

        $test->save($data, $where);
//        $test->allowField(['name'])->save();
//        $test->data($data);
//        $test->save();
    }


    /**
     * @throws \think\exception\DbExceptiondan
     * 单表删除
     */
    public function deletname()
    {
        $test = Test::get(2);
        $test->delete();
    }


    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 条件查询 指定具体字段
     */
    public function selename()
    {
        $test = new Test();
        $where = [
            'name' => '倪康盛',
            'id' => ['>=', '3']
        ];
        $dara = $test->where($where)->field('*')->select();
//        $dara = $test->Sex;
//        echo "<pre>";
        return parent::jsonmag('200', '返回成功', $dara);
//        print_r($dara);
    }


    /**
     * @throws \think\exception\DbException
     * 根据账号状态
     */
    public function status()
    {
        $test = Test::get(1);
        echo $test->sex;
    }

    public function joinname()
    {
//        echo
//        phpinfo();
//        die;
        $in = new Test();
        $data = $in->with(['address','school'])->select();
        return parent::jsonmag(1, '返回成功', $data);
    }


    /**
     * token 的验证方式验证key等
     */
    public function tokenopen()
    {
        $key = '344'; //key要和签发的时候一样
        $jwt = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC93d3cuaGVsbG8ubmV0IiwiaWF0IjoxNTg1NTU3OTI1LCJkYXRhIjp7InVzZXJpZCI6MSwidXNlcm5hbWUiOiJcdTRlOTFcdTRlOTEifSwic2NvcGVzIjoicm9sZV9hY2Nlc3MiLCJleHAiOjE1ODU1NjUxMjV9.oBdS5F6NMYYntCjfwfQjgPEMqc-JMSKd98AZVuSuqAE'; //签发的Token
        try {
            JWT::$leeway = 60;//当前时间减去60，把时间留点余地
            $decoded = JWT::decode($jwt, $key, ['HS256']); //HS256方式，这里要和签发的时候对应
            $arr = (array)$decoded;
            echo "<pre>";
            print_r($arr);
        } catch (\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
            echo $e->getMessage();
        } catch (\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
            echo $e->getMessage();
        } catch (\Firebase\JWT\ExpiredException $e) {  // token过期
            echo $e->getMessage();
        } catch (Exception $e) {  //其他错误
            echo $e->getMessage();
        }
    }

    /**
     * @param \app\commom\Demo $demo
     * @return string
     * 这是依赖注入方法
     */
    public function hello(\app\commom\Demo $demo)
    {
        $demo->setName('think');
        return $demo->getName();
    }

//    public static $sta = '这是静态方法';

    public static function stas()
    {
        return '这是静态function';
    }

    public function sta()
    {
        return self::stas();
    }

    public function thissta()
    {
        return Index::stas();
    }


}
