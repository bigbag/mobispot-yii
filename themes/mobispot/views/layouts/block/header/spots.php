<?php $info = $this->userInfo(); ?>
<header class="header-page">
    <div class="hat-bar content">
        <h1 class="logo">
            <a href="/">
                <img itemprop="logo" alt="Mobispot" src="/themes/mobispot/img/logo_x2.png">
            </a>
        </h1>
        <ul class="right">
            <li>
                <a class="show" href="/service/logout/">
                <?php echo Yii::t('menu', 'Logout') ?>
                <?php if ($info):?> (<?php echo $info['name']?>)<?php endif;?>

                </a>
            </li>
        </ul>
    </div>
    <div id="message" class="show-block b-message" ng-class="{active: (modal=='message')}">
        <p>{{result.message}}
        </p>
    </div>
</header>