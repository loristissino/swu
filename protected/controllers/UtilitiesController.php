<?php

class UtilitiesController extends Controller
{
  /**
   * @var string the default layout for the views.
   */
  public $layout='//layouts/column2';

  /**
   * @return array action filters
   */
  public function filters()
  {
    return array(
      'accessControl', // perform access control for CRUD operations
      'postOnly + delete', // we only allow deletion via POST request
    );
  }

  /**
   * Specifies the access control rules.
   * This method is used by the 'accessControl' filter.
   * @return array access control rules
   */
  public function accessRules()
  {
    return array(
      array('allow',
        'actions'=>array('none'),
        'users'=>array('*'),
      ),
      array('allow', // allow admin user to perform the selected actions
        'actions'=>array('password'),
        'users'=>array_keys(Helpers::getYiiParam('admins')),
      ),
      array('deny',  // deny all users
        'users'=>array('*'),
      ),
    );
  }
  
  public function actionIndex()
  {
    $this->render('index');
  }

public function actionPassword()
{
    $model=new PasswordForm;

    if(isset($_POST['PasswordForm']))
    {
        $model->attributes=$_POST['PasswordForm'];
        if($model->validate())
        {
          $password = UserIdentity::createPassword($model->password, $model->cost);
          return $this->render('password_generated', array('password'=>$password));
        }
    }
    $model->cost = 8;
    $this->render('password',array('model'=>$model));
}

}
