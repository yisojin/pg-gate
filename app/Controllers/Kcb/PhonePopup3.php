<?php
namespace App\Controllers\Kcb;

use App\Controllers\BaseController;

class PhonePopup3 extends BaseController{

    public function index(){

        //phonepopup2 에서 리턴 된 모듈토큰
        $MDL_TKN = $_REQUEST['MDL_TKN'];
        //kcb에서 부여받은 회원사코드
        $CP_CD = "V43600000000";
        // 타겟 (운영, 테스트전환)
        $target = "PROD";
        // 라이센스파일
        $license = "/home/ubuntu/{$CP_CD}_IDS_01_{$target}_AES_license.dat";
        // okcert parameter
        $param = '{ "MDL_TKN":"'.$MDL_TKN.'" }';
        
        $svcName = 'IDS_HS_POPUP_RESULT';
        $out = NULL;

        //utf8용 
        $ret = okcert3_u($target, $CP_CD, $svcName, $param, $license, $out);

        // 성공
        if($ret == 0){
            
            $output = json_decode($out,true);

            $response['CP_CD'] = $output['CP_CD'] ?? '';
            $response['TX_SEQ_NO'] = $output['TX_SEQ_NO'] ?? '';
            $response['RSLT_CD'] = $output['RSLT_CD'] ?? '';
            $response['RSLT_MSG'] = $output['RSLT_MSG'] ?? '';
            $response['RSLT_NAME'] = $output['RSLT_NAME'] ?? '';
            $response['RSLT_BIRTHDAY'] = $output['RSLT_BIRTHDAY'] ?? '';

            $response['RSLT_SEX_CD'] = $output['RSLT_SEX_CD'] ?? '';
            $response['RSLT_NTV_FRNR_CD'] = $output['RSLT_NTV_FRNR_CD'] ?? '';

            $response['DI'] = $output['DI'] ?? '';
            $response['CI_UPDATE'] = $output['CI_UPDATE'] ?? '';
            $response['CI'] = $output['CI'] ?? '';
            $response['TEL_COM_CD'] = $output['TEL_COM_CD'] ?? '';
            $response['TEL_NO'] = $output['TEL_NO'] ?? '';
            $response['RETURN_MSG'] = $output['RETURN_MSG'] ?? '';

        }else{
            echo ("failed ret:'+".$ret);
        }

        return json_encode($response);
            
    }

}
?>
