<?php

class StudentController extends Controller
{
  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout='//layouts/column2';
  
  public $assignment=null;

  /**
   * @return array action filters
   */
  public function filters()
  {
    return array(
      'accessControl', // perform access control for CRUD operations
      'postOnly + delete', // we only allow deletion via POST request,
      'postOnly + message', // we only allow setting ids to prepare messages via POST request,
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
        'actions'=>array('sendcodes'),
        'users'=>array('*'),
      ),
      /*
      array('allow', // allow authenticated user to perform 'create' and 'update' actions
        'actions'=>array(),
        'users'=>array('@'),
      ),
      */
      array('allow', // allow admin user to perform 'admin' and 'delete' actions
        'actions'=>array('view','create','admin','delete','update','emailaddresses','message','email','report','import'),
        'users'=>array_keys(Helpers::getYiiParam('admins')),
      ),
      array('deny',  // deny all users
        'users'=>array('*'),
      ),
    );
  }

  /**
   * Displays a particular model.
   * @param integer $id the ID of the model to be displayed
   */
  public function actionView($id)
  {
    $model=$this->loadModel($id);
    $this->render('view',array(
      'model'=>$model,
      'exercises'=>$model->exercises,
    ));
  }

  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate()
  {
    $model=new Student;

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(isset($_POST['Student']))
    {
      $model->attributes=$_POST['Student'];
      if($model->save())
        $this->redirect(array('view','id'=>$model->id));
    }

    $this->render('create',array(
      'model'=>$model,
    ));
  }

  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionUpdate($id)
  {
    $model=$this->loadModel($id);

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(isset($_POST['Student']))
    {
      $model->attributes=$_POST['Student'];
      if($model->save())
        $this->redirect(array('view','id'=>$model->id));
    }

    $this->render('update',array(
      'model'=>$model,
    ));
  }

  public function actionSendcodes($email='')
  {
    $model = new SendcodesForm;
    $model->email = $email;
    
    if(isset($_POST['SendcodesForm']))
    {
      $model->attributes=$_POST['SendcodesForm'];
      if($model->validate())
      {
        if($model->sendcodes($this))
        {
          $this->setAllFlashes(array(
            'success'=>array(
              Yii::t('swu', 'The codes were sent to the address you specified.') . ' ' .  Yii::t('swu', 'Please check your email.')
            ),
          ));
          $this->redirect(array('file/upload'));
        }
        else
        {
          $this->setAllFlashes(array(
            'error'=>array(
              Yii::t('swu', 'Sorry, no codes were found for the user with the specified email.'),
            )
          ));
          $this->redirect(array('student/sendcodes', 'email'=>$model->email));
        }
      }
    }

    $this->render('sendcodes',array(
      'model'=>$model,
      'email'=>$email,
    ));
  }

  public function actionImport()
  {
    $form = new IEStudentsForm;
    
    if(Yii::app()->getRequest()->isPostRequest)
    {
      $form->attributes=$_POST['IEStudentsForm'];
      if($form->validate())
      {
        $result = Student::model()->importStudentsFrom($form);
        $flashes = array('success'=>array(), 'error'=>array());
        if($result['imported'])
        {
          $flashes['success'][]=Yii::t('swu', 'One student correctly imported.|{n} students have been correctly imported.', $result['imported']);
        }
        if($result['skipped'])
        {
          $flashes['error'][]=Yii::t('swu', 'One line has been skipped.|{n} lines have been skipped.', $result['skipped']);
        }
        $this->setAllFlashes($flashes);
        $this->redirect(array('student/admin'));
      }
    }
      
    $this->render('import',array(
      'model'=>$form,
    ));
  }

  public function actionEmailaddresses()
  {
    if(Yii::app()->getRequest()->isPostRequest)
    {
      Yii::app()->getUser()->setState('ids', $_POST['id']);
      $this->redirect(array('student/emailaddresses'));
    }
    $ids=Yii::app()->getUser()->getState('ids', array());
    $this->render('emailaddresses',array(
      'students_with_email'=>Student::model()->withEmail()->sortByLastname()->findAllByPk($ids),
      'students_without_email'=>Student::model()->withoutEmail()->sortByLastname()->findAllByPk($ids),
    ));
  }

  public function actionReport($subtemplate='exercises')
  {
    if(Yii::app()->getRequest()->isPostRequest)
    {
      Yii::app()->getUser()->setState('ids', $_POST['id']);
      $this->redirect(array('student/report', 'subtemplate'=>'synthetic'));
    }
    $ids=Yii::app()->getUser()->getState('ids', array());
    $students=Student::model()->sortByLastname()->findAllByPk($ids);
    $this->render('report', array(
      'students'=>$students,
      'subtemplate'=>'_'.$subtemplate,
    ));
  }

  public function actionEmail($subject='', $body='')
  {
    $ids=Yii::app()->getUser()->getState('ids', array());
    $students = Student::model()->findAllByPK($ids); 
    $model=new MessageForm;
    $model->subject=$subject;
    $model->body=$body;
    if(isset($_POST['MessageForm']))
    {
      $model->attributes=$_POST['MessageForm'];
      if($model->validate())
      {
        $result = $model->prepareMessages($students);
        
        $flashes = array('success'=>array(), 'error'=>array());
        if($result['prepared'])
        {
          $flashes['success'][]=Yii::t('swu', 'One message has been correctly prepared.|{n} messages have been correctly prepared.', $result['prepared']);
        }
        if($result['failed'])
        {
          $flashes['error'][]=Yii::t('swu', 'One message could not be prepared.|{n} messages could not be prepared.', $result['failed']);
        }
        $this->setAllFlashes($flashes);
        $this->refresh();
      }
    }
    $this->render('message',array('model'=>$model, 'students'=>$students));
  }


  public function actionMessage()
  {
    Yii::app()->getUser()->setState('ids', $_POST['id']);
    $this->redirect(array('student/email'));
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id)
  {
    try
    {
      $this->loadModel($id)->delete();
    }
    catch (Exception $e)
    {
      Yii::app()->getUser()->setFlash('flash-error', 'The user could not be deleted.');
      $this->redirect(array('view', 'id'=>$id));
    }
    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    if(!isset($_GET['ajax']))
      $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
  }

  /**
   * Lists all models.
   */
  public function actionIndex()
  {
    $dataProvider=new CActiveDataProvider('Student');
    $this->render('index',array(
      'dataProvider'=>$dataProvider,
    ));
  }

  /**
   * Manages all models.
   */
  public function actionAdmin($assignment=null)
  {
    $model=new Student('search');
    $model->unsetAttributes();  // clear any default values
    
    if($assignment)
    {
      $dataProvider = new CArrayDataProvider($this->loadStudentsFromAssignment($assignment), array(
        'keyField'=>'id',
        'pagination'=>array(
          'pageSize'=>1000,
        ),
        )
      );
    }
    else
    {
      if(isset($_GET['Student']))
        $model->attributes=$_GET['Student'];
      $dataProvider = $model->search();
    }

    $this->render('admin',array(
      'model'=>$model,
      'dataProvider'=>$dataProvider,
    ));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Student the loaded model
   * @throws CHttpException
   */
  public function loadModel($id)
  {
    $model=Student::model()->findByPk($id);
    if($model===null)
      throw new CHttpException(404,'The requested page does not exist.');
    return $model;
  }

  public function loadStudentsFromAssignment($id)
  {
    $this->assignment=Assignment::model()->findByPk($id);
    if($this->assignment===null)
      throw new CHttpException(404,'The requested page does not exist.');
    
    return $this->assignment->getStudents();
  }

  /**
   * Performs the AJAX validation.
   * @param Student $model the model to be validated
   */
  protected function performAjaxValidation($model)
  {
    if(isset($_POST['ajax']) && $_POST['ajax']==='student-form')
    {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
}
