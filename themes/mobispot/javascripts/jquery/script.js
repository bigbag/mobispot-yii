var $body = $('body');
var $popup = $('.popup');
var $element = new Object();

var toggleOpen = function (element) {
  $(element).toggleClass('open');
};

var addActive = function () {
  $(this).addClass('active').siblings().removeClass('active');
};
var toggleActive = function () {
  $(this).toggleClass('active');
};

var showPopup = function(){
  $element.showPopupButton = $(this);
  $popup.removeClass('hide').animate({opacity: 1}, 50, function(){
    $body.addClass('overflow-h');
  });

};
var hidePopup = function(){
  $element.showPopupButton.removeClass('active');
  $popup.animate({opacity: 0}, 50, function(){
    $(this).addClass('hide');
    $body.removeClass('overflow-h');
  });

};

$(window).load(function() {
  $(document).on('click','.add-active > *', addActive);
  $(document).on('click','.toggle-active > *', toggleActive);
  $(document).on('click','.store-items__close', function(){
    $(this).parents('tr').remove();
  });

  $('label').on('click', function(){
    $(this).prev().focus();
  });
  $('.settings-button').on('click', showPopup);
  $('.button', '.popup').on('click', hidePopup);

  $(document).keydown(function(e){
    if(e.which == 27){
      $popup.hasClass('hide')||hidePopup();
    }
  });

   $('.spot-list').on('input propertychange','textarea', function() {
    if($(this).val()){
      $(this).addClass('put');
    }else{
      $(this).removeClass('put');
    }

  });
});

