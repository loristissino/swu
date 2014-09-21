<?php

class SiteController extends Controller
{

   /**
   * Declares class-based actions.
   */
  public function actions()
  {
    return array(
      // captcha action renders the CAPTCHA image displayed on the contact page
      'captcha'=>array(
        'class'=>'CCaptchaAction',
        'backColor'=>0xFFFFFF,
      ),
      // page action renders "static" pages stored under 'protected/views/site/pages'
      // They can be accessed via: index.php?r=site/page&view=FileName
      'page'=>array(
        'class'=>'CViewAction',
      ),
    );
  }


  /**
   * @return array action filters
   */
  public function filters()
  {
    return array(
      'accessControl', // perform access control
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
      array('allow',  // allow all users to perform 'index' and 'view' actions
        'actions'=>array('index', 'error', 'contact', 'login', 'logout', 'captcha', 'page', 'image'),
        'users'=>array('*'),
      ),
      /*
      array('allow', // allow authenticated user to perform 'create' and 'update' actions
        'actions'=>array(),
        'users'=>array('@'),
      ),
      */
      array('allow', // allow admin user to perform 'admin' and 'delete' actions
        'actions'=>array('admin','testmail','backup','files',),
        'users'=>array_keys(Helpers::getYiiParam('admins')),
      ),
      array('deny',  // deny all users
        'users'=>array('*'),
      ),
    );
  }



  /**
   * This is the default 'index' action that is invoked
   * when an action is not explicitly requested by users.
   */
  public function actionIndex()
  {
    // renders the view file 'protected/views/site/index.php'
    // using the default layout 'protected/views/layouts/main.php'
    $this->render('index');
  }
  
  public function actionThrow()
  {
    throw new CHttpException(500,'Server error.');
  }

  /**
   * This is the action to handle external exceptions.
   */
  public function actionError()
  {
    if($error=Yii::app()->errorHandler->error)
    {
      if(Yii::app()->request->isAjaxRequest)
        echo $error['message'];
      else
        $this->render('error', $error);
    }
  }

  /**
   * Displays the contact page
   */
  public function actionContact($name='', $subject='', $body='')
  {
    $model=new ContactForm;
    $model->name=$name;
    $model->subject=$subject;
    $model->body=$body;
    if(isset($_POST['ContactForm']))
    {
      $model->attributes=$_POST['ContactForm'];
      if($model->validate())
      {
        MailTemplate::model()->mailFromTemplate('contact_form', Helpers::getYiiParam('adminEmail'), array(
          'subject'=>$model->subject,
          'name'=>$model->name,
          'email'=>$model->email,
          'body'=>$model->body,
        ),array(
          'replyto'=>$model->email,
        ));
        
        Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
        $this->refresh();
      }
    }
    $this->render('contact',array('model'=>$model));
  }

  public function actionImage($name, $k)
  {
    if(!Message::acknowledge($k, false))
    {
      throw new CHttpException(404,'The requested file does not exist.');
    }
      
    $file = 'images/' . $name . '.png';
    return $this->serveFile('image/png', $file);
  }

  /**
   * Displays the login page
   */
  public function actionLogin()
  {
    $model=new LoginForm;

    // if it is ajax validation request
    if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
    {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }

    // collect user input data
    if(isset($_POST['LoginForm']))
    {
      $model->attributes=$_POST['LoginForm'];
      // validate user input and redirect to the previous page if valid
      if($model->validate() && $model->login())
      {
        Yii::app()->user->setReturnUrl(array('site/admin'));
        $this->redirect(Yii::app()->user->returnUrl);
      }
    }
    // display the login form
    $this->render('login',array('model'=>$model));
  }

  /**
   * Logs out the current user and redirect to homepage.
   */
  public function actionLogout()
  {
    Yii::app()->user->logout();
    $this->redirect(Yii::app()->homeUrl);
  }
  
  
  public function actionAdmin()
  {
    if(Yii::app()->user->isGuest)
      throw new CHttpException(401,'You are not authorized to access this page.');
    
    $this->render('admin');
  }
  
  public function actionTestmail()
  {
      if(Yii::app()->user->isGuest)
        throw new CHttpException(401,'You are not authorized to access this page.');
        
      Mailer::mail(array('loris.tissino@gmail.com'=>'Loris Tissino'), 'test msg', 'body sample');
      $this->redirect(Yii::app()->homeUrl);

  }

  public function actionBackup()
    {
      if(Yii::app()->user->isGuest)
        throw new CHttpException(401,'You are not authorized to access this page.');

      /* kept for reference...
      if(Yii::app()->request->isPostRequest)
      {
        set_time_limit(120);
        $backupFilePath = Yii::app()->basePath . DIRECTORY_SEPARATOR . Helpers::getYiiParam('uploadDirectory');
        $backupFileName = 'dbbackup_'.date('ymd_His').'.sql';
        
        Helpers::backupDb($backupFilePath . DIRECTORY_SEPARATOR .'dbbackup_'.date('ymd_His').'.sql');
                  
        return $this->render('backup_finished',array(
           'file'=>$backupFileName,
        ));
      }
      */
      
      set_time_limit(120);
      $sql = Helpers::backupDb(null);
      
      $this->render('backup', array('sql'=>$sql));
    }

  public function actionFiles()
    {
      if(Yii::app()->user->isGuest)
        throw new CHttpException(401,'You are not authorized to access this page.');
      
      $this->render('files', array('files'=>File::model()->findAll()));
    }


  

}
