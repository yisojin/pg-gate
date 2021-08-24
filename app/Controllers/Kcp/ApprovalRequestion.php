<?php
namespace App\Controllers\Kcp;

use App\Controllers\BaseController;
use App\Libraries\kcp\C_PAYPLUS_CLI_T;

class ApprovalRequestion extends BaseController
{
    public function index()
    { 
        /* ============================================================================== */
        /* =   01. KCP 지불 서버 정보 설정                                              = */
        /* = -------------------------------------------------------------------------- = */

        $g_conf_pa_url    = "paygw.kcp.co.kr";
        $g_conf_pa_port   = "8090";
        $g_conf_tx_mode   = 0;

        /* ============================================================================== */


        /* ============================================================================== */
        /* =   02. 쇼핑몰 지불 정보 설정                                                = */
        /* = -------------------------------------------------------------------------- = */
        // ※ V6 가맹점의 경우
        $g_conf_user_type = "PGNW";  // 변경 불가
        $g_conf_site_id   = $this->request->getPost('site_cd') ?? ''; // 리얼 반영시 KCP에 발급된 site_cd 사용 ex) T0000
        $g_conf_site_key = $this->request->getPost('site_key') ?? '';
        /* ============================================================================== */

        /* ============================================================================== */
        /* =   01. 요청 정보 설정                                                       = */
        /* = -------------------------------------------------------------------------- = */
        $req_tx     = $this->request->getPost( "req_tx"     ) ?? '';                             // 요청 종류
        $trad_time  = $this->request->getPost( "trad_time"  ) ?? '';                             // 원거래 시각
        /* = -------------------------------------------------------------------------- = */
        $ordr_idxx  = $this->request->getPost( "ordr_idxx"  ) ?? '';                             // 주문 번호
        $buyr_name  = $this->request->getPost( "buyr_name"  ) ?? '';                             // 주문자 이름
        $buyr_tel1  = $this->request->getPost( "buyr_tel1"  ) ?? '';                             // 주문자 전화번호
        $buyr_tel2  = $this->request->getPost( "buyr_tel2"  ) ?? '';                             // 주문자 전화번호
        $buyr_mail  = $this->request->getPost( "buyr_mail"  ) ?? '';                             // 주문자 E-Mail
        $good_name  = $this->request->getPost( "good_name"  ) ?? '';                             // 상품 정보
        $comment    = $this->request->getPost( "comment"    ) ?? '';                             // 비고
        /* = -------------------------------------------------------------------------- = */
        $corp_type     = $this->request->getPost( "corp_type"      ) ?? '';                      // 사업장 구분
        $corp_tax_type = $this->request->getPost( "corp_tax_type"  ) ?? '';                      // 과세/면세 구분
        $corp_tax_no   = $this->request->getPost( "corp_tax_no"    ) ?? '';                      // 발행 사업자 번호
        $corp_nm       = $this->request->getPost( "corp_nm"        ) ?? '';                      // 상호
        $corp_owner_nm = $this->request->getPost( "corp_owner_nm"  ) ?? '';                      // 대표자명
        $corp_addr     = $this->request->getPost( "corp_addr"      ) ?? '';                      // 사업장 주소
        $corp_telno    = $this->request->getPost( "corp_telno"     ) ?? '';                      // 사업장 대표 연락처
        /* = -------------------------------------------------------------------------- = */
        $tr_code    = $this->request->getPost( "tr_code"    ) ?? '';                             // 발행용도
        $id_info    = $this->request->getPost( "id_info"    ) ?? '';                             // 신분확인 ID
        $amt_tot    = $this->request->getPost( "amt_tot"    ) ?? '';                             // 거래금액 총 합
        $amt_sup    = $this->request->getPost( "amt_sup"    ) ?? '';                             // 공급가액
        $amt_svc    = $this->request->getPost( "amt_svc"    ) ?? '';                             // 봉사료
        $amt_tax    = $this->request->getPost( "amt_tax"    ) ?? '';                             // 부가가치세
        /* = -------------------------------------------------------------------------- = */
        $mod_type   = $this->request->getPost( "mod_type"   ) ?? '';                             // 변경 타입
        $mod_value  = $this->request->getPost( "mod_value"  ) ?? '';                             // 변경 요청 거래번호
        $mod_gubn   = $this->request->getPost( "mod_gubn"   ) ?? '';                             // 변경 요청 거래번호 구분
        $mod_mny    = $this->request->getPost( "mod_mny"    ) ?? '';                             // 변경 요청 금액
        $rem_mny    = $this->request->getPost( "rem_mny"    ) ?? '';                             // 변경처리 이전 금액
        /* = -------------------------------------------------------------------------- = */
        $cust_ip    = $this->request->getPost("cust_ip") ?? '';                            // 요청 IP

        $shop_user_id = $this->request->getPost("shop_user_id") ?? '';
        $sitename = $this->request->getPost("sitename") ?? '';
        $good_mny = $this->request->getPost('good_mny') ?? '';
        $currency = $this->request->getPost('currency') ?? '';
        $good_expr = $this->request->getPost('good_expr') ?? '';
        $res_cd = $this->request->getPost('res_cd') ?? '';
        $res_msg = $this->request->getPost('res_msg') ?? '';
        $tno = $this->request->getPost('tno') ?? '';
        $trace_no = $this->request->getPost('trace_no') ?? '';
        $enc_info = $this->request->getPost('enc_info') ?? '';
        $enc_data = $this->request->getPost('enc_data') ?? '';
        $ret_pay_method = $this->request->getPost('ret_pay_method') ?? '';
        $tran_cd = $this->request->getPost('tran_cd') ?? '';
        $bank_name = $this->request->getPost('bank_name') ?? '';
        $bank_issu = $this->request->getPost('bank_issu') ?? '';
        $use_pay_method = $this->request->getPost('use_pay_method') ?? '';
        $ordr_chk = $this->request->getPost('ordr_chk') ?? '';
        $cash_yn = $this->request->getPost('cash_yn') ?? '';
        $pay_method = $this->request->getPost('pay_method') ?? '';

        $va_txtype = $this->request->getPost('va_txtype') ?? '';
        $va_mny = $this->request->getPost('va_mny') ?? '';
        $va_bankcode = $this->request->getPost('va_bankcode') ?? '';
        $va_name = $this->request->getPost('va_name') ?? '';
        $va_date = $this->request->getPost('va_date') ?? '';
        $va_receipt_gubn = $this->request->getPost('va_receipt_gubn') ?? '';
        $va_taxno = $this->request->getPost('va_taxno') ?? '';
        
        /* ============================================================================== */

        $buyr_name = iconv("utf-8", "cp949", $buyr_name);
        $good_name = iconv("utf-8", "cp949", $good_name);

        $cli = new C_PAYPLUS_CLI_T();
        $cli->mf_clear();

        $cli->m_encx_data = $enc_data;
        $cli->m_encx_info = $enc_info;

    /* ============================================================================== */
    /* =   03. 처리 요청 정보 설정, 실행                                            = */
    /* = -------------------------------------------------------------------------- = */

    /* = -------------------------------------------------------------------------- = */
    /* =   03-1. 승인 요청                                                          = */
    /* = -------------------------------------------------------------------------- = */
        // 업체 환경 정보
        if ( $req_tx == "pay" )
        {
            $tx_cd = "07010000"; // 현금영수증 등록 요청

            $common_data_set = $cli->mf_set_data_us("amount", $good_mny);
            $common_data_set .= $cli->mf_set_data_us("currency", $currency);
            $common_data_set .= $cli->mf_set_data_us("cust_ip", $cust_ip);
            $common_data_set .= $cli->mf_set_data_us("escw_mod", "N");

            $cli->mf_add_payx_data("common", $common_data_set);

            // 주문 정보
            $cli->mf_set_ordr_data("ordr_mony", $good_mny);
            $cli->mf_set_ordr_data('shop_user_id', $shop_user_id);
            $cli->mf_set_ordr_data('sitename', $sitename);
            $cli->mf_set_ordr_data('good_mny', $good_mny);
            $cli->mf_set_ordr_data('currency', $currency);
            $cli->mf_set_ordr_data('good_expr', $good_expr);
            $cli->mf_set_ordr_data('res_cd', $res_cd);
            $cli->mf_set_ordr_data('res_msg', $res_msg);
            $cli->mf_set_ordr_data('tno', $tno);
            $cli->mf_set_ordr_data('trace_no', $trace_no);
            $cli->mf_set_ordr_data('enc_info', $enc_info);
            $cli->mf_set_ordr_data('enc_data', $enc_data);
            $cli->mf_set_ordr_data('ret_pay_method', $ret_pay_method);
            $cli->mf_set_ordr_data('tran_cd', $tran_cd);
            $cli->mf_set_ordr_data('bank_name', $bank_name);
            $cli->mf_set_ordr_data('bank_issu', $bank_issu);
            $cli->mf_set_ordr_data('use_pay_method', $use_pay_method);
            $cli->mf_set_ordr_data('ordr_chk', $ordr_chk);
            $cli->mf_set_ordr_data('cash_yn', $cash_yn);
            $cli->mf_set_ordr_data('pay_method', $pay_method);
            $cli->mf_set_ordr_data( "ordr_idxx",  $ordr_idxx );
            $cli->mf_set_ordr_data( "good_name",  $good_name );
            $cli->mf_set_ordr_data( "buyr_name",  $buyr_name );
            $cli->mf_set_ordr_data( "buyr_tel1",  $buyr_tel1 );
            $cli->mf_set_ordr_data( "buyr_tel2",  $buyr_tel2 );
            $cli->mf_set_ordr_data( "buyr_mail",  $buyr_mail );
            $cli->mf_set_ordr_data( "comment",    $comment   );

            if($pay_method == 'VCNT') {
                $vcnt_data_set = $cli->mf_set_data_us("va_txtype", $va_txtype);
                $vcnt_data_set .= $cli->mf_set_data_us("va_mny", $va_mny);
                $vcnt_data_set .= $cli->mf_set_data_us("va_bankcode", $va_bankcode);
                $vcnt_data_set .= $cli->mf_set_data_us("va_name", $va_name);
                $vcnt_data_set .= $cli->mf_set_data_us("va_date", $va_date);
                
                if($cash_yn == 'Y') {
                    $vcnt_data_set .= $cli->mf_set_data_us("va_receipt_gubn", $va_receipt_gubn);
                    $vcnt_data_set .= $cli->mf_set_data_us("va_taxno", $va_taxno);
                }

                $cli->mf_add_payx_data("va", $vcnt_data_set);
            }

            // 현금영수증 정보
            // $rcpt_data_set = $cli->mf_set_data_us( "user_type",      $g_conf_user_type );
            // $rcpt_data_set .= $cli->mf_set_data_us( "trad_time",      $trad_time        );
            // $rcpt_data_set .= $cli->mf_set_data_us( "tr_code",        $tr_code          );
            // $rcpt_data_set .= $cli->mf_set_data_us( "id_info",        $id_info          );
            // $rcpt_data_set .= $cli->mf_set_data_us( "amt_tot",        $amt_tot          );
            // $rcpt_data_set .= $cli->mf_set_data_us( "amt_sup",        $amt_sup          );
            // $rcpt_data_set .= $cli->mf_set_data_us( "amt_svc",        $amt_svc          );
            // $rcpt_data_set .= $cli->mf_set_data_us( "amt_tax",        $amt_tax          );
            // $rcpt_data_set .= $cli->mf_set_data_us( "pay_type",       "PAXX"            ); // 선 결제 서비스 구분(PABK - 계좌이체, PAVC - 가상계좌, PAXX - 기타)


            //$rcpt_data_set .= $c_PayPlus->mf_set_data_us( "pay_trade_no",   $pay_trade_no ); // 결제 거래번호(PABK, PAVC일 경우 필수)
            //$rcpt_data_set .= $c_PayPlus->mf_set_data_us( "pay_tx_id",      $pay_tx_id    ); // 가상계좌 입금통보 TX_ID(PAVC일 경우 필수)

            // // 가맹점 정보
            // $corp_data_set = $cli->mf_set_data_us( "corp_type",       $corp_type     );

            // if ( $corp_type == "1" ) // 입점몰인 경우 판매상점 DATA 전문 생성
            // {
            //     $corp_data_set .= $cli->mf_set_data_us( "corp_tax_type",   $corp_tax_type );
            //     $corp_data_set .= $cli->mf_set_data_us( "corp_tax_no",     $corp_tax_no   );
            //     $corp_data_set .= $cli->mf_set_data_us( "corp_sell_tax_no",$corp_tax_no   );
            //     $corp_data_set .= $cli->mf_set_data_us( "corp_nm",         $corp_nm       );
            //     $corp_data_set .= $cli->mf_set_data_us( "corp_owner_nm",   $corp_owner_nm );
            //     $corp_data_set .= $cli->mf_set_data_us( "corp_addr",       $corp_addr     );
            //     $corp_data_set .= $cli->mf_set_data_us( "corp_telno",      $corp_telno    );
            // }

            // $cli->mf_set_ordr_data( "rcpt_data", $rcpt_data_set );
            // $cli->mf_set_ordr_data( "corp_data", $corp_data_set );
        }

    /* = -------------------------------------------------------------------------- = */
    /* =   03-2. 취소 요청                                                          = */
    /* = -------------------------------------------------------------------------- = */
        else if ( $req_tx == "mod" )
        {
            if ( $mod_type == "STSQ" )
            {
                $tx_cd = "07030000"; // 조회 요청
            }
            else
            {
                $tx_cd = "07020000"; // 취소 요청
            }

            $cli->mf_set_modx_data( "mod_type",   $mod_type   );      // 원거래 변경 요청 종류
            $cli->mf_set_modx_data( "mod_value",  $mod_value  );
            $cli->mf_set_modx_data( "mod_gubn",   $mod_gubn   );
            $cli->mf_set_modx_data( "trad_time",  $trad_time  );

            if ( $mod_type == "STPC" ) // 부분취소
            {
                $cli->mf_set_modx_data( "mod_mny",  $mod_mny  );
                $cli->mf_set_modx_data( "rem_mny",  $rem_mny  );
            }
        }
    /* ============================================================================== */


    /* ============================================================================== */
    /* =   03-3. 실행                                                               = */
    /* ------------------------------------------------------------------------------ */
        $g_conf_home_dir = '/var/module/kcp/vcnt';
        $g_conf_log_level = 4;
        $g_conf_key_dir = '/var/module/kcp/vcnt/bin/pub.key';
        $g_conf_log_dir = '/var/module/kcp/vcnt/log';

        if ( strlen($tran_cd) > 0 )
        {
            $cli->mf_do_tx( "", $g_conf_home_dir, $g_conf_site_id,
                                  $g_conf_site_key,  $tran_cd,           "",
                                  $g_conf_pa_url,    $g_conf_pa_port,  "payplus_cli_slib",
                                  $ordr_idxx,        $cust_ip,         $g_conf_log_level,
                                  "",                $g_conf_tx_mode,  $g_conf_key_dir, $g_conf_log_dir );
        }
        else
        {
            $cli->m_res_cd  = "9562";
            $cli->m_res_msg = "연동 오류";
        }
        $res_cd  = $cli->m_res_cd;                      // 결과 코드
        $res_msg = $cli->m_res_msg;                     // 결과 메시지

        $response['res_cd'] = $res_cd;
        $response['res_msg'] = iconv('CP949', 'UTF-8', $res_msg);
    /* ============================================================================== */

    /* ============================================================================== */
    /* =   04. 승인 결과 처리                                                       = */
    /* = -------------------------------------------------------------------------- = */
        if ( $req_tx == "pay" )
        {
            if ( $res_cd == "0000" )
            {
                $cash_no    = $cli->mf_get_res_data( "cash_no"    );       // 현금영수증 거래번호
                $receipt_no = $cli->mf_get_res_data( "receipt_no" );       // 현금영수증 승인번호
                $app_time   = $cli->mf_get_res_data( "app_time"   );       // 승인시간(YYYYMMDDhhmmss)
                $reg_stat   = $cli->mf_get_res_data( "reg_stat"   );       // 등록 상태 코드
                $reg_desc   = $cli->mf_get_res_data( "reg_desc"   );       // 등록 상태 설명

    /* = -------------------------------------------------------------------------- = */
    /* =   04-1. 승인 결과를 업체 자체적으로 DB 처리 작업하시는 부분입니다.         = */
    /* = -------------------------------------------------------------------------- = */
    /* =         승인 결과를 DB 작업 하는 과정에서 정상적으로 승인된 건에 대해      = */
    /* =         DB 작업을 실패하여 DB update 가 완료되지 않은 경우, 자동으로       = */
    /* =         승인 취소 요청을 하는 프로세스가 구성되어 있습니다.                = */
    /* =         DB 작업이 실패 한 경우, bSucc 라는 변수(String)의 값을 "false"     = */
    /* =         로 세팅해 주시기 바랍니다. (DB 작업 성공의 경우에는 "false" 이외의 = */
    /* =         값을 세팅하시면 됩니다.)                                           = */
    /* = -------------------------------------------------------------------------- = */
                $bSucc = "";             // DB 작업 실패일 경우 "false" 로 세팅

                // 결과값 serialize
                $cash = array();
                $cash['receipt_no'] = $receipt_no;
                $cash['app_time']   = $app_time;
                $cash['reg_stat']   = $reg_stat;
                $cash['reg_desc']   = iconv("cp949", "utf-8", $reg_desc);
                $cash['tr_code']    = $tr_code;
                $cash['id_info']    = $id_info;
                $cash_info = serialize($cash);

    /* = -------------------------------------------------------------------------- = */
    /* =   04-2. DB 작업 실패일 경우 자동 승인 취소                                 = */
    /* = -------------------------------------------------------------------------- = */
                if ( $bSucc == "false" )
                {
                    $cli->mf_clear();

                    $tx_cd = "07020000"; // 취소 요청

                    $cli->mf_set_modx_data( "mod_type",  "STSC"     );                    // 원거래 변경 요청 종류
                    $cli->mf_set_modx_data( "mod_value", $cash_no   );
                    $cli->mf_set_modx_data( "mod_gubn",  "MG01"     );
                    $cli->mf_set_modx_data( "trad_time", $trad_time );

                    $cli->mf_do_tx( "",   $g_conf_home_dir, $g_conf_site_id,
                                          $g_conf_site_key,  $tx_cd,           "",
                                          $g_conf_pa_url,    $g_conf_pa_port,  "payplus_cli_slib",
                                          $ordr_idxx,        $cust_ip,         $g_conf_log_level,
                                          "",                $g_conf_tx_mode, $g_conf_key_dir, $g_conf_log_dir );

                    $res_cd  = $cli->m_res_cd;
                    $res_msg = $cli->m_res_msg;
                }

            }    // End of [res_cd = "0000"]

    /* = -------------------------------------------------------------------------- = */
    /* =   04-3. 등록 실패를 업체 자체적으로 DB 처리 작업하시는 부분입니다.         = */
    /* = -------------------------------------------------------------------------- = */
            else
            {
            }
        }
    /* ============================================================================== */


    /* ============================================================================== */
    /* =   05. 변경 결과 처리                                                       = */
    /* = -------------------------------------------------------------------------- = */
        else if ( $req_tx == "mod" )
        {
            if ( $res_cd == "0000" )
            {
                $cash_no    = $cli->mf_get_res_data( "cash_no"    );       // 현금영수증 거래번호
                $receipt_no = $cli->mf_get_res_data( "receipt_no" );       // 현금영수증 승인번호
                $app_time   = $cli->mf_get_res_data( "app_time"   );       // 승인시간(YYYYMMDDhhmmss)
                $reg_stat   = $cli->mf_get_res_data( "reg_stat"   );       // 등록 상태 코드
                $reg_desc   = $cli->mf_get_res_data( "reg_desc"   );       // 등록 상태 설명
            }

    /* = -------------------------------------------------------------------------- = */
    /* =   05-1. 변경 실패를 업체 자체적으로 DB 처리 작업하시는 부분입니다.         = */
    /* = -------------------------------------------------------------------------- = */
            else
            {
            }
        }
    /* ============================================================================== */


    /* ============================================================================== */
    /* =   06. 인스턴스 CleanUp                                                     = */
    /* = -------------------------------------------------------------------------- = */
    $cli->mf_clear();
    /* ============================================================================== */





        return $this->response->setJSON($response);

    }
}