var gsi = 0; // глобальная переменная по js в кот передаётся Sale id
var gnpp = 0; // глобальная переменная по js в кот передаётся npp

function set(si, npp, zakaz, nzakaz) {
  // console.log('----------------- передано в функцию SET ---------------------');
  // console.log('|si' + si + '|npp' + npp + '|zakaz' + zakaz + '|nzakaz' + nzakaz + '|');
  // console.log('count_si_npp' + count_si_npp);
  // console.log('count_zakaz' + count_zakaz);
  // console.log('--------------------------------------------------------------');

  gsi = si; // делаем глобальный SALE ID
  gnpp = npp; // делаем глобальный npp

  $.ajax({
    url:
      "fullzakaz.php?si=" +
      si +
      "&npp=" +
      npp +
      "&count_si_npp=" +
      count_si_npp +
      "&count_zakaz=" +
      count_zakaz,
    cache: false,
    beforeSend: function() {
      $("div.modal-footer").remove(); // очистка кнопки close куоторая дабавляется в start_op аякс запросе
      $(".modal-header>h4").html(""); // очистка заказов
      $("#div-modal").html(
        "<h1>Загрузка данных</h1><br>" +
          '<div class="d-flex justify-content-center"><div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div></div>   '
      ); // спинер загрузки
    },
    success: function(html) {
      if (count_zakaz == "") {
        $(".modal-header>h4")
          .html("Заказ:" + "&nbsp" + zakaz + "/" + nzakaz)
          .css({ "margin-left": "140px", "font-size": "30px" }); // выводит в заголовок номер заказа и позицию
      }

      $("#div-modal").html(html);

      if ($(".btn_start_op").attr("name") == "btn_start_op_4") {
        $(".block_tiraj").css("display", "block"); // включает блок с вводом тиража только для операций кроме клише и только для начатой операции
      }
    }
  });
}

// --- Функция получения ключа оборудования для изготовления клише -----------
function key_device() {
  let n = document.getElementById("cifra_device").options.selectedIndex;
  let key_device = document.getElementById("cifra_device").options[n].value;
  console.log("key_device" + key_device);
  console.log(n);
  return key_device;
}

// --------------- Начать и завершить операцию --------------------------------
function start_op(BegOrEnd) {
  // BegOrEnd - параметр 1 - начать подготовку плиты, 2 - завершить подготовку плиты, 3 - начать операцию, 4 - завершить операцию

  let plita_num = $("#plita_modal")
    .text()
    .substr(8);
  let qty = document.getElementById("workqty").value;

  let id = id_user();

  if (id <= 0) {
    //alert('Не выбран сотрудник'); перенесён в функцию
    return;
  }

  id = "u" + id;

  let device = 0;

  if (BegOrEnd == 3 && count_si_npp != "") {
    device = key_device();
    if (device == 0) {
      alert("Выберите оборудование");
      return;
    }
  }

  // console.log("device " + device);
  // console.log(
  //   "--------------------- запуск аякс запроса на закрытие --------------"
  // );
  // console.log(
  //   "BegOrEnd:" +
  //     BegOrEnd +
  //     " " +
  //     "si:" +
  //     gsi +
  //     "npp:" +
  //     gnpp +
  //     " " +
  //     "qty:" +
  //     qty +
  //     "count_si_npp" +
  //     count_si_npp +
  //     "id" +
  //     id +
  //     "count_zakaz" +
  //     count_zakaz +
  //     "&count_si_npp_edit=" +
  //     count_si_npp_edit +
  //     "&plita_num=" +
  //     plita_num +
  //     "device" +
  //     device
  // );
  // console.log(
  //   "--------------------------------------------------------------------"
  // );

  // ----------------------------------
  $.ajax({
    url:
      "closezakaz.php?si=" +
      gsi +
      "&npp=" +
      gnpp +
      "&id=" +
      id +
      "&qty=" +
      qty +
      "&BegOrEnd=" +
      BegOrEnd +
      "&count_si_npp=" +
      count_si_npp +
      "&count_zakaz=" +
      count_zakaz +
      "&count_si_npp_edit=" +
      count_si_npp_edit +
      "&plita_num=" +
      plita_num +
      "&device=" +
      device,
    cache: false,
    beforeSend: function() {
      $("#div-modal").html(
        '<div align="center"><h1>Закрытие операции.<h1></div> ' +
          '<div class="d-flex justify-content-center"><div class="spinner-border text-secondary" role="status"><span class="sr-only">Loading...</span></div></div>   '
      );
      $(".modal-header>h4").html("");
      $(".modal-header>h5").html("");
    },
    success: function(html) {
      $("#div-modal").html(html);
      $(".modal-content").append(
        '<div class="modal-footer"> <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button></div>'
      );
      // $("#request_result").html(html);
      func_my_alert(); // выводит сообщение запрос выполнен и скрывает его через несколько секунд после этого закрывает модальное окно
    }
  });
  // ----------------------------------
}

function inp_zakaz() {
  // обработка ввода заказа при добавлении в плиту
  //console.clear();
  let x = $(".add_zakaz_v_plitu").val(); // получение значение input
  let a = 0; // 0 - ошибка, 1 - ошибок нет при валидации ввода
  // 1 (условие) - первый символ не равен 0 и /, 2 - последний символ не равен / и 0, 4 - количество / равно 1
  if (
    x[0] != 0 &&
    x[0] != "/" &&
    x[x.length - 1] != "/" &&
    x[x.length - 1] != 0 &&
    x.split("/").length - 1 == 1
  )
    a = 1;
  if (a == 1) return 1;
  else return 0;
}

// --------------- Редактировать плиту ---------------------
function edit_plita(BegOrEnd) {
  // BegOrEnd - параметр 5 - Удалить заказы из плиты, 6 - Добавить заказ в плиту

  //--- обработка не правильного заказа для добавления в плиту ---
  // input ввода добавления заказа подсвечивает красным
  if (BegOrEnd == 6 && inp_zakaz() == 0) {
    $(".add_zakaz_v_plitu").css({
      border: "2px solid red !important",
      "background-color": "#DC143C"
    });
    $(".add_zakaz_v_plitu").val("");
    window.setTimeout(function() {
      $(".add_zakaz_v_plitu").css({
        border: "1px solid grey !important",
        "background-color": "white"
      });
    }, 1500); // 1000 - время

    return;
  }

  if (BegOrEnd == 6 && inp_zakaz() == 1) {
    //
    $(".add_zakaz_v_plitu").css({
      border: "2px solid 	#006400 !important",
      "background-color": "#D4EDDA"
    });
  }
  // -----------------------------------------------------------

  let device = 0;
  if (BegOrEnd == 3) {
    // получение ключа оборудования только для плиты и только для операции начать операцию (начать изготовление плиты)
    let device = key_device();
  }
  // -- Получить в переменную ключи заказов которые нужно удалить --
  var zakazi_del_is_pliti = ""; // Получить в переменную Saleid и npp
  var add_si; // Sale id для добавления
  var add_npp; // npp для добавления

  let id = id_user();

  if (id <= 0) {
    //alert('Не выбран сотрудник'); перенесён в функцию
    return;
  }

  id = "u" + id;
  let qty = document.getElementById("workqty").value;
  let add_zakaz = $(".add_zakaz_v_plitu").val();
  let plita_num = $("#plita_modal")
    .text()
    .substr(8);
  plita_key_edit();

  // ---- Для проверки переданных параметров в аякс запрос -----------------
  // console.log('plita_num---' + plita_num + '---');
  // console.log('add_zakaz' + add_zakaz);
  // console.log('--------------------- аякс запрос на удаление заказов из плиты --------------');
  // console.log('BegOrEnd:' + BegOrEnd + 'count_si_npp:' + count_si_npp + 'count_zakaz:' + count_zakaz);
  // console.log('-----------------------------------------------------------------------------');
  //console.log('====================');
  //console.log('si=' + gsi + '&npp=' + gnpp + '&id=' + id + '&qty=' + qty + ' & BegOrEnd=' + BegOrEnd + 'count_si_npp = ' + count_si_npp + 'count_zakaz = ' + count_zakaz + 'count_si_npp_edit = ' + count_si_npp_edit + 'add_zakaz' + add_zakaz + 'plita_num' + plita_num + '&device=' + device);
  //console.log('====================');

  $.ajax({
    url:
      "closezakaz.php?si=" +
      gsi +
      "&npp=" +
      gnpp +
      "&id=" +
      id +
      "&qty=" +
      qty +
      "&BegOrEnd=" +
      BegOrEnd +
      "&count_si_npp=" +
      count_si_npp +
      "&count_zakaz=" +
      count_zakaz +
      "&count_si_npp_edit=" +
      count_si_npp_edit +
      "&add_zakaz=" +
      add_zakaz +
      "&plita_num=" +
      plita_num +
      "&device=" +
      device,
    cache: false,
    beforeSend: function() {
      // предварительно ничего не делать
    },
    success: function(html) {
      // после отправки запроса
      if (BegOrEnd == 5) {
        //console.log('BegOrEnd = 5 - Удаление');
        $("#div-modal").html(html);
        // удалить блоки заказ с чекбоксом
      }

      if (BegOrEnd == 6) {
        // Добавить блок с чек боксом
        $("#div-modal").html(html);
        //console.log('BegOrEnd = 6 - Добавление');
      }
      $(".modal-content").append(
        '<div class="modal-footer"> <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button></div>'
      );
      func_my_alert(); // выводит сообщение запрос выполнен и скрывает его через несколько секунд после этого закрывает модальное окно
    }
  });
  // ----------------------------------
}

//-- Записывает ключи и npp в строку для редактирования плиты ----
var count_si_npp_edit = ""; // переменная для редактирования всех checkbox
//var count_zakaz_edit = '';

function plita_key_edit() {
  // Функция для получения ключей плиты
  let a = $(".zakaz_v_plite input:checked"); //выбираем все отмеченные checkbox
  count_si_npp_edit = ""; // переменная для  всех checkbox
  //count_zakaz = '';
  //var vv = (a[0].value.split("!"));
  //console.log(vv[0]);

  for (let x = 0; x < a.length; x++) {
    //перебераем все объекты
    let vb = a[x].value.split("!");
    count_si_npp_edit = count_si_npp_edit + vb[0] + "!";
  }
  //console.log('count_si_npp_edit' + count_si_npp_edit);
  //console.log('count_zakaz' + count_zakaz);
}

// -------------------------------------------------------------

//------------- Записывает ключи и npp в строку ----------------
var count_si_npp = ""; // переменная для  всех checkbox
var count_zakaz = ""; // переменная для отображения выбранных заказов в плиту для модального окна

function plita_key() {
  let a = $(".block_plita input:checked"); //выбираем все отмеченные checkbox
  count_si_npp = ""; // переменная для  всех checkbox
  count_zakaz = "";

  for (let x = 0; x < a.length; x++) {
    //перебераем все объекты
    var vb = a[x].value.split("!");
    count_si_npp = count_si_npp + vb[0] + "!";
    count_zakaz = count_zakaz + vb[1] + ";";
  }
  //console.clear();
  //console.log('count_si_npp' + count_si_npp);
  //console.log('count_zakaz' + count_zakaz);
}

//----------  Кнопка закрыть диалоговое окно -------------------
function m_exit() {
  // document.getElementById('layer').style.display = 'none';
  $("body input:checkbox").prop("checked", false); // снимает все галки с чекбоксов
  count_si_npp = ""; // обнуление переменной со всеми выбранными ключами для плиты
  $("div").removeClass("vibor-div"); // убирает выделение div
  $(".modal-content").remove(".modal-footer");
  count_zakaz = "";
}

// -- Закрытие модального окна и alerta (сообщение о выполнении операции)--
// -- параметр 1500 = 1,5 сек и тд. после аякс запроса ----
// -- тоесть происходит автоматичекое закрытие модального окна --
function func_my_alert() {
  // window.setTimeout(function() {
  //   $("#my-alert").alert("close"); // скрыть алерт аякс запроса
  // }, 1500); // 1000 - время
  // window.setTimeout(function() {
  //   $("#exampleModalCenter").modal("hide"); // скрыть модальное окно
  // }, 1500);
}
// -------- Получение id сотрудника из select --------
function id_user() {
  // Пропуск изначально был по штрихкоду, далее передаётся в функцию dbo._шаблоны_kiosk_сотрудник_по_штрихкоду('id') в формате u101767
  // который даёт ключ сотрудника можно переделать чтоб сразу выдавал ключ
  let n = document.getElementById("select_user").options.selectedIndex;
  let id_user = document.getElementById("select_user").options[n].value;
  // console.log(id_user);
  // console.log(n);
  if (id_user == "" || id_user == 0) alert("Не выбран сотрудник");
  return id_user;
}

// ----------------- после загрузки документа вызывает события ---------
$(document).ready(function() {
  // ---------- при загрузке устонавливаем id сотрудника ---
  let id_user_ls = localStorage.getItem("id_user"); // получаем из Local Storage ключ сотрудника
  if (id_user_ls == null) id_user_ls = 0;
  //console.log('ID ИЗ LOCALSTORAGE' + id_user_ls); // ключ сотрудника в локальной памяти
  $("#select_user").val(id_user_ls);
  // -------------------------------------------------------------------

  // ----- ключ сотрудника передаем в localStorage ---
  $("#select_user").change(function() {
    let a = document.getElementById("select_user").options.selectedIndex;
    let id_user = document.getElementById("select_user").options[a].value;
    //console.log('change сработал id сотрудника  id_user =' + id_user);
    localStorage.setItem("id_user", id_user);
  });

  // --- обработчик исключения снятия выделения блока при нажатии на кнопку для вызова модального окна --
  $(".btn-nzakaz").click(function() {
    $(this)
      .parent("div")
      .parent("div")
      .parent("div")
      .find("input")
      .trigger("click");
  });

  // -------- Div выбранный для плиты подсвечивается ----------
  //готовая функция по двойному клику находит нажат ли checkbox
  //если нажат добавляет класс диву если нет то удаляет
  var select_plita = 0;

  $(".block_plita").click(function vibor() {
    // по клику на div запускает функцию выборки блоков

    // ---------- Выбор по клику всех блоков в одной плите -----------
    var r = $(this)
      .find(".plita_n")
      .html(); // получает номер плиты
    if (r > 0) {
      // ----
      //console.clear();
      $("body input:checkbox").prop("checked", false); // снимет галочку
      $("div").removeClass("vibor-div"); // убирает выделение div
      // ----
      $(".block_plita:has(.plita_n:contains(" + r + "))")
        .find("input")
        .prop("checked", true); // Выбирает блоки с номером плиты и сравнивает с номером на кликнутом блоке и делает выбор чекбокса
      $(".block_plita:has(.plita_n:contains(" + r + "))").addClass("vibor-div"); // Выбирает блоки с номером плиты и сравнивает с номером на кликнутом блоке и присваивает стиль блоку
      plita_key(); // передаём si и npp выбранных блоков
      // --------------------------------------------------------------
    } else {
      if (
        $(".vibor-div")
          .find(".plita_n")
          .html() != 0
      )
        select_plita = $(".vibor-div")
          .find(".plita_n")
          .html(); // -- Проверка выбираем все выбранные блоки и находим номер плиты

      $(".block_plita:has(.plita_n:contains(" + select_plita + "))")
        .find("input")
        .prop("checked", false); // сбросить плиту с номером
      $(
        ".block_plita:has(.plita_n:contains(" + select_plita + "))"
      ).removeClass("vibor-div"); // отменить стиль для плиты

      // ------------------ Одиночный выбор блока для формирования плиты ------------------
      if ($(this).find("input").length == 1) {
        // проверяет есть ли в dive checkbox
        if (
          $(this)
            .find("input")
            .is(":checked")
        ) {
          // если есть checkbox то проверяет чекнут или нет checkbox
          $(this)
            .find("input")
            .trigger("click"); // имитация слика по checkbox
          $(this).removeClass("vibor-div"); // удаляет класс с выделением блока
        } else {
          $(this)
            .find("input")
            .trigger("click");
          $(this).addClass("vibor-div");
        }
      }
      // --------------------------------------------------------------------------------
    } //if (r > 0) --- else {
  }); //$('.block_plita').click(function () { // по клику на div запускает функцию выборки блоков

  // ----- кнопка Очистить расположенная на заголовке операции * Фрезерный станок * ----
  // $('.btn_clear').click(function () {
  //   $('body input:checkbox').prop('checked', false);// снимет галочку
  //   $('div').removeClass('vibor-div');// убирает выделение div
  //   count_si_npp = '';// обнуляет ключи si и npp
  //   count_zakaz = '';// обнуляет список закаов
  // });

  $("#exampleModalCenter").on("hidden.bs.modal", function(e) {
    // при закрытии модального окна обнуляется выделение плиты
    $("body input:checkbox").prop("checked", false); // снимет галочку
    $("div").removeClass("vibor-div"); // убирает выделение div
    count_si_npp = ""; // обнуляет ключи si и npp
    count_zakaz = ""; // обнуляет список закаов
    location.reload(true); // перезагрузка страницы
  });
  // ------------------------------------------------------------------------------------
});
