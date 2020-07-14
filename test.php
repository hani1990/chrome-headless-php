<?php
ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(-1);
ini_set('memory_limit', '-1');
include "vendor/autoload.php";

use HeadlessChromium\BrowserFactory;

$browserFactory = new BrowserFactory();
//var_dump($browserFactory);exit();
// starts headless chrome
$browser = $browserFactory->createBrowser();
$browser = $browserFactory->createBrowser([
    'headless'        => false,         // disable headless mode
   // 'connectionDelay' => 0.8,           // add 0.8 second of delay between each instruction sent to chrome,
    'ignoreCertificateErrors' => true,
    'debugLogger'     => './log' // will enable verbose mode
]);

try {
    $kw = 'chrome';
    $page = $browser->createPage();
    $page->navigate("https://www.baidu.com/s?wd={$kw}")->waitForNavigation(\HeadlessChromium\Page::LOAD, 900000);

    $i = 1;
    while(true){
        //保存页面
        save_html($page, $i, $kw);
        //在第一页的时候有下一页没有上一页class=n只有1个
        $next_page_ret = $page->evaluate("document.querySelectorAll('.n')")->getReturnValue();

        if(count($next_page_ret) <=  1){
            $next_page_text = $page->evaluate("document.querySelectorAll('.n')[0].innerText")->getReturnValue();
            //最后一页的时候只有上一页没有下一页，可以判断为全部采集完了
            if(strpos($next_page_text, '上一页')){
                //爬完了
                return 0;
            }
            $next_page_url = $page->evaluate("document.querySelectorAll('.n')[0].href")->getReturnValue();
        }else{
            $next_page_url = $page->evaluate("document.querySelectorAll('.n')[1].href")->getReturnValue();
        }
        echo "next_page_url:$next_page_url\r\n";
        $page->navigate($next_page_url)->waitForNavigation(\HeadlessChromium\Page::LOAD, 900000);


        $i = $i+1;
    }
} catch (\HeadlessChromium\Exception\OperationTimedOut $e){
    // bye
    var_dump($e->getMessage());
    $browser->close();
} catch (\HeadlessChromium\Exception\NavigationExpired $e){
// bye
    var_dump($e->getMessage());
    $browser->close();
}


function save_html($page, $i, $kw)
{
    $html = $page->evaluate("document.getElementsByTagName('html')[0].innerHTML")->getReturnValue();
    file_put_contents("./runtime/baidu/". str_replace(' ', '_', $kw) ."_{$i}.html", $html);
}