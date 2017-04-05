<?php
class IndexController extends BaseController {
    public function IndexAction(){
        $this->assign('title' , 'GSK日志系统');
        $t = new LogData();

        foreach ($_REQUEST as $key => $value)
        {
            if( $key == 'query_text' )
            {
                $_SESSION[ $key ] .= ' ' . $value;   
            }
            else
            {
                $_SESSION[ $key ] = $value;   
            }

            $_SESSION[ $key ] = trim( $_SESSION[ $key ] );
        }
        $_SESSION['query_page'] = 0;
        if( isset( $_GET['query_page'] ) )
        {
            $_SESSION['query_page'] = $_GET['query_page'];
        }
        $this->assign('data' , $t->get( $_SESSION ));
        $this->assign('param' , $_SESSION);
        $this->display();
    }
    public function UrlAction(){
        echo 'url测试成功';
    }
    public function RedirectAction(){
        $this->redirect('http://www.baidu.com');
    }
    public function AjaxAction(){
        $ret = array(
            'result' => true,
            'data'   => 123,
        );
        $this->AjaxReturn($ret);   
    }
    public function CommonAction(){
    }
    public function AutoLoadAction(){
        $t = new LogData();
        echo $t->get();
    }
    public function WidgetAction(){
        $this->display();
    }
    public function LogAction(){
        Log::fatal('something');
        Log::warn('something');
        Log::notice('something');
        Log::debug('something');
        Log::sql('something');
    }
}
