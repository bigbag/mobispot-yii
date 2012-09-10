<div id="main-container">
    <div class="grayAllBlock rad6 shadow">
        <div class="grayHead radTop6"><b><?php echo Yii::t('mobile', 'Отправка на E-mail');?></b></div>
        <div class="infoViz proc100 clr txtFLeft">
            <p><b><?php echo Yii::t('mobile', 'Введите адрес электронной почты и Вам отправят файлы');?></b></p>
        </div>

        <form>
            <input type="text" class="txt-100p rad6" value="" placeholder=""/>

            <div class="txtFLeft"><input type="checkbox" class="niceCheck"/><?php echo Yii::t('mobile', 'Я согласен с правилами пользования сервиса');?>
            </div>
            <input type="submit" class="btn-round fright rad12 shadow" value="<?php echo Yii::t('mobile', 'Отправить');?>"/>

        </form>
    </div>
</div>