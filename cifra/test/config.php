<?
// Файл определения конфигурации.

session_start();

foreach ($_REQUEST AS $index=>$val) {
 if (substr($index,0,1)!="_") $$index=$val;
}

// IP адрес оффиса.
$officeip="84.204.215.42";

// ftp сервер.
$ftp_server="84.204.215.42";
$ftp_user_name="poni\\test";
$ftp_user_pass="test";

// Параметры подключения к базе.
//$db_Host="mysql78.1gb.ru";
//$db_Name="gb_db_poni_1";
//$db_User="gb_db_poni_1";
//$db_Pass="35b7a5d3bjkl";
//$db_Host="mysql.poni.z8.ru";
//$db_Name="db_poni_1";
//$db_User="dbu_poni_1";
//$db_Pass="Ivanov";

// Параметры подключения к базе MSSQL.

$msdb_Host="poni";
$msdb_Name="poni";
$msdb_Name2="poni_se2";
$msdb_User="phpweb";
$msdb_Pass="inop3INOP";


// Параметры подключения к базе CorporativeMsg.
$db_Host_m="84.204.215.42:3307";
$db_Name_m="corporativemessenger";
$db_User_m="Anton";
$db_Pass_m="test";

// Поля баз данных MSSQL.

// Справочник ТМЦ
$tb_tmc=      "ТМЦ";
$fd_tmc_key=   $tb_tmc.".Ключ";			$tmc_key=  "Ключ";
$fd_tmc_type=  $tb_tmc.".Вид";
$fd_tmc_art=   $tb_tmc."._Артикул";     $tmc_art=   "_Артикул";
$fd_tmc_node=  $tb_tmc."._node";        $tmc_node=  "_node";
$fd_tmc_cart=  $tb_tmc."._ЦветАрт";     $tmc_cart=  "_ЦветАрт";
$fd_tmc_color= "cast(".$tb_tmc."._ЦветОписание as NVARCHAR)";
$fd_tmc_name=  "cast(".$tb_tmc.".Название as NVARCHAR(100))";		$tmc_name= "Название";
$fd_tmc_nomer= $tb_tmc.".Номер";		$tmc_nomer="Номер";
$fd_tmc_minost=$tb_tmc.".[_Min остаток]";

// Артикулы материалов
$tb_infmat=         "_inf_material";
$fd_infmat_namemat= $tb_infmat.".Название";
$fd_infmat_art=     $tb_infmat.".Номер";
$fd_infmat_type=    $tb_infmat.".type";
$fd_infmat_idkey=   $tb_infmat.".idkey_mat";

// Артикулы блоков
$tb_infbl=        "_inf_block";
$fd_infbl_namebl= $tb_infbl.".num_bl";
$fd_infbl_idbl=   $tb_infbl.".id_block";

// Информация по блокам
$tb_nmbl=       "_name_bl";
$fd_nmbl_id=    $tb_nmbl.".id";
$fd_nmbl_idkey= $tb_nmbl.".idkey";

// Информация по оперативным остаткам на складе
$tb_ost=       "Остаток";
$fd_ost_yl=    $tb_ost.".[Юр.лицо]";			$ost_yl=    "[Юр.лицо]";         
$fd_ost_tsbc=  $tb_ost.".[Тип субконто]";		$ost_tsbc=  "[Тип субконто]";
$fd_ost_sbc=   $tb_ost.".Субконто";				$ost_sbc=   "Субконто";
$fd_ost_zn=    $tb_ost.".Значение";				$ost_zn=    "Значение";
$fd_ost_vid=   $tb_ost.".Вид";					$ost_vid=   "Вид";

// Информация по договорам
$tb_dog=        "Договор";
$fd_dog_key=    $tb_dog.".Ключ";				$dog_key=    "Ключ";
$fd_dog_yl=     $tb_dog.".[Юр.лицо]";
$fd_dog_type=   $tb_dog.".Вид";					$dog_type=   "Вид";
$fd_dog_many=   $tb_dog.".Валюта";
$fd_dog_mng=    $tb_dog.".Ответственный";
$fd_dog_vod=    $tb_dog.".Водитель";
$fd_dog_zapros= $tb_dog."._НомерЗапроса";
$fd_dog_zakaz=  $tb_dog."._НомерЗаказа";
$fd_dog_zaprosb=$tb_dog."._НомерЗапросаБ";
$fd_dog_zakazb= $tb_dog."._НомерЗаказаБ";
$fd_dog_editor= $tb_dog."._Редактор";
$fd_dog_d201=   $tb_dog."._догспец201";
$fd_dog_summa=  $tb_dog.".Сумма";
$fd_dog_tclient=$tb_dog.".[тип получателя]";	$dog_tpost=   "[тип получателя]";
$fd_dog_client= $tb_dog.".Получатель";
$fd_dog_tpost=  $tb_dog.".[тип поставщика]";	$dog_tpost=   "[тип поставщика]";
$fd_dog_post=   $tb_dog.".поставщик";			$dog_post=    "поставщик";
$fd_dog_gpoluch=$tb_dog.".грузополучатель";
$fd_dog_gotprv= $tb_dog.".грузоотправитель";
$fd_dog_name=   $tb_dog.".Текст";
$fd_dog_date=   $tb_dog.".[Дата]";
$fd_dog_date1=  $tb_dog.".[Дата 1]";
$fd_dog_p1=     $tb_dog.".[Признак 1]";
$fd_dog_p2=     $tb_dog.".[Признак 2]";
$fd_dog_p3=     $tb_dog.".[Признак 3]";
$fd_dog_p4=     $tb_dog.".[Признак 4]";
$fd_dog_p5=     $tb_dog.".[Признак 5]";
$fd_dog_p6=     $tb_dog.".[Признак 6]";
$fd_dog_p7=     $tb_dog.".[Признак 7]";
$fd_dog_p8=     $tb_dog.".[Признак 8]";
$fd_dog_p9=     $tb_dog.".[Признак 9]";
$fd_dog_rsp=    $tb_dog.".[рс поставщика]";
$fd_dog_spopl=  $tb_dog.".[способ оплаты]";

// Информация по позициям
$tb_dgs=        "Догспец";
$fd_dgs_key=    $tb_dgs.".Ключ";
$fd_dgs_dog=    $tb_dgs.".Договор";
$fd_dgs_type=   $tb_dgs.".Вид";
$fd_dgs_npp=    $tb_dgs.".[Порядок ввода]";
$fd_dgs_tsc1=   $tb_dgs.".[Тип субконто 1]";
$fd_dgs_sc1=    $tb_dgs.".[Субконто 1]";
$fd_dgs_tsc2=   $tb_dgs.".[Тип субконто 2]";
$fd_dgs_sc2=    $tb_dgs.".[Субконто 2]";
$fd_dgs_tsc3=   $tb_dgs.".[Тип субконто 3]";
$fd_dgs_sc3=    $tb_dgs.".[Субконто 3]";
$fd_dgs_tsc4=   $tb_dgs.".[Тип субконто 4]";
$fd_dgs_sc4=    $tb_dgs.".[Субконто 4]";
$fd_dgs_tsc5=   $tb_dgs.".[Тип субконто 5]";
$fd_dgs_sc5=    $tb_dgs.".[Субконто 5]";
$fd_dgs_qty=    $tb_dgs.".Количество";
$fd_dgs_bcena=  $tb_dgs.".[_Баз.Цена]";
$fd_dgs_cena=   $tb_dgs.".Цена";
$fd_dgs_summ=   $tb_dgs.".Сумма";
$fd_dgs_pdv=    $tb_dgs."._ПодВид";

// Информация по проводкам
$tb_prv=        "проводка";
$fd_prv_summa=  $tb_prv.".Сумма";
$fd_prv_credit= $tb_prv.".Кредит";
$fd_prv_debet=  $tb_prv.".Дебет";
$fd_prv_data=   $tb_prv.".Дата";
$fd_prv_ktsc2=  $tb_prv.".[Кр тип субконто 2]";
$fd_prv_ksc2=   $tb_prv.".[Кр субконто 2]";

// Список персонализаций
$tb_pers=       "_person";
$fd_pers_tmc=   $tb_pers.".тмц";
$fd_pers_www=   $tb_pers.".WWW";

// Таблица бронирования
$tb_bron=       "_бронь";
$fd_bron_key=   $tb_bron.".Ключ";
$fd_bron_date1= $tb_bron.".дата1";
$fd_bron_date2= $tb_bron.".дата2";
$fd_bron_dog=   $tb_bron.".договор";

// Поля баз данных MySQL.


// Таблица Форума.
$tb_Forum       = "Forum"; 
$fd_Forum_ID    = $tb_Forum.".IDF";           $fd_Forum_UID = "IDF";
$fd_Forum_User  = $tb_Forum.".User";
$fd_Forum_DtCr  = $tb_Forum.".DataCreate";    $fd_Forum_DC  = "DataCreate";
$fd_Forum_Grupe = $tb_Forum.".Grupe";         $fd_Forum_Grp = "Grupe";
$fd_Forum_Vpr   = $tb_Forum.".Vpr";
$fd_Forum_St    = $tb_Forum.".Status";
$fd_Forum_Sbj   = $tb_Forum.".Subject";
$fd_Forum_Text  = $tb_Forum.".Text";

// Таблица отметок не просмотров тем форума.
$tb_VF          = "VisF"; 
$fd_VF_DF       = $tb_VF.".IDF";
$fd_VF_ID       = $tb_VF.".UID";


// Справочник городов
$tb_city=       "DilerCity";
$fd_city_id=    $tb_city.".IdCity";
$fd_city_name=  $tb_city.".NameCity";
$fd_city_reg=   $tb_city.".IdOkrg";

// Справочник менеджеров
$tb_Mng=        "Mngr";
$fd_Mng_id=     $tb_Mng.".IdMng";
$fd_Mng_Name=   $tb_Mng.".Name";
$fd_Mng_Phone=  $tb_Mng.".Phone";
$fd_Mng_SPhone= $tb_Mng.".SPhone";
$fd_Mng_CM=     $tb_Mng.".CM";
$fd_Mng_Mail=   $tb_Mng.".Mail";
$fd_Mng_pic=    $tb_Mng.".pic";

// Справочник регионов
$tb_reg=         "DilerRegion";
$fd_reg_id=      $tb_reg.".IdReg";
$fd_reg_name=    $tb_reg.".NameReg";
$fd_reg_visible= $tb_reg.".Visible";

// Справочник Статусов
$tb_status=      "DilerStatus";
$fd_status_id=   $tb_status.".IdStatus";
$fd_status_name= $tb_status.".NameStatus";

// Справочник дилеров
$tb_dorg=          "DilerOrg";
$fd_dorg_clientid= $tb_dorg.".IdClient";
$fd_dorg_idcm=     $tb_dorg.".IdCM";
$fd_dorg_name=     $tb_dorg.".Name";
$fd_dorg_www=      $tb_dorg.".WWW";
$fd_dorg_phone=    $tb_dorg.".Phone";
$fd_dorg_mail=     $tb_dorg.".Mails";
$fd_dorg_idMng=    $tb_dorg.".IdMng";
$fd_dorg_visible=  $tb_dorg.".Visible";
$fd_dorg_mailSubs= $tb_dorg.".IsMailSubscribe";
$fd_dorg_idCity=   $tb_dorg.".IdCity";
$fd_dorg_limit=    $tb_dorg.".Limits";
$fd_dorg_status=   $tb_dorg.".IdStatus";

// Справочник пользователей

$tb_dusr=        "DilerUsr";
$fd_dusr_iddu=   $tb_dusr.".IDDU";
$fd_dusr_iddo=   $tb_dusr.".IDDO";
$fd_dusr_name=   $tb_dusr.".Name";
$fd_dusr_login=  $tb_dusr.".Login";
$fd_dusr_pass=   $tb_dusr.".Pass";
$fd_dusr_mail=   $tb_dusr.".Mail";
$fd_dusr_hexid=  $tb_dusr.".HEXid";
$fd_dusr_da=     $tb_dusr.".DateAc";
$fd_dusr_ip=     $tb_dusr.".IP";
$fd_dusr_ac=     $tb_dusr.".IsAccess";
$fd_dusr_subs=   $tb_dusr.".IsMailSubscribe";


// Файлы к заказам

$tb_fz=         "ZFiles";
$fd_fz_idfz=    $tb_fz.".IDZF";
$fd_fz_size=    $tb_fz.".SizeF";
$fd_fz_zakaz=   $tb_fz.".ZakazN";
$fd_fz_dc=      $tb_fz.".DateCr";


// База новостей

$tb_news            = "News";
$fd_news_idkey      = $tb_news.".IdNews";
$fd_news_datef      = $tb_news.".DateF";
$fd_news_datet      = $tb_news.".DateT";
$fd_news_text       = $tb_news.".Text";
$fd_news_pic        = $tb_news.".Pic";
$fd_news_waring     = $tb_news.".Waring";
$fd_news_todlr      = $tb_news.".ToDlr";

// База Адресов обратной связи.

$tb_lm              = "LinkMail";
$fd_lm_id           = $tb_lm.".Id";
$fd_lm_name         = $tb_lm.".Name";
$fd_lm_mail         = $tb_lm.".Mail";

// База продуктов Адъютант.

$tb_APr             = "AProduct";
$fd_APr_id          = $tb_APr.".IdProduct";
$fd_APr_Class       = $tb_APr.".IdClass";
$fd_APr_Name        = $tb_APr.".Name";
$fd_APr_Type        = $tb_APr.".IdType";
$fd_APr_Tisn        = $tb_APr.".IdTisn";
$fd_APr_Dat         = $tb_APr.".IdDat";
$fd_APr_Qty         = $tb_APr.".Qty";
$fd_APr_A8          = $tb_APr.".A8";
$fd_APr_Visible     = $tb_APr.".Visible";

// Цвета материалов.

$tb_ACol            = "AColor";
$fd_ACol_id         = $tb_ACol.".IdColor";
$fd_ACol_idPr       = $tb_ACol.".IdProduct";
$fd_ACol_CName      = $tb_ACol.".ColorName";

// Восьмой знак артикула.

$tb_A8              = "A8";
$fd_A8_bt           = $tb_A8.".Bt";
$fd_A8_zn           = $tb_A8.".Zn";
$fd_A8_name         = $tb_A8.".Name";

// Персонализации - Info

$tb_pinf            = "pers_inf";
$fd_pinf_mid        = $tb_pinf.".Mark_Id";
$fd_pinf_smid       = $tb_pinf.".SubMark_Id";
$fd_pinf_id         = $tb_pinf.".ID";
$fd_pinf_mark       = $tb_pinf.".Mark";
$fd_pinf_mtype      = $tb_pinf.".MarkType";
$fd_pinf_nac        = $tb_pinf.".Nac";
$fd_pinf_disc       = $tb_pinf.".Discount";

// База ожидаемых приходов

$tb_ph              = "APrihod";
$fd_ph_art          = $tb_ph.".Art";
$fd_ph_ph           = $tb_ph.".Prihod";
$fd_ph_qty          = $tb_ph.".Qty";
$fd_ph_bron         = $tb_ph.".Bron";

// База параметров сайта

$tb_op              = "Options";
$fd_op_vars         = $tb_op.".Vars";
$fd_op_date         = $tb_op.".DateV";
$fd_op_param        = $tb_op.".Param";

// База Сшивок

$tb_ws              = "WorkSh";
$fd_ws_id           = $tb_ws.".IdSh";
$fd_ws_id           = $tb_ws.".IdSh";
$fd_ws_id           = $tb_ws.".IdSh";

// CM
// База сообщений.

$tb_m_msg        = "messages";
$fd_m_msg_id     = $tb_m_msg.".IdMsg";
$fd_m_msg_from   = $tb_m_msg.".FromUser";   
$fd_m_msg_to     = $tb_m_msg.".ToUser"; 
$fd_m_msg_text   = $tb_m_msg.".Text";
$fd_m_msg_dc     = $tb_m_msg.".CreateDate";
$fd_m_msg_dr     = $tb_m_msg.".ReadDate";
$fd_m_msg_dg     = $tb_m_msg.".GetDate";

// База пользователей.

$tb_m_usr        = "users";
$fd_m_usr_id     = $tb_m_usr.".IdUser";
$fd_m_usr_login  = $tb_m_usr.".Login";     $fd_m0_usr_login="Login";
$fd_m_usr_pass   = $tb_m_usr.".Password";
$fd_m_usr_status = $tb_m_usr.".Status";
$fd_m_usr_dc     = $tb_m_usr.".ActiveDate";
$fd_m_usr_da     = $tb_m_usr.".NetDate";





// Длительность брони в днях.
$dlbron=7; 

// Адрес электронной почты
$mailto=  "admin@adjutant.ru";
$mailfrom="admin@adjutant.ru";

// Рисунки
$r_foto="<img src=/pic/b_foto.png border=0 title='фотография'>";
$r_edit="<img src=/pic/b_edit.png border=0 title='редактировать'>";
$r_vars="<img src=/pic/b_vars.gif border=0 title='параметры'>";
$r_drop="<img src=/pic/b_drop.png border=0 title='удалить'>";
$r_new ="<img src=/pic/b_new.png border=0' title='добавить персонализацию'>";
$r_start="<img src=/pic/b_waring.gif border=0' title='запрос в оформление'>";
$r_comme="<img src=/pic/b_foto.png border=0' title='коммерческое предложение в формате Excel'>";
$r_setbr="<img src=/pic/b_plus.gif border=0' title='бронирование'>";
$r_delbr="<img src=/pic/b_minus.gif border=0' title='отменить бронирование'>";
$r_mail="<img src=/pic/mail.gif border=0>";
$r_www ="<img src=/pic/www.gif border=0>";
$r_phone="<img src=/pic/phone.gif border=0>";

// Подписка на
$sub_news =1;
$sub_zak =2;

// Уровни доступа
$ac_sklad =1;
$ac_farch =2;
$ac_vzakaz=4;
$ac_zapros=8;
$ac_bron  =16;
$ac_sbpz  =32;
$ac_smaket=64;
$ac_forum=128;

// Массив для фрезы
$fz=array(
 'sale_id', 'npp', 's'
);

// Функция сбора статистики
function astat($s=1, $p1="", $p2="")
{
 global $db_Host;
 global $db_Name;
 global $db_User;
 global $db_Pass;
 global $REMOTE_ADDR;

 //  Подключение к MySQL
 if (!mysql_connect($db_Host, $db_User, $db_Pass))
 {
  echo "Erorr MySQL connect.";
  exit;
 }
 mysql_select_db($db_Name);
 
 @mysql_query("INSERT INTO Stat VALUES(now(), '$REMOTE_ADDR', '$s', '$p1', '$p2')");
  
 mysql_close();
}


function iconvArray($inputArray){
  $outputArray=array();
    if ($newEncoding!=''){
      if (!empty($inputArray)){
        foreach ($inputArray as $element){
          if (!is_array($element)){
            $element=iconv('UTF-8', 'windows-1251', $element);
          } else {
            $element=iconvArray($element);
          }
          $outputArray[]=$element;
        }
      }
    }
  $inputArray=$outputArray;
}

?>
