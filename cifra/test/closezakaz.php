<?
  require "config.php";

  try {
      //$dbh = new PDO("odbc:poni32_test1", $msdb_User, $msdb_Pass);// тестовая база
      $dbh = new PDO("odbc:poni32", $msdb_User, $msdb_Pass);
    } catch (PDOException $e) {
      echo 'Connection failed: ' . $e->getMessage();
    }
    
    
  $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT );  
  $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );  
  $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );    
  


  // ---------------------------------------
  // переданные параметры аякс запросом по нажатию на кнопке завершить операцию в модальном окне
  // --- function start_op(BegOrEnd) -- функция передающая параметры
  // $si; // ключ sale id
  // $npp; // npp
  // qty -- количество сделаного
  // idu -- ключ сотрудника
  // ---------------------------

  $sql = iconv('UTF-8', 'windows-1251', "select buhta_key, FIO_sokr, is_lock from dbo._шаблоны_kiosk_сотрудник_по_штрихкоду('".$id."');");

  $sth = $dbh->query($sql);

  $u = $sth->fetch();  
  foreach ($u as $key => $value) 
  $u[iconv('windows-1251', 'UTF-8', $key)]=iconv('windows-1251', 'UTF-8', $value);
  
  $user = $u[FIO_sokr];

  if (($u[is_lock]==1) or ($u[buhta_key]==0)) {
  ?>
  <br /><br /><br /><br />
  <h1> Пропуск не опознан или блокирован!</h1>
  <br /><br />
  <?
  } else {
   
    // --------------- Закрытие заказа кроме плит ----------------------------------
    if (($BegOrEnd == 4) and ($count_si_npp == '')) { // $BegOrEnd == 4 -- кнопка "завершить операцию", переменная с ключами от плит

      $sql = iconv('UTF-8', 'windows-1251', "exec [dbo].[_шаблоны_Антон_Добавить_сделанное_колво]
                      @aaa__npp=$npp, @aaa_sale_id = $si, @zzzzz_sotrudnik_key=$u[buhta_key],
                      @aaa_сделанное_колво = $qty;");

      $dbh->query($sql);

      $sql = iconv('UTF-8', 'windows-1251', "SELECT * FROM dbo._шаблоны_Антон_listworks(88, 1, 0)
              WHERE [sale_id]='$si' AND [npp]='$npp'");

      $sth   =   $dbh->query($sql);

      while($u = $sth->fetch()) {  
        foreach ($u as $key => $value) 
          $u[iconv('windows-1251', 'UTF-8', $key)]=iconv('windows-1251', 'UTF-8', $value);

        echo "<h2>ЗАКАЗ ".$u[Договор_Номер]."/".$u[позиция_в_Договоре]." ЗАКРЫТ</h2>\n";
        echo "<div><h5>$u[npp]. $u[_inf_workname_name]</h5></div>\n";
        echo "<hr>\n";
        echo "<div> Тираж на закрытие: ".round($qty)." шт";
        echo "<div> Закрыл: ".$user;

        if ($u[calc_IS_Close]==1) {
          // закрытые заказы
        echo "<div class='zclose'>Закрыто:".date("d M Y",strtotime($u[time_made]))." - $u[Кем_выполнено]</div>";
        }
        
        echo "<h2>Отгрузка:".date("d M Y",strtotime($u[Дата_отгрузки]))."</h2>\n";
      }
   } 
// --------------------------------------------------------------------------------------------


// ----------------------------------- Закрытие заказов плит ----------------------------------
if (($BegOrEnd == 4) and ($count_si_npp != '')) {
  // -- Преобразуем строку с Sale id и npp выбранные для плиты в массив ----------------------
  $rstr = str_replace("!",":",$count_si_npp);  // заменили "!" на ":" // $count_si_npp строка из js с SALE ID и npp
  $sstr = explode(":",$rstr); // по разделителю ":" создали массив из Sale id(четный индекс) и npp(не чётный индекс)

  $array_zakaz_name = explode(";",$count_zakaz); // по разделителю ";" создали массив с заказам/позиция  

  $k = 0;//счётчик для перебора массива заказов
  for ($i=0;$i<count($sstr)-2;$i++){ 
    $SaleID = 0;
    $NPP = 0;
    // -- Получаем Sail id и npp в цикле --        
    $SaleID = $sstr[$i] ;// Для проверки на существующий Sale_id для плиты// получили в массиве Sale_id          
    $NPP = $sstr[$i+1];  // Для проверки на существующий по Sale_id - npp// получили в массиве npp        
    $i++; // Для получения следующего Sale id +1
// ------------ Подставляем в функцию для Sale id, npp , ключ сотрудника, количество сделанного 1 (константа)

  $sql = iconv('UTF-8', 'windows-1251', "exec [dbo].[_шаблоны_Антон_Добавить_сделанное_колво]
                  @aaa__npp=$NPP, @aaa_sale_id = $SaleID, @zzzzz_sotrudnik_key=$u[buhta_key],
                  @aaa_сделанное_колво = 1;");

  $dbh->query($sql);

  echo "<h4>Заказ -".$array_zakaz_name[$k]." - закрыт </h4>";
  $k++; // создаём индекс следубщего заказа
  }

  $sql = iconv('UTF-8', 'windows-1251', "SELECT * FROM dbo._шаблоны_Антон_listworks(88, 1, 0)
  WHERE [sale_id]='$si' AND [npp]='$npp'");

  $sth   =   $dbh->query($sql);

  while($u = $sth->fetch()) {  
    foreach ($u as $key => $value) 
      $u[iconv('windows-1251', 'UTF-8', $key)]=iconv('windows-1251', 'UTF-8', $value);

  echo "<hr><div><h5>$u[npp]. $u[_inf_workname_name]</h5></div><hr>\n";
  echo "<h3>Закрыл: ".$user."<h3>";
  echo "<div class='zclose'><h5>Закрыто:".date("d M Y",strtotime($u[time_made]))." - $u[Кем_выполнено]</h5></div>";
  echo "<h2>Отгрузка:".date("d M Y",strtotime($u[Дата_отгрузки]))."</h2>\n";
  
  }//while($u = $sth->fetch()) {  

  echo "<hr>\n";
  
 //--------------------------------------------------------------------------------------------
 // ------------ Вывод в модольное окно результат закрытия операции ---------------------------


} //if (($BegOrEnd == 4) and ($count_si_npp != '')) {
// --------------------------------------------------------------------------------------------

  if (($BegOrEnd == 1) or ($BegOrEnd == 2) or ($BegOrEnd == 3) or ($BegOrEnd == 4)  or ($BegOrEnd == 7) ){

    $id_not_u = ($str = substr($id, 1)); // преобразование id сотрудника из штрих кода id 
    if ($id_not_u > 100000) $id_not_u = $id_not_u-100000;

      //  echo '<br>----------------------------------<br>';
      //   echo '|si:'.$si.'<br>|npp:'.$npp.'<br>|id_user:'. $id_not_u.'<br>|BegOrEnd:'.$BegOrEnd;
      //   echo '<br>|$count_si_npp:'.$count_si_npp.'<br>|count_si_npp_edit'.$count_si_npp_edit.'<br>|qty:'.$qty.'<br>|plita_num'.$plita_num.'<br>|device'.$device;
      //  echo '<br>----------------------------------<br>';


    $count_si_npp ="'$count_si_npp'";    
    $count_si_npp_edit = " '$count_si_npp_edit'";
    if ($plita_num == '') $plita_num = 0;


     $sql_ins = iconv('UTF-8', 'windows-1251', "exec dbo._insert_operation_begin_end @Sale_id = $si,	
                                                                                      @npp =   $npp ,	
                                                                                      @id_user = $id_not_u, 
                                                                                      @BegOrEnd = $BegOrEnd,
                                                                                      @count_si_npp = $count_si_npp,
                                                                                      @count_si_npp_edit = $count_si_npp_edit,
                                                                                      @qty = $qty,
                                                                                      @plita_num = $plita_num,
                                                                                      @device = $device;");


     $sth_ins = $dbh->query($sql_ins);




    echo   '<div class="alert alert-success alert-dismissible text-center" role="alert" id="my-alert">';
    if ($BegOrEnd == 1) echo 'Подготовка плиты начата';
    if ($BegOrEnd == 2) echo 'Подготовка плиты завершена ';
    if ($BegOrEnd == 3) echo 'Операция начата';      
    if ($BegOrEnd == 4) echo 'Операция завершена';
    if ($BegOrEnd == 7) echo 'Заказ выполнен частично.Операция повторно начата ';
    echo '</div>'; 

    //----------------------------------------------------------------------------------------------
  }


//-----------------------------------------------------------------------------------
//                    Редактирование Плиты 
//-----------------------------------------------------------------------------------

  if (($BegOrEnd == 5) and ($count_si_npp != '') and ($count_si_npp_edit != '' ) ) { // Удаление заказов из плиты
    $id_not_u = ($str = substr($id, 1)); // преобразование id сотрудника из штрих кода id 
    if ($id_not_u > 100000) $id_not_u = $id_not_u-100000;
    $count_si_npp ="'$count_si_npp'"; // обворачиваем переменные в кавычки    
    $count_si_npp_edit = " '$count_si_npp_edit'";

    $sql_del = iconv('UTF-8', 'windows-1251', "exec dbo._insert_operation_begin_end @Sale_id = $si,
                                                                                @npp =  $npp,
                                                                                @id_user = $id_not_u,
                                                                                @BegOrEnd = $BegOrEnd,
                                                                                @count_si_npp = $count_si_npp,
                                                                                @count_si_npp_edit = $count_si_npp_edit,
                                                                                @qty = $qty,
                                                                                @plita_num = $plita_num,
                                                                                @device = $device;");

    $sql_del_query = $dbh->query($sql_del);

    echo   '<div class="alert alert-danger alert-dismissible text-center" role="alert" id="my-alert">';
    echo "Выбранные заказы из плиты удалены";
    echo '</div>'; 
  }




  if (($BegOrEnd == 6) and ($count_si_npp != '')  ) { //Добавление заказов в плиту
    // Получаем введённый в иинпут заказ $add_zakaz
    // echo "add_zakaz:".$add_zakaz;
    $add_SALEID = 0;
    $add_npp = 0;
    $zakaz = explode("/",$add_zakaz); // по разделителю "/" создали массив из Заказ(индекс 0) и позиция(индекс 1)  
    // Проверяем заказ на наличие его в базе
    // echo "<br>Заказ:".$zakaz[0]; // Заказ
    // echo "<br>Позиция:".$zakaz[1]; // Позиция
    // $zakaz_num = $zakaz[0];
    // $zakaz_poz = $zakaz[1];
    //echo "<br>zakaz_num:".$zakaz_num;
    //echo "<br>zakaz_poz:".$zakaz_poz;
     //iconv - функция переводящая строку в указанную кодировку 
    $sql = iconv('UTF-8', 'windows-1251', "SELECT * from dbo._шаблоны_Антон_listworks(88, 1, 0) where (_inf_workname_nomer = 70008) and (Договор_Номер = $zakaz[0]) and (позиция_в_Договоре = $zakaz[1]) " );

    $sth   =  $dbh->query($sql); 

    // echo "<br>--------------------<br>";
    while ($row = $sth->fetch(PDO::FETCH_LAZY))
    {
        // echo "Saleid".$row[0] . "\n";
        // echo "npp".$row[1] . "\n<br>";
        // echo "Заказ".$row[2] . "\n";
        // echo "Позиция".$row[3] . "\n<br>";
        //echo "-------------------<br>";

        if (($row[0] > 0) and ($row[1] > 0)) {
          $add_SALEID = $row[0];
          $add_npp = $row[1];
          // echo "<br>add_SALEID".$add_SALEID;
          // echo "<br>add_npp".$add_npp;
          // echo "Найдена запись";
          // echo "Номер плиты".$plita_num;
          // echo "<br>---------------------<br>";         
        }


    }//while ($row = $sth->fetch(PDO::FETCH_LAZY))
      // --------------------------------------------------
      $id_not_u = ($str = substr($id, 1)); // преобразование id сотрудника из штрих кода id 
      if ($id_not_u > 100000) $id_not_u = $id_not_u-100000;
      $count_si_npp ="'$count_si_npp'"; // обворачиваем переменные в кавычки    
      $count_si_npp_edit = " '$count_si_npp_edit'";
    //-----------------

    //  echo "<br>-----add_SALEID-----<br>".$add_SALEID;
    //  echo "<br>-----add_npp--------<br>".$add_npp;
    if (($add_SALEID > 0) and ($add_npp > 0)){       
      $sql_ins = iconv('UTF-8', 'windows-1251', "exec dbo._insert_operation_begin_end @Sale_id = $add_SALEID,	
                                                                                      @npp =   $add_npp ,	
                                                                                      @id_user = $id_not_u, 
                                                                                      @BegOrEnd = $BegOrEnd,
                                                                                      @count_si_npp = $count_si_npp,
                                                                                      @count_si_npp_edit = $count_si_npp_edit,
                                                                                      @qty = $qty,
                                                                                      @plita_num = $plita_num,
                                                                                      @device = $device;");


      $sth_ins = $dbh->query($sql_ins);

      echo   '<div class="alert alert-success alert-dismissible text-center" role="alert" id="my-alert">';
      echo "Заказ в плиту добавлен";
      echo '</div>';
    } else {
      echo   '<div class="alert alert-danger alert-dismissible text-center" role="alert" id="my-alert">';
      echo "Заказ в плиту не добавлен";
      echo '</div>';

    }




  }

}


?>
