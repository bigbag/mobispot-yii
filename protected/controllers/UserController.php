<?php

class UserController extends MController
{

    public $defaultAction = 'profile';

    // Вывод профиля
    public function actionProfile()
    {
        if (!Yii::app()->user->id)
        {
            $this->setAccess();
        }
        else
        {
            $user = User::model()->findByPk(Yii::app()->user->id);
            $profile = UserProfile::model()->findByPk(Yii::app()->user->id);

            if (isset($_POST['UserProfile']))
            {
                $profile->attributes = $_POST['UserProfile'];

                if ($profile->validate())
                {
                    $profile->save();
                    $this->refresh();
                }
            }

            $this->render('profile', array(
                'profile' => $profile,
                'user' => $user,
            ));
        }
    }

    // Страница управления персональными спотами
    public function actionPersonal()
    {
        $this->layout = '//layouts/spots';

        $defDiscodes = '';
        $defKey = '';
        $message = '';

        if (!Yii::app()->user->id)
        {
            $this->setAccess();
        }
        else
        {
            $user_id = Yii::app()->user->id;
            $user = User::model()->findByPk($user_id);

            if ($user->status == User::STATUS_NOACTIVE)
            {
                $this->redirect('/');
            }

            if (isset(Yii::app()->session['bind_discodes']) && isset(Yii::app()->session['bind_key']))
            {
                $defDiscodes = Yii::app()->session['bind_discodes'];
                $defKey = Yii::app()->session['bind_key'];
                $spot = Spot::getSpot(array('discodes_id' => Yii::app()->session['bind_discodes']));
                if ($spot)
                {
                    $spotContent = SpotContent::getSpotContent($spot);

                    if ($spotContent)
                    {
                        $socInfo = new SocInfo;
                        $key = Yii::app()->session['bind_key'];
                        $netName = $socInfo->detectNetByLink($spotContent->content['data'][$key]);
                        $linkCorrect = $socInfo->isLinkCorrect($spotContent->content['data'][$key], $defDiscodes, $defKey);
                        if (isset($linkCorrect) && ($linkCorrect != 'ok'))
                            $message = $linkCorrect;
                        if (($netName != 'no') && isset(Yii::app()->session[$netName]) && (Yii::app()->session[$netName] == 'auth') && isset($linkCorrect) && ($linkCorrect == 'ok'))
                        {
                            $content = $spotContent->content;
                            $content['keys'][$key] = 'socnet';
                            $spotContent->content = $content;
                            $spotContent->save();
                        }
                    }
                }
                unset(Yii::app()->session['bind_discodes']);
                unset(Yii::app()->session['bind_key']);
            }

            $dataProvider = new CActiveDataProvider(
                    Spot::model()->personal()->used()->selectUser($user_id), array(
                'pagination' => array(
                    'pageSize' => 100,
                ),
                'sort' => array('defaultOrder' => 'registered_date desc'),
            ));

            $this->render('personal', array(
                'dataProvider' => $dataProvider,
                'spot_type_all' => SpotType::getSpotTypeArray(),
                'defDiscodes' => $defDiscodes,
                'defKey' => $defKey,
                'message' => $message,
            ));
        }
    }

    public function actionUploadFile()
    {
        if (!empty($_FILES))
        {
            $spot_id = $_POST['spot_id'];

            $tempFile = $_FILES['Filedata']['tmp_name'];
            $targetPath = Yii::getPathOfAlias('webroot.uploads.spot.') . '/';
            $targetFileName = $spot_id . '_' . time() . '_' . $_FILES['Filedata']['name'];
            $targetFile = rtrim($targetPath, '/') . '/' . $targetFileName;

            move_uploaded_file($tempFile, $targetFile);

            echo json_encode(array('file' => $targetFileName));
        }
    }

    public function actionUploadCouponLogo()
    {
        if (!empty($_FILES))
        {

            $spot_id = $_POST['spot_id'];
            $tempFile = $_FILES['Filedata']['tmp_name'];

            $targetPath = Yii::getPathOfAlias('webroot.uploads.spot.') . '/';
            $targetFileName = $spot_id . '_' . time() . '.png';
            $targetFile = rtrim($targetPath, '/') . '/' . $targetFileName;

            $image = new CImageHandler();
            $image->load($tempFile);
            if ($image->thumb(70, 70, true))
            {
                $image->save($targetFile, 3);
                echo json_encode(array('file' => $targetFileName));
            }
            else
                echo json_encode(array('error' => Yii::t('images', 'Загруженный файл не является изображением.')));
        }
    }

    public function actionUpload()
    {
        if (!empty($_FILES))
        {
            $action = $_POST['action'];
            $tempFile = $_FILES['Filedata']['tmp_name'];

            $fileParts = pathinfo($_FILES['Filedata']['name']);

            $fileName = $action . '_' . md5(time() . $fileParts['basename']);
            $targetFileName = $fileName . '.jpg';

            $targetPath = Yii::getPathOfAlias('webroot.uploads.spot.') . '/';

            $image = new CImageHandler();
            $image->load($tempFile);
            if ($image->thumb(400, 600, true))
            {
                $image->save($targetPath . $fileName . '.jpg');
                echo json_encode(array('file' => $targetFileName));
            }
            else
                echo json_encode(array('error' => Yii::t('images', 'Загруженный файл не является изображением.')));
        }
    }

    public function actionBindSocLogin()
    {
        $service = Yii::app()->request->getQuery('service');

        if (isset($service))
        {
            if (!Yii::app()->user->id)
            {
                $this->setAccess();
            }
            else
            {
                if (($service == 'instagram') && isset($_GET['tech']) && ($_GET['tech'] == Yii::app()->eauth->services['instagram']['client_id']))
                {
                    Yii::app()->session['instagram_tech'] = $_GET['tech'];
                }
                $authIdentity = Yii::app()->eauth->getIdentity($service);
                $authIdentity->redirectUrl = Yii::app()->user->returnUrl;
                $authIdentity->cancelUrl = $this->createAbsoluteUrl('user/personal');

                if ($authIdentity->authenticate())
                {
                    $identity = new ServiceUserIdentity($authIdentity);

                    if ($identity->authenticate())
                    {
                        Yii::app()->session[$service] = 'auth';
                        Yii::app()->session[$service . '_id'] = $identity->getId();
                        $authIdentity->redirect(array('user/personal'));
                    }
                    else
                    {
                        $authIdentity->cancel();
                    }
                }
            }
        }
        else
        {
            $this->setNotFound();
        }
    }

}