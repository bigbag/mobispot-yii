'use strict';

angular.module('mobispot').controller('PaymentController', 
  function($scope, $http, $compile, $timeout, contentService) {

  $scope.card = 1;
  $scope.history = [];

  //Параметры по умолчанию для пагинации
  $scope.pagination = {
    cur: 1,
    total: 7,
    display: 20
  };

  $scope.$watch('payment.amount + payment.status', function(payment) {
    if ($scope.payment) {
      if ($scope.payment.amount && ($scope.payment.amount > 99) && $scope.payment.status !=-1){
        var delta = 1000 - $scope.payment.balance;
        if (delta - $scope.payment.amount >= 0) {
          angular.element('#add-button').removeClass('button-disable');
        }
        else {
          angular.element('#add-button').addClass('button-disable');
        }
      }
      else {
        angular.element('#add-button').addClass('button-disable');
      }
    }
  });

  //Пополнение кошелька
  $scope.addSumm = function(payment, e){
    var delta = 1000 - $scope.payment.balance;
    if (payment.amount && payment.wallet_id && $scope.payment.amount>99 && (delta - $scope.payment.amount >= 0)){
      var paymentForm = angular.element(e.currentTarget).parent().parent().parent().parent();
      $http.post('/wallet/addSumm', payment).success(function(data){
        if(data.error == 'no') {
          var order = data.order;
          paymentForm.append($compile(data.content)($scope));
          paymentForm.submit();
          delete $scope.payment.amount;
        }
      });
    }
  }

  //Меняем статус активности кнопки включить автоплатежи в зависимости от валидности формы
  $scope.$watch('recurrent.amount + recurrent.history_id + recurrent.terms', function(recurrent) {
    if ($scope.recurrent) {
      var activButton = angular.element('#buttonApayOn');
      if ($scope.recurrent.amount && $scope.recurrent.terms){
        if (($scope.recurrent.terms == 1) && ($scope.recurrent.amount < 901) && ($scope.recurrent.amount > 99)) {
          activButton.removeClass('button-disable');
        }
        else {
          activButton.addClass('button-disable');
        }
      }
      else {
        activButton.addClass('button-disable');
      }
    }
  });

  // Атрибут согласия с условиями автоплатежей
  $scope.setRecurrentTerms = function(recurrent){
    if (recurrent.terms == 1) recurrent.terms = 0;
    else recurrent.terms = 1;
  };

  //Включение автоплатежей
  $scope.enableRecurrent = function(recurrent, valid) {
    if (!valid || !($scope.recurrent.terms == 1)) return false;

    recurrent.token = $scope.user.token
    $http.post('/wallet/recurrent', recurrent).success(function(data) {
      if (data.error == 'no') {
        angular.element('#disableReccurent').show();
        angular.element('#enableReccurent').hide();
        angular.element('#auto-payment .m-auto-payment').addClass('active');
        angular.element('#auto-payment-summ').text($scope.recurrent.amount);
        angular.element('#card_pan span.m-card-info').text(data.auto.pan);
        angular.element('#card_date span.m-card-info').text(data.auto.date);
        $scope.recurrent.terms = 0;
        angular.element('a.checkbox.agree').removeClass('active');
        angular.element('#buttonApayOn').addClass('button-disable');
        var systemImg = angular.element('#disableReccurent .m-card-cur');
        systemImg.removeClass();
        systemImg.addClass('m-card-cur');

        if (typeof (data.system_class) != 'undefined' && data.system_class.length > 0)
            systemImg.addClass(data.system_class);
        else
            systemImg.addClass('m-card_uniteller');
        contentService.setModal(data.content, 'none');
      }
      
    });
  };

  //Выключение автоплатежей
  $scope.disableRecurrent = function(payment) {
    payment.token = $scope.user.token
    $http.post('/wallet/recurrent', payment).success(function(data) {
      if (data.error == 'no') {
        angular.element('#disableReccurent').hide();
        angular.element('#enableReccurent').show();
        angular.element('#auto-payment .m-auto-payment').removeClass('active');
        contentService.setModal(data.content, 'none');
      }
    });
  };


  //Выбор типа заявки на регистрацию
  $scope.setAction = function(action, e){
    $scope.action = action;
    var curent = angular.element(e.currentTarget).next();
    var all = angular.element('.corp-register-form');
    if (curent.hasClass('hide')) {
      all.slideUp('slow', function () {
        $(this).addClass('hide');
      });
      curent.slideToggle('slow', function () {
        $(this).removeClass('hide');
      });
      }
  }

  //Отправка заявки на регистрацию
  $scope.corpRegister = function(){
    if ($scope.action){
      if ($scope.action == 'connection') var params = $scope.connection;
      else if ($scope.action == 'rent') var params = $scope.rent;
      else if ($scope.action == 'self') var params = $scope.self;

      console.log($scope.self);
    }

  }

  $scope.accordion = function(e, payment) {
    var spot = angular.element(e.currentTarget).parent();
    var id = spot.attr('id');
    var spotContent = spot.find('.slide-content');
    var spotHat = spot.find('.spot-hat');

    $scope.payment.wallet_id = id;
    $scope.keys = [];
    if (spotContent.attr('class') == null) {
      $http.post('/wallet/getView', payment).success(function(data) {
          if(data.error == 'no') {
            var oldSpotContent = angular.element('.slide-content');
            angular.element('.spot-content_li').removeClass('open');
            oldSpotContent.slideUp('slow', function () {
              oldSpotContent.remove();
            });

            spotHat.after($compile(data.content)($scope));
            spot.addClass('open');
            spot.find('.slide-content').slideToggle('slow');

            if (typeof $.foundation !== 'undefined' && typeof $.foundation.customForms !== 'undefined') {
              angular.element('body').foundationCustomForms();
            } else {
              angular.element('body').foundation('forms');
            }
            
            $('#filter-date').datepicker();
            $('#filter-date').datepicker("option", "dateFormat", "dd.mm.yy");
            $('#ui-datepicker-div').slideUp(0);
            angular.element('#j-settingsForm').addClass('slide-content');
          }
      });
    }
    else {
      delete $scope.payment.amount;
      spot.removeClass('open');
      spotContent.slideUp('slow',
        function () {
          spotContent.remove();
        });
    }
  }

  $scope.setAuto = function(){
    if ($scope.card == 1) $scope.card = 0;
    else $scope.card = 1;
  };

  $scope.blockWallet = function(id){
    var payment = $scope.payment;
    payment.id=id;
    $http.post('/wallet/blockWallet', payment).success(function(data) {
      if(data.error == 'no') {
        angular.element('#block-button').text(data.content);
        angular.element('#block-button').toggleClass('red-button green-button');
        if (angular.element('li#'+id).size())
            angular.element('li#'+id).toggleClass('invisible-spot');
        if (angular.element('#wallet-block').size())
            angular.element('#wallet-block').toggleClass('invisible-spot');
        $scope.recurrent.terms = 0;
        angular.element('a.checkbox.agree').removeClass('active');
        angular.element('#buttonApayOn').addClass('button-disable');
        $scope.payment.status=data.status;
      }
    });
  };

    // Атрибут согласия с условиями сервиса
  $scope.setTerms = function(payment){
    if (payment.terms == 1) payment.terms = 0;
    else payment.terms = 1;
  };

  // Следим за полями добавления карты
  $scope.$watch('payment.code + payment.terms', function(payment) {
    if ($scope.payment && $scope.payment.code){
      if (($scope.payment.terms == 1) && ($scope.payment.code.length == 10)) {
        angular.element('#add-spot .form-control a').removeClass('button-disable');
      }
      else {
        angular.element('#add-spot .form-control a').addClass('button-disable');
      }
    }

  });

  // Добавление карты
  $scope.addWallet = function(payment) {
    if (!payment.code | ($scope.payment.terms == 0)) return false;

    $http.post('/wallet/addWallet', payment).success(function(data) {
      if(data.error == 'no') {
        var spotAdd = angular.element('#actSpotForm')
        angular.element('.spot-list').append($compile(data.content)($scope));
        spotAdd.find('a.checkbox').toggleClass('active');
        spotAdd.hide();
        delete $scope.payment.code;
      }
      else if (data.error == 'yes') {
        angular.element('#actSpotForm input[name=code]').addClass('error');
        angular.element('#actSpotForm input[name=name]').addClass('error');
      }
    });
  };

  //Запрос истории последних операций
  $scope.getHistory = function(search) {
    if(typeof(search.page)==='undefined') search.page = 1;
    search.token = $scope.payment.token;
    
    $http.post('/wallet/getHistory', search).success(function(data) {
      $scope.result = data.result;
      $scope.search.page_count = data.count;
      $scope.pagination.total = Math.ceil(data.count/$scope.search.limit) - 1;
    });
  };

   //Тригер на запрос истории
  $scope.$watch('pagination.cur', function() {
    if (!$scope.search) return false;
    var search = $scope.search;
    search.page = $scope.pagination.cur;
    $scope.getHistory(search);
  });
  
  // $scope.getHistory = function(wallet_id, page, newFilter){
  //     var newFilter = newFilter || 0;

  //     if (newFilter){
  //         $scope.history.term = angular.element('#block-history input[name=term]').val();
  //         $scope.history.date = angular.element('#block-history input[name=date]').val();
  //     }

  //     var data = {token: $scope.user.token, id:wallet_id, page:page};
  //     if (typeof ($scope.history.term) != 'undefined') data.term = $scope.history.term;
  //     if (typeof ($scope.history.date) != 'undefined') data.date = $scope.history.date;

  //     angular.element('#block-history .m-table-wrapper').addClass('loading');
  //     $http.post('/wallet/getHistory', data).success(function(data) {
  //         if(data.error == 'no') {
  //             angular.element('#table-history tbody').html($compile(data.content)($scope));
  //             angular.element('#block-history .m-table-wrapper').removeClass('loading');
  //         }
  //     }).error(function(error){
  //         angular.element('#block-history .m-table-wrapper').removeClass('loading');
  //         console.log(error);
  //     });
  // }
  
  //акции
  $scope.getSpecialActions = function(wallet_id, page, status, search){
      if ('undefined' == typeof (search)) search = $scope.actions.search || '';
      else $scope.actions.search = search;
      
      if ('undefined' == typeof (status))
      {
          if (angular.element('a#actions-actual').hasClass('active'))
              var status = 1;
          else
              var status = 0;
      }

      var data = {token: $scope.user.token, id:wallet_id, page:page, status:status, search:search};
      $http.post('/wallet/getActions', data).success(function(data) {
          if(data.error == 'no') {
              angular.element('#actions-table tbody').html($compile(data.content)($scope));
          }
      });
  }

  $scope.getAllActions = function(status, page, search){
      if ('undefined' == typeof (search)) var search = $scope.allActions.search || '';
      else $scope.allActions.search = search;

      var data = {token: $scope.user.token, page:page, status:status, search:search};
      $http.post('/wallet/getAllActions', data).success(function(data) {
        if(data.error == 'no') {
            angular.element('#all-actions-table tbody').html($compile(data.content)($scope));
        }
      })
  }
  
  // Атрибут включения смс информирования на всех кошельках
  $scope.setSmsAllWallets = function(sms){
    if (sms.all_wallets == 1) sms.all_wallets = 0;
    else sms.all_wallets = 1;
  };

  //Сохранение телефона sms информирования
  $scope.savePhone = function(sms, valid){
    if (!valid) return false;
      sms.phone = $scope.sms.prefix + $scope.sms.phone;

      var settings_phone = angular.element('#j-settingsForm input[name=phone]');
      var settings_condition = angular.element('#j-settingsForm div.condition02 a');

      $http.post('/wallet/savePhone', sms).success(function(data) {
        if(data.error == 'no') {
          $scope.sms.savedPhone = sms.phone;
          $scope.sms.phone = '';
          contentService.setModal(data.content, 'none');
        }
        else{
            settings_phone.addClass('error');
        }
      });
  }

  //Отключение sms информирования
  $scope.cancelSms = function(sms){
      sms.phone = $scope.sms.savedPhone;
      $http.post('/wallet/removePhone', sms).success(function(data) {
        if(data.error == 'no') {
          $scope.sms.savedPhone = false;
          contentService.setModal(data.content, 'none');
        }
      });
  }
  
  //отмена sms информирования для кошелька
  // $scope.cancelSms = function(wallet_id)
  // {
  //     var data = {token: $scope.user.token, id:wallet_id};
  //     $http.post('/wallet/cancelSms', data).success(function(data) {
  //     });
  // }
  
  // //sms для всех кошельков
  // $scope.SmsAllWallets = function(wallet_id)
  // {
  //     var settings_phone = angular.element('#j-settingsForm input[name=phone]');
  //     if ($scope.sms.savedPhone.length)
  //     {
  //         var data = {token: $scope.user.token, id:wallet_id, phone:$scope.sms.savedPhone};
  //         if (!angular.element('#UserSmsInfo').hasClass('active'))
  //             data.enable = true;
  //         else
  //             data.enable = false;

  //         $http.post('/wallet/SmsAllWallets', data).success(function(data) {
  //             if ('yes' == data.error)
  //               settings_phone.addClass('error');
  //         });
  //     }
  // }

  $scope.removePhoneError = function()
  {
      angular.element('#j-settingsForm input[name=phone]').removeClass('error');
  }
  
  var popup;
  var socTimer;
  var resultModal = angular.element('.m-result');
  
  //проверка like для акции
  $scope.checkLike = function(id_action)
  {
      var data = {token: $scope.user.token, id:id_action};
      $http.post('/wallet/checkLike', data).success(function(data) {
          if ('no' == data.error)
          {
              if (!data.isSocLogged)
              {
                  var options = $.extend({
                    id: '',
                    popup: {
                      width: 450,
                      height: 380
                    }
                  }, options);

                  var redirect_uri, url = redirect_uri = 'http://' + window.location.hostname + '/service/SocLogin?service=' + data.service;

                  url += url.indexOf('?') >= 0 ? '&' : '?';
                  if (url.indexOf('redirect_uri=') === -1)
                    url += 'redirect_uri=' + encodeURIComponent(redirect_uri) + '&';
                  url += 'js';

                  var centerWidth = (window.screen.width - options.popup.width) / 2,
                    centerHeight = (window.screen.height - options.popup.height) / 2;

                  popup = window.open(url, "yii_eauth_popup", "width=" + options.popup.width + ",height=" + options.popup.height + ",left=" + centerWidth + ",top=" + centerHeight + ",resizable=yes,scrollbars=no,toolbar=no,menubar=no,location=no,directories=no,status=yes");
                  popup.focus();
                  
                  $scope.checkingAction = {id:id_action};
                  socTimer = $timeout($scope.loginTimer, 1000);
              }
              else
              {
                  if ('undefined' != typeof (data.message_error) && 'undefined' != typeof (data.message))
                  {
                      if ('yes' == data.message_error)
                          contentService.setModal(data.message, 'error'); 
                      else
                          contentService.setModal(data.message, 'none'); 
                  }
              }
          }
      });
  }

  $scope.loginTimer = function()
  {
      if (!popup.closed) {
          var data = {token: $scope.user.token, id:$scope.checkingAction.id};
          $http.post('/wallet/checkLike', data).success(function(data) {
              if ('undefined' != typeof (data.isSocLogged))
              {
                  if (data.isSocLogged)
                  {
                      popup.close();
                      $scope.bindNet = {};
                      
                      if ('undefined' != typeof (data.message_error) && 'undefined' != typeof (data.message))
                      {
                          if ('yes' == data.message_error) 
                              contentService.setModal(data.message, 'error'); 
                          else
                              contentService.setModal(data.message, 'none'); 
                      }
                  }
                  else
                  {
                      socTimer = $timeout($scope.loginTimer, 1000);
                  }
              }
          });
      }
  }; 
});