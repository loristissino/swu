<?php

class ExerciseController extends Controller
{
  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout='//layouts/column1';

  /**
   * @return array action filters
   */
  public function filters()
  {
    return array(
      'accessControl', // perform access control for CRUD operations
      'postOnly + delete', // we only allow deletion via POST request
//      'postOnly + message',
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
        'actions'=>array('info'),
        'users'=>array('*'),
      ),
      array('allow', // allow authenticated user to perform 'create' and 'update' actions
        'actions'=>array(),
        'users'=>array('@'),
      ),
      array('allow', // allow admin user to perform 'admin' and 'delete' actions
        'actions'=>array('create','update','index','view','admin','delete',),
        'users'=>array_keys(Helpers::getYiiParam('admins')),
      ),
      array('deny',  // deny all users
        'users'=>array('*'),
      ),
    );
  }

  /**
   * Displays information about an exercise (code, assignment, etc.).
   * @param integer $id the ID of the model to be displayed
   */
  public function actionInfo($k='', $code='', $hash='', $defaultcode='')
  {
    $exerciseInfoForm = new ExerciseInfoForm;
    $exerciseInfoForm->code = $defaultcode;

    $model = null;
    $files = null;

    if(isset($_POST['ExerciseInfoForm']))
    {
      $exerciseInfoForm->attributes=$_POST['ExerciseInfoForm'];
      
      if($exerciseInfoForm->validate())
      {
        if(Yii::app()->user->isGuest)
        {
          // if a students looks up an exercise using the code, it means that he/she has received the code...
          $exerciseInfoForm->exercise->increaseStatus(Exercise::STATUS_ACKNOWLEDGED);
        }
        $this->redirect(array('exercise/info','code'=>$exerciseInfoForm->exercise->code, 'hash'=>Helpers::hash($exerciseInfoForm->exercise->id, 12)));
      }
    }
    
    if($code)
    {
      $model=$this->loadModelByCode($code);
      if($model && Helpers::hash($model->id, 12)!=$hash)
      {
        $model = null;
      }
      if($model===null)
      {
        Yii::app()->user->setFlash('flash-error', 'Our bad. We couldn\'t find an exercise with that code.');
        $this->redirect(array('exercise/info', 'defaultcode'=>$code));
      }
    }
    elseif($k)
    {
      $model=$this->loadModelByInfokey($k);
    }
    
    $this->render('info',array(
      'model'=>$model,
      'files'=>$this->getFilesOfExercise($model),
      'code'=>$code,
      'exerciseInfoForm'=>$exerciseInfoForm,
    ));
  }

  /**
   * Displays a particular model.
   * @param integer $id the ID of the model to be displayed
   */
  public function actionView($id)
  {
    $model = $this->loadModel($id);
    
    $this->render('view',array(
      'model'=>$model,
      'files'=>$this->getFilesOfExercise($model),
    ));
  }

  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate()
  {
    $model=new Exercise;

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(isset($_POST['Exercise']))
    {
      $model->attributes=$_POST['Exercise'];
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
  public function actionUpdate($id, $version=null, $file=null)
  {
    $model=$this->loadModel($id);
    $file=File::model()->findByPk($file);

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(isset($_POST['Exercise']))
    {
      $model->attributes=$_POST['Exercise'];
      if($model->save())
      {
        if($file=File::model()->findByPk($model->file))
        {
          $file->markAsChecked();
        }
        
        if($model->generate_message)
        {
          if($model->generateMessage())
          {
            Yii::app()->getUser()->setFlash('flash-success', 'Message generated.');
          }
          else
          {
            Yii::app()->getUser()->setFlash('flash-error', 'The message was not generated.');
          }
        }
        
        $this->redirect(array('assignment/view','id'=>$model->assignment_id, 'format'=>'marks'));
      }
    }

    if($model->comment)
    {
      $model->comment .= "\n\n";
    }
      
    if($version)
    {
      $model->comment .= sprintf("Version %d (%s):\n", $version, $file->uploaded_at);
      $model->file = $file->id;
    }
      
    $model->comment .= $model->assignment->checklist . "\n";

    $this->render('update',array(
      'model'=>$model,
    ));
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id)
  {
    $this->loadModel($id)->delete();

    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    if(!isset($_GET['ajax']))
      $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
  }

  /**
   * Lists all models.
   */
  public function actionIndex()
  {
    $dataProvider=new CActiveDataProvider('Exercise');
    $this->render('index',array(
      'dataProvider'=>$dataProvider,
    ));
  }

  /**
   * Manages all models.
   */
  public function actionAdmin()
  {
    $model=new Exercise('search');
    $model->unsetAttributes();  // clear any default values
    if(isset($_GET['Exercise']))
      $model->attributes=$_GET['Exercise'];

    $this->render('admin',array(
      'model'=>$model,
    ));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Exercise the loaded model
   * @throws CHttpException
   */
  public function loadModel($id)
  {
    $model=Exercise::model()->findByPk($id);
    if($model===null)
      throw new CHttpException(404,'The requested page does not exist.');
    return $model;
  }

  public function loadModelByInfokey($k)
  {
    $model=Exercise::model()->findByAckKey($k);
    if($model===null)
      throw new CHttpException(404,'The requested page does not exist.');
    
    $model->increaseStatus(Exercise::STATUS_ACKNOWLEDGED);
    return $model;
  }

  public function loadModelByCode($code)
  {
    return $model=Exercise::model()->findByAttributes(array('code'=>$code));
  }


  /**
   * Performs the AJAX validation.
   * @param Exercise $model the model to be validated
   */
  protected function performAjaxValidation($model)
  {
    if(isset($_POST['ajax']) && $_POST['ajax']==='exercise-form')
    {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
  
  protected function getFilesOfExercise($model)
  {
    if($model)
    {
      $file = new File('search');
      $file->exercise_id = $model->id;
      return $file->search();
    }
  }
  
}
