<div id="recPassForm" class="slide-box"  ng-controller="UserCtrl">
  <div class="row">
    <div class="seven columns centered">
      <h3><?php echo Yii::t('user', 'Forgot password?')?></h3>
    </div>
    <a href="javascript:;" class="slide-box-close">&#xe00b;</a>
  </div>
  <div class="row">
    <div class="five columns centered">
      <h5 class="result color"></h5>
      <form id="recovery-pass" name="recoveryForm">
      <input
        name='email'
        type="email"
        ng-model="recovery.email"
        placeholder="<?php echo Yii::t('user', 'E-mail')?>"
        ui-keypress="{enter: 'login(recovery, recoveryForm.$valid)'}"
        autocomplete="off"
        maxlength="300"
        required >
        <div class="form-control">
          <a class="spot-button  button-disable" href="javascript:;" ng-click="recovery(recovery, recoveryForm.$valid)">
            <?php echo Yii::t('user', 'Send')?>
          </a>
        </div>
      </form>
    </div>
  </div>
</div>