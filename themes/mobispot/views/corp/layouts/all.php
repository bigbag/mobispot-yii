<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" class="ng-app"  ng-app="mobispot" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" class="ng-app" ng-app="mobispot" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9" class="ng-app" ng-app="mobispot" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" ng-app="mobispot" lang="en"> <!--<![endif]-->

<?php include('block/head.php');?>
<body ng-init="payment.token='<?php echo Yii::app()->request->csrfToken;?>'; user.token='<?php echo Yii::app()->request->csrfToken;?>'">
<div class="content-wrapper content-payment" ng-controller="PaymentController">
  <?php include('block/header/all.php');?>
  <?php echo $content; ?>

  <div class="popup slow bg-gray hide">
    <div class="row popup-content content-settings">
      <div class="twelve columns">
        <h4>Функция «Автопополнение»</h4>
        <p>
          Функция «Автопополнение» позволяет вам автоматически пополнять баланс
          вашей кампусной карты при помощи рекуррентных платежей с вашей банковской карты.
          Подключение данной функции означает ваше согласие
          на автоматическое списание с вашей банковской карты
          указанной вами суммы каждый раз, когда баланс вашей кампусной карты
          будет опускаться ниже 40 рублей 00 коп.
        </p>
        <p>
          Для того, чтобы подключить функцию автопополнения, Вам нужно:
          <ol>
            <li>
              1. Совершить разовую процедуру пополнения кампусной карты
              при помощи банковской карты.
            </li>
            <li>
              2. Прочитать и согласиться с условиями подключения услуги
              «Автопополнения», поставив «флажок» в соответсвующей строке внутри интерфейса
              кампусной карты.
            </li>
            <li>
              3. Указать сумму, на которую будет автоматически пополняться
              баланс вашей кампусной карты.
            </li>
            <li>
              4. Нажать кнопку «Подключить».
            </li>
          </ol>
        </p>
        <p>
          Автоматическое пополнение будет осуществляться при помощи банковской карты,
          с которой была произведена процедура разового пополнения кампусной карты.
        </p>
        <p>
          В личном кабинете «Мобиспот» Вы можете наглядно увидеть карты,
          с которых происходит Автопополнение вашей кампусной карты
          (4 последние цифры номера), дату подключения функции автопополнения,
          а также  в любой момент самостоятельно.
        </p>
        <h5>Выгоды от функции «Автопополнение»</h5>
        <p>
          Подключив функцию «Автопополнение» для своей кампусной карты,
          Вы получаете возможность «забыть» о постоянном пополнении кампусных карт,
          согласившись на автоматическое пополнение.
        </p>
        <p>
          Вы можете самостоятельно определить сумму,
          на которую будет автоматически пополняться кампусная карта «Мобиспот». 
          Максимальная сумма автопополнения - 900 рублей 00 коп.
        </p>
        <p>
          Обращаем Ваше внимание, что при отсутствии средств на Банковской карте –
          автоматическое пополнение кампусной карты «Мобиспот» НЕ ВОЗМОЖНО. 
        </p>
        <p>
          Так же Банк-эмитент (выпустивший вашу карту) может запрещать
          автоматическое списание с ряда своих банковских карт,
          в соответствии с условиями  вашего договора с Банком. Пожалуйста,
          уточняйте данную информацию в службе поддержки клиентов банка-эмитента.
        </p>
      </div>
    </div>
    <div class="row popup-content content-wallet">
      <div class="twelve columns">
        <h4>Оплата банковской картой в сети Интернет</h4>
        <p>
          Наш магазин подключен к <a href="http://www.uniteller.ru/">интернет-эквайрингу</a> и Вы можете оплатить свой заказ
          банковской картой Visa или Mastercard. После подтверждения заказа Вы будете
          перенаправлены на защищенную платежную страницу <a href="http://www.uniteller.ru/Info/ru/109">процессингового центра Uniteller</a>,
          где Вам необходимо ввести данные Вашей банковской карты. Для дополнительной
          аутентификации держателя карты используется протокол 3D Secure. Если Ваш Банк
          поддерживает данную технологию, Вы будете перенаправлены на его сервер для
          дополнительной идентификации. Информацию о правилах и методах дополнительной
          идентификации уточняйте в Банке, выдавшем Вам банковскую карту.
        </p>
        <h4>Гарантии безопасности</h4>
        <p>
          Сервис-провайдер Uniteller защищает и обрабатывает данные Вашей банковской карты
          по стандарту безопасности PCI DSS 2.0. Передача информации в <a href="http://www.uniteller.ru/Info/ru/109">платежный шлюз</a>
          Uniteller происходит с применением технологии шифрования SSL. Дальнейшая передача
          информации происходит по закрытым банковским сетям, имеющим наивысший уровень
          надежности. Uniteller не передает данные Вашей карты нам (Mobispot ИП Волгин) и иным
          третьим лицам. Для дополнительной аутентификации держателя карты используется
          протокол 3D Secure.
        </p>
        <p>
          В случае, если у Вас есть вопросы по совершенному платежу, Вы можете обратиться в
          службу поддержки клиентов <a href="mailto:support@uniteller.ru">support@uniteller.ru</a> или по телефону (495) 987-19-60.
        </p>
        <img src="/themes/mobispot/images/mps.png" alt="payment-icon">
      </div>
    </div>
  </div>
</div>

<?php include('block/footer.php');?>
<?php include('block/script.php');?>
</body>
</html>
