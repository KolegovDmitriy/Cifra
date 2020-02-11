<?

  require "config.php";

  // определяем участок
  if ($reg<1) $reg=88;

  $_SESSION[reg]=$reg;

  $treg[53]="Участок печати";
  $treg[25]="Участок вырубки и тиснения";
  $treg[28]="Участок упаковки";
  $treg[31]="Швейный участок";
  $treg[88]="Участок цифровой печати";

  // время отсечки далеких заказов для разных участков
  $tlim[53]="+5 day";
  $tlim[25]="+2 day";
  $tlim[28]="+2 day";
  $tlim[31]="+2 day";
  $tlim[88]="+5 day";


  function str_limit($s, $n=80)
  { 

    if (strlen($s)>$n) {
      $s = substr($s, 0, $n); //режем строку от 0 до limit
      
      $s= substr($s, 0, strrpos($s, ' ' ));    
      //берем часть обрезанной строки от 0 до последнего пробела
    }

    return $s;
  }

// тестовая база odbcad32  test_26_11_2019_9h

  try {
      //$dbh = new PDO("odbc:poni32_test1", $msdb_User, $msdb_Pass);// тестовая база
      $dbh = new PDO("odbc:poni32", $msdb_User, $msdb_Pass);
      $dbh->exec('SET CHARACTER SET utf8');
      } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        }
    
    
  $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT );  
  $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );  
  $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );    

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="ru">

  <head>
  
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="author" content="Gukin Anton, Kolegov Dmitriy">
  <meta http-equiv="refresh" content="1200">
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> 
  <link rel="stylesheet" type="text/css" href="css/works.css">
  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />

  <title><? echo $treg[$reg]; ?></title>
 
  <script src='js/jquery-2.0.3.min.js'></script>
  <script type="text/javascript" src="js/window.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>  
    
  </head>

  <body >   

    <?



  // --------------- Создание Select списка сотрудников цифры функция -------------------------------
  // -----получает данные из таблицы шаблоны дизайнеров в графиках в модальном окне -----------------

  
  echo '<div class="user_id_menu">';

  echo '<div class="panel text-center "><h3 text-center>Сотрудник: </h3>'.      
      '<select class="btn btn-info mb-2 ml-1" name="id_user" id="select_user">';   
      echo "<option value='0'>Не выбран</option>";

      $user_cifra =  $dbh->query("SELECT * FROM [dbo].[_php_desiner]()"); 
  ?>
  
  <?php
  
      while($uc = $user_cifra->fetch()){  
        foreach ($uc as $key => $value) 
          $uc[$key]=iconv('windows-1251', 'UTF-8', $value);
          echo "<option value=".$uc['id'].">".$uc['f']."</option>";              
      }

  ?>
    
  <?php
     echo '</select></div> <br><br>';  

echo '</div>'; //<!--class="user_id_menu" -->


$operation_w = $dbh->query('SELECT * FROM _operation_work') -> fetchAll(PDO::FETCH_UNIQUE);

     //iconv - функция переводящая строку в указанную кодировку 

    $sql = iconv('UTF-8', 'windows-1251', " SELECT * FROM [dbo].[_шаблоны_Антон_listworks_plita]($reg,1,0) 
                                                        order by [device_key] DESC,
                                                                [calc_IS_Close],
                                                                (case when status_made = 3 then 1 else 0 end ) asc, 
                                                                (case when PLITA <> 0 then 1 else 0 end ) desc , 
                                                                PLITA ,                                                                  
                                                                --[time_beg], 
                                                                [Это_Инди_01] DESC, 
                                                                [otgruzka_data] , 
                                                                [time_end]" );


    // [dbo].[_шаблоны_Антон_listworks] ( ключ_отдела, выводить_дату_отгрузки_1_или_0, выводить_список_дизайнеров_1_или_0 )

    $sth   =  $dbh->query($sql);

    if ($sth->rowCount() == 0) {
      ?>
      <br /><br /><br /><br />
      <h1>Заказы в очереди на выполнение не обнаруженны.</h1>
      <?
    } else {

      while($u = $sth->fetch()) {  

        foreach ($u as $key => $value) 
        $u[iconv('windows-1251', 'UTF-8', $key)]=iconv('windows-1251', 'UTF-8', $value);
               
       
        if ($u[device_key]!=$dk) {
        // Разделитель оборудования
        if ($dk>0) { ?> <br clear="all" /> <? }
       
        ?>

        <!-- //if ($u[device_key] == 116) echo "<button type='button' class='btn btn-outline-secondary btn-sm ml-2 mb-1 btn_clear'>Редактировать</button>"; -->
        <?php //if ($u[device_key] == 116) echo "<button type='button' class='btn btn-outline-secondary btn-sm mb-1  btn_clear'>Очистить</button>"; ?>
        <div><h2 class='myh2'>  <? echo "* ".$u[device_name]." *";  ?></h2></div>

        <?
          $dk=$u[device_key];
          }

          if ((strtotime($tlim[$reg])>strtotime(substr($u[time_beg],0,10))) OR ($_SESSION[reg]==88)) {
            // отсекаем очень далекие заказы

            echo "<div  class=\"";

            if ($u[Это_Инди_01]==1) echo "indi";

            if ((strtotime("now")>strtotime($u[Дата_отгрузки])) and ($u[calc_IS_Close]!=1)) {
                echo "verybad";
              } elseif ($u[calc_IS_Close]==1) { 
                echo "greey";
                } elseif (strtotime("-1 day")>strtotime(substr($u[time_end],0,10))) { 
                  echo "red";
                  } elseif (strtotime("now")>strtotime(substr($u[time_beg],0,10))) { 
                    echo "yellow";
                    } else {
                        echo "green";
                      }         
            echo " block_plita\">";

            if ($u[calc_IS_Close] == 1) {// закрытые заказы                              
              echo "<div class='zname'>".str_limit($u[_inf_workname_name])."</div>";        
              echo "<div class='zzakaz'>".$u[Договор_Номер]."/".$u[позиция_в_Договоре]."</div>";       
              // echo '<button type="button" class="btn btn-dark">'.$u[Договор_Номер].'/'.$u[позиция_в_Договоре].'</button>';       
              echo "<div>".round($u[qty_made])." шт<br>"."Закрыто:".date("d M",strtotime($u[time_made]))."<br>".$u[Кем_выполнено]."</div>";
            }  else {// не закрытые заказы
                 echo "<div class='zname'>".str_limit($u[_inf_workname_name]);   

                if (($u[_inf_workname_nomer]==70008) or ($u[_inf_workname_nomer]==70058)) {
                          echo '<input type="checkbox" name ="check_plita[]" class="clcheckbox" id="idcheckbox" value='.$u[sale_id].':'.$u[npp].'!'.$u[Договор_Номер]."/".$u[позиция_в_Договоре].' onchange = "plita_key()">'; // для изготовления плит 
       
                  }
                echo "</div>";
                
               echo "<div>";
               echo "<div class=\"nzakaz\"><button type='button' class='btn btn-outline-secondary  p-0 m-0 btn-lg btn-block btn-nzakaz ";
              //-------------------------------------------------------------------------------
              //if ($u[started_01]!=1) //------- Выполнение начато 
     
              if($u[stm] == 2) echo "active"; // Выполнение начато для операций клише

              foreach ($operation_w as $keyow => $valueow) {
                  if (($u[sale_id] == $valueow[Sale_id]) and ($u[npp] ==  $valueow[npp]) and ($valueow[manager_end] == 0) ){ // для ПОНИ осовной 
                  //if (($u[sale_id] == $keyow) and ($u[npp] ==  $valueow[0]) and ($valueow[5] == 0)){  // для тестовой базы
                  echo "active";// Выполнение начато для операций кроме клише 
                } 
              }                
           //---------------------------------------------------------------------------------                           
                      echo "' data-toggle='modal' data-target='#exampleModalCenter' onclick=\"set(".$u[sale_id].",".$u[npp].",".$u[Договор_Номер].",".$u[позиция_в_Договоре].") ; return false;\"> ".$u[Договор_Номер]."/".$u[позиция_в_Договоре];
                  echo " </button></div>";

                    echo "<div class='count'>";
                      echo round($u[qty])." (".round($u[qty_obrazec_prigon]).")";
                      if ($u[qty_made]>0) echo "-".round($u[qty_made]);
                      echo "шт<br>".date("d M",strtotime($u[time_beg]))." -- ".date("d M",strtotime($u[time_end]));
                    echo "</div>";
                echo "</div>";
            
               echo "<div>".str_limit($u[comment])."</div>";

                echo "<div>";
                if ($u[device_key]==116) {
                  // дополнительная отметка для фрезы.        
                  echo "<p>Плита №:&nbsp;<p class='plita_n'>"?>  <?php 
                  echo $u[PLITA];
                  echo "</p>&nbsp;Станок:"; 
                    if ($u[device_plita] == 133) echo "б/у - НЕМЕЦ"; elseif ($u[device_plita] == 134) echo "SD-80709 КИТ"; else  $u[device_plita]; 
                  echo "</p><br>";              
                }            
                echo "<p>Отгрузка:".date("d M",strtotime($u[Дата_отгрузки]))."</div>";
              }

             echo "</div>";
             echo "</div>";

          }//else {// не закрытые заказы
        } //if ((strtotime($tlim[$reg])>strtotime(substr($u[time_beg],0,10))) OR ($_SESSION[reg]==88))
      }//while($u = $sth->fetch()) {

        
?>
  
  <?php

?>

      <!-- Modal -->
      <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" > 
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">

            <div class="modal-header"><h4></h4>
              <!-- <h5 class="modal-title" id="exampleModalLongTitle">Modal titleямясч</h5> -->
              <h5 class="modal-title" id="exampleModalLongTitle"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
             </div><!-- class="modal-header" -->

            <div class="modal-body" id="div-modal">
             </div> <!--class="modal-body" -->        

           </div> <!--class="modal-content" -->
         </div> <!--     class="modal-dialog   -->
     </div> <!--  class="modal fade"  -->
      <!-- Modal -->





  </body>
</html>


