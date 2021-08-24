<?php
namespace App\Controllers\Kcb;

use App\Controllers\BaseController;
class PhonePopup2 extends BaseController
{
    public function index()
    {
        $req = $_REQUEST;
    
        $SITE_NAME = $req['sitename'];
        $SITE_URL = $req['siteurl'];
        $RETURN_URL = $req['returnurl'];
        
        $CP_CD = 'V43600000000';
        $RQST_CAUS_CD = '01';
        $target = 'PROD';
        $popupUrl = 'https://safe.ok-name.co.kr/CommonSvl';
        $license = "/home/ubuntu/{$CP_CD}_IDS_01_{$target}_AES_license.dat";
        $params  = '{"CP_CD":"'.$CP_CD.'",';
        $params .= '"RETURN_URL":"'.$RETURN_URL.'",';
        $params .= '"SITE_NAME":"'.$SITE_NAME.'",';
        $params .= '"SITE_URL":"'.$SITE_URL.'",';
        $params .= '"RQST_CAUS_CD":"'.$RQST_CAUS_CD.'" }';

        $svcName = "IDS_HS_POPUP_START";
	    $out = NULL;
    
        $ret = okcert3_u($target, $CP_CD, $svcName, $params, $license, $out);	// UTF-8


        $RSLT_CD = "";						// 결과코드
        $RSLT_MSG = "";						// 결과메시지
        $MDL_TKN = "";						// 모듈토큰
        $TX_SEQ_NO = "";					// 거래일련번호

        if ($ret == 0) {// 함수 실행 성공일 경우 변수를 결과에서 얻음
            
            $output = json_decode($out,true);		// $output = UTF-8
            
            $RSLT_CD = $output['RSLT_CD'];
            $RSLT_MSG  = $output["RSLT_MSG"];	
            
            if(isset($output["TX_SEQ_NO"])) $TX_SEQ_NO = $output["TX_SEQ_NO"]; // 필요 시 거래 일련 번호 에 대하여 DB저장 등의 처리
            
            if( $RSLT_CD == "B000" ) { // B000 : 정상건
                $MDL_TKN = $output['MDL_TKN']; 
            }
        }
        else {
            echo (" failed ret: ".$ret);
        }

        $response['rslt_cd'] = $RSLT_CD; //결과값
        $response['rslt_msg'] = $RSLT_MSG; //결과메세지

        if($RSLT_CD == 'B000') {
            $response['tc'] = "kcb.oknm.online.safehscert.popup.cmd.P931_CertChoiceCmd"; //변경불가
            $response['cp_cd'] = $CP_CD; //회원사코드
            $response['mdl_tkn'] = $MDL_TKN; //모듈토큰
            $response['action'] = $popupUrl; //팝업
        }
        
        return json_encode($response);
    }

}