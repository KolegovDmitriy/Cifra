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

  $Sale_id_in_base = 0; // Есть запись в базе 0 - Нет записей с выбранным Sale_id и npp в БД; 1 - Нет записей с выбранным 
                        //Sale_id и npp в БД флаг начала операции для кнопки выполнения операции в модальном окне;

  //  $sql = iconv('UTF-8', 'windows-1251', "SELECT * FROM dbo._шаблоны_Антон_listworks(".$_SESSION[reg].", 1, 1)
  // 			   WHERE [sale_id]='$si' AND [npp]='$npp'");


  // для разработки параметры заданы
  $sql = iconv('UTF-8', 'windows-1251', "SELECT * FROM dbo._шаблоны_Антон_listworks(88, 1, 1)
  WHERE [sale_id]='$si' AND [npp]='$npp'");
                    
  $sth   =   $dbh->query($sql);

  while($u = $sth->fetch()) {  
    foreach ($u as $key => $value) 
      $u[iconv('windows-1251', 'UTF-8', $key)]=iconv('windows-1251', 'UTF-8', $value);


    if ($count_si_npp == ''){
      // ----------------------------------------------------------------------------------
      echo "<div class='label-zakaz'>";    
      echo "<div>$u[npp]. $u[_inf_workname_name]</div><div>\n";

      echo "<hr>\n";

      echo "<div> Тираж: ".round($u[qty])." шт, Пригон: ".round($u[qty_obrazec_prigon])." шт";    
      if ($u[qty_made]>0) echo ", сдано ".round($u[qty_made])." шт";
      echo ".</div>\n";

      echo "<hr>\n";

      echo "<div>$u[comment]</div>";
      echo "<br>\n";
      echo "<div align=left>Продажа: $u[_Sale_manager_name]</div>";
      echo "<div align=left>Исполнитель: $u[Ответственный_name]</div>";
      echo "<div align=left>Оператор: $u[Дизайнер_тек_операции_name]</div>";
      // ----------------------------------------------------------------------------------

      if ($u[calc_IS_Close]==1) {
          // закрытые заказы
        echo "<div class=zclose>Закрыто:".date("d M Y",strtotime($u[time_made]))." - $u[Кем_выполнено]</div>";
      }
      
      echo "<h2>Отгрузка: ".date("d M Y",strtotime($u[Дата_отгрузки]))."</h2>\n";

      // ----------------------------------------------------------------------------------
    } else{
      echo "<div class = 'plita_edit'>";    

        // ----------------------- Получили выбранные для плиты Sale id и npp ----------------------
        // -- Преобразуем строку с Sale id и npp выбранные для плиты в массив ----------------------
        $rstr = str_replace("!",":",$count_si_npp);  // заменили "!" на ":" // $count_si_npp строка из js с SALE ID и npp
        $sstr = explode(":",$rstr); // по разделителю ":" создали массив из Sale id(четный индекс) и npp(не чётный индекс)  
        
        $array_zakaz_name = explode(";",$count_zakaz); // по разделителю ";" создали массив с заказам/позиция        

        $k = 0;//счётчик для перебора массива заказов
        for ($i=0;$i<count($sstr)-2;$i++){ 
          $SaleID = 0;
          $NPP = 0;
          $array_zakaz = '';
          // -- Получаем Sail id и npp в цикле --        
          $SaleID = $sstr[$i] ;// Для проверки на существующий Sale_id для плиты// получили в массиве Sale_id          
          $NPP = $sstr[$i+1];  // Для проверки на существующий по Sale_id - npp// получили в массиве npp                     
          $i++; // Для получения следующего Sale id +1
          
        // ----------------------------------------------------------------------------------------
        $number_plita = 0;

        $plita_status_made = -1; // -1 - Плита не создана 0 - плита создана 1 - подготовка завершена 2 - начато изготовление плиты 3 - закончено изготовление плиты
        // -------------- Сравниваем выбранные Sale id c Sail id в таблице плит -------------------
          $sql = "SELECT * FROM _plita_pattern"; // Присваиваем переменной запрос SQL как обычную строку
          $pp1 = $dbh->query($sql); // Передаём SQL запрос в PDO (PDOStatement Object ( [queryString] => SELECT * FROM _operation_work )) 
          // -- Перебераем все строки в таблице и сравниваем с отобранными Sale id и npp --
            while ($row = $pp1->fetch()){ // пока есть строки, то перебераем запрос  
              if (($row['Sale_id'] == $SaleID) and ($row['npp'] == $NPP)) {
                $plita_status_made = $row['status_made'];
                $number_plita = $row['PLITA'];
              }
            }

            echo "<label class = 'zakaz_v_plite'> <div> <input type='checkbox' value='$SaleID:$NPP'!'' > </div><div><h4>".$array_zakaz_name[$k]."</h4></div></label><br>";
            $k++; // создаём индекс следубщего заказа

        }
        echo "<h3 id='plita_modal'>Плита № ".$number_plita."</h3></div>";
        //-------------------------------------------------------------------------------------------

   if ($plita_status_made >= 0){
      echo "<div class='btn_plita_edit'> <button type='button' class='btn mb - 1 btn-outline-danger' onclick='edit_plita(5)'>Удалить</button> ";
      echo "<button type='button' class='btn ml-1 mt - 1 btn-outline-success' onclick='edit_plita(6)'>Добавить</button>";
      echo "<input class='form-control ml-1 add_zakaz_v_plitu ' type='text'  onkeyup=\"this.value = this.value.replace ( /[^0-9/]/g, '')\"  >  ";    
    
   }



   if ($plita_status_made == 1){

    echo "<hr>";
      // --------------------- Создание Select списка оборудования цифры  --------------------------------

      $device_cifra =  $dbh->query("SELECT * FROM _inf_device where enable = 1 and department = 69"); 
      echo '<div>'. 
            '<select class="btn mt-1 btn-outline-warning btn-block cifra_device" id = "cifra_device">';     
            echo "<option value='0'>Выберите оборудование</option>"; 
      ?>
      
      <?php
      
          while($dc = $device_cifra->fetch()){  
            foreach ($dc as $key => $value) 
              $dc[$key]=iconv('windows-1251', 'UTF-8', $value); 
              echo "<option value=".$dc[0].">".$dc[2]."</option>";   
          }
      ?>
        
      <?php
          echo '</select>'.
      '</div>';  
      // -------------------------------------------------------------------------------------------------
    }
       echo "</div>"; // echo "<div class = 'plita_edit'>";
  

    }

    if ($u[calc_IS_Close]==0) {
      echo "<div class = 'block_tiraj'>";
      echo "<hr id='hr_hude'>\n";
      echo "<div class='gl-tiraj'>
              <h5 class='tiraj'>Для закрытия тиража</h5>
              <input class='form-control qty' type='text' id='workqty' value=".round($u[qty] + $u[qty_obrazec_prigon] - $u[qty_made])."> <h5>шт.</h5> </div>";    
      echo "</div>";

      echo '<div id="request_result">  </div> ';

      echo "<hr>";

?>        


  
  
<?php

// -------------------------------------------------------------------------------------------------

//----------- Обработка статуса(открыт закрыт) операций кроме клише --------------
$sql = "SELECT * FROM _operation_work";
$ow1 = $dbh->query($sql);

while($ow = $ow1->fetch()) {  
  foreach ($ow as $key1 => $value1) 
    $ow[iconv('windows-1251', 'UTF-8', $key1)]=iconv('windows-1251', 'UTF-8', $value1);
    // Статус 0 - Когда нет записи в таблице тогда НАЧАТЬ
    // Статус 1 - Когда есть запись в таблице время начала тогда ЗАВЕРШИТЬ
    // Статус 2 - Когда есть запись в таблице время завершения но заказ не закрыт тогда НАЧАТЬ 

    if (($ow['Sale_id'] == $u[sale_id]) and ($ow['npp'] == $u[npp]) and ($ow['manager_begin'] > 0)) $Sale_id_in_base = 1; // Если есть запись с Sale id и npp то время начало операции и ключ сотрудника Begin уже есть  
    if (($ow['Sale_id'] == $u[sale_id]) and ($ow['npp'] == $u[npp]) and ($ow['manager_end']   > 0)) $Sale_id_in_base = 2; // 


} 
// -----------------------------------------------------------------------------------------

// ------- В зависимости от состояния операции начата или нет существует плита или нет ---
// -------------------- отображаются кнопки для старта операции --------------------------
 echo '<div class="button_op">';

  if ($u[_inf_workname_nomer] == 70008){
    if ($plita_status_made == -1) //Если плиты не существует то НАЧАТЬ ПОДГОТОВКУ
         echo '<input type="button" class=" btn btn-primary btn_start_op" name="" id="" value="Начать подготовку плиты    " onclick="start_op(1)">';                      
    if ($plita_status_made == 0)// Если плита существует и не закончена подготовка
          echo '<input type="button" class="btn btn-primary btn_start_op" name="" id="" value="Завершить подготовку плиты " onclick="start_op(2)"> '; 
    if ($plita_status_made == 1)// Если закончена подготовка плиты то начать выполнение   
            echo '<input type="button" class="btn btn-primary btn_start_op" name="" id="" value="Начать операцию   " onclick="start_op(3)">'; 
    if ($plita_status_made >= 2) // Если операция начата то закончить выполнение
              echo '<input type="button" class="btn btn-primary btn_start_op" name="" id="" value="Завершить операцию" onclick="start_op(4)">';    

  } 
  else {

          if ($Sale_id_in_base == 0) // Если нет записи в таблице с Sale id и npp  тоесть Если == 1 значит запись есть
            echo '<input type="button" class="btn btn-primary btn_start_op" name="" id="" value="Начать операцию   " onclick="start_op(3)">';
          if ($Sale_id_in_base == 1)
            echo '<input type="button" class="btn btn-primary btn_start_op" name="btn_start_op_4" id="" value="Завершить операцию" onclick="start_op(4)">';
          if ($Sale_id_in_base == 2)
            echo '<input type="button" class="btn btn-primary btn_start_op" name="btn_start_op_7" id="" value="Начать операцию(начатую)" onclick="start_op(7)">';

      echo '</div>'; 
    }        


  '<!------------ /Менюшка с выбором начала и окончания операции ------------------------->';
  
  echo  '</div> <!--<div class="button_action"> -->';


  echo '<div class="modal_exit"><button type="button" class="btn btn-secondary " data-dismiss="modal">Close</button></div>';




  ?>


  <?
    }
   }
   ?>