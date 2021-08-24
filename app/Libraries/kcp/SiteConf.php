<?php

namespace App\Libraries\Kcp;

class SiteConf {
    private $type;                  // 실행 타입 ("real" : 상용 / "test" : 테스트)
    private $g_conf_home_dir;       // bin 절대 경로 (bin 전까지 입력)
    private $g_conf_log_path;       // log 경로
    private $g_conf_gw_url;         // PG사 URL (New)
    private $g_conf_js_url;         // 결제창 전용 JS URL
    private $g_wsdl;                // 스마트폰 SOAP 통신
    private $g_conf_site_cd;        // Site Code
    private $g_conf_site_key;       // Site Key
    private $g_conf_site_name;      // Site Name
    private $g_conf_site_id;        // site id (cd 사용)
    private $g_conf_log_level;      //
    private $g_conf_gw_port;        // PG사 Port (New)
    private $module_type;           // 변경 불가
    private $g_conf_tx_mode;
    private $g_conf_user_type;
    public function __construct($type = "real")
    {
        $this->type = $type;
    }
    public function setSiteConfInfo($confData)
    {
        $this->setInfo($confData);
    }
    private function setInfo($confData)
    {
        $url = "paygw.kcp.co.kr";
        $jUrl = "https://pay.kcp.co.kr/plugin/payplus_web.jsp";
        $wsdl = "real_KCPPaymentService.wsdl";
        $cd = $confData['cd'];
        $key = $confData['key'];
        if ($this->type == "test")
        {
            $url = "testpaygw.kcp.co.kr";
            $jUrl = "https://testpay.kcp.co.kr/plugin/payplus_web.jsp";
            $wsdl = "test_KCPPaymentService.wsdl";
            $cd = $confData['cd'];;
            $key = $confData['key'];
        }
            
        $this->g_conf_home_dir = "/var/module/kcp/vcnt";
        $this->g_conf_user_type = "PGNW";
        $this->g_conf_log_path = "/var/module/kcp/vcnt/log";
        $this->g_conf_key_path = "/var/module/kcp/vcnt/bin/pub.key";
        $this->g_conf_gw_url = $url;
        $this->g_conf_js_url = $jUrl;
        $this->g_wsdl = $wsdl;
        $this->g_conf_site_cd = $cd;
        $this->g_conf_site_key = $key;
        $this->g_conf_site_name = $confData['sitename'];
        $this->g_conf_log_level = "4";
        $this->g_conf_gw_port = "8090";
        $this->module_type = "01";      // 변경 불가
        $this->g_conf_tx_mode = 0;
        $this->g_conf_site_id = $this->g_conf_site_cd;
    }
    public function getSiteConfInfo($name)
    {
        return $this->$name;
    }
    public function getSiteConfInfoArray()
    {
        $data['g_conf_home_dir'] = $this->g_conf_home_dir;
        $data['g_conf_key_path'] = $this->g_conf_key_path;
        $data['g_conf_log_path'] = $this->g_conf_log_path;
        $data['g_conf_gw_url'] = $this->g_conf_gw_url;
        $data['g_conf_js_url'] = $this->g_conf_js_url;
        $data['g_wsdl'] = $this->g_wsdl;
        $data['g_conf_site_cd'] = $this->g_conf_site_cd;
        $data['g_conf_site_key'] = $this->g_conf_site_key;
        $data['g_conf_site_name'] = $this->g_conf_site_name;
        $data['g_conf_log_level'] = $this->g_conf_log_level;
        $data['g_conf_gw_port'] = $this->g_conf_gw_port;
        $data['module_type'] = $this->module_type;
        $data['g_conf_user_type'] = $this->g_conf_user_type;
        $data['g_conf_tx_mode'] = $this->g_conf_tx_mode;
        $data['g_conf_site_id'] = $this->g_conf_site_id;
        return $data;
    }
    public function getConfHomeDir()
    {
        return $this->g_conf_home_dir;
    }
    public function getConfLogPath()
    {
        return $this->g_conf_log_path;
    }
    public function getConfGwUrl()
    {
        return $this->g_conf_gw_url;
    }
    public function getConfJsUrl()
    {
        return $this->g_conf_js_url;
    }
    public function getWsdl()
    {
        return $this->g_wsdl;
    }
    public function getConfSiteCd()
    {
        return $this->g_conf_site_cd;
    }
    public function getConfSiteKey()
    {
        return $this->g_conf_site_key;
    }
    public function getConfSiteName()
    {
        return $this->g_conf_site_name;
    }
    public function getConfLogLevel()
    {
        return $this->g_conf_log_level;
    }
    public function getConfGwPort()
    {
        return $this->g_conf_gw_port;
    }
    public function getModuleType()
    {
        return $this->module_type;
    }
    public function getConfTxMode()
    {
        return $this->g_conf_tx_mode;
    }
    public function getUserType(){
        return $this->g_conf_user_type;
    }
}
?>