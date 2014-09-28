<?php

class AssignmentController extends Controller
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
        'actions'=>array('list'),
        'users'=>array('*'),
      ),
      /*
      array('allow', 
        'actions'=>array(),
        'users'=>array('@'),
      ),
      */
      array('allow', // allow admin user to perform 'admin' and 'delete' actions
        'actions'=>array('index','view','create','update','admin','delete','students','messages','generateinvitations', 'codes', 'files'),
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
      'exercises'=>$model->exercisesWithStudents,
    ));
  }
  
  public function actionCodes($id)
  {
    $model=$this->loadModel($id);
    
    $this->render('codes',array(
      'model'=>$model,
      'exercises'=>$model->exercisesWithStudents,
    ));
  }
  
  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate($id=null)
  {
    $model=new Assignment;

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(isset($_POST['Assignment']))
    {
      $model->attributes=$_POST['Assignment'];
      if($model->save())
        $this->redirect(array('view','id'=>$model->id));
    }
    
    if($id)
    {
      $cloned = $this->loadModel($id);
      Helpers::object2object($cloned, $model, array('subject', 'title', 'description', 'checklist', 'url', 'duedate', 'grace', 'language', 'status', 'notification', 'shown_since'));
    }
    else
    {
      $model->grace = Helpers::getYiiParam('defaultGrace');
      $model->duedate = date('Y-m-d') . ' 23:59:00';
      $model->shown_since = date('Y-m-d H:i:s');
      $model->language = Yii::app()->language;
      $model->status = Helpers::getYiiParam('defaultAssignmentStatus');
      $cloned = null;
    }
    
    $this->render('create',array(
      'model'=>$model,
      'cloned'=>$cloned,
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

    if(isset($_POST['Assignment']))
    {
      $model->storeAttributes();
      $model->attributes=$_POST['Assignment'];
      if($model->save())
        $this->redirect(array('view','id'=>$model->id));
    }

    $this->render('update',array(
      'model'=>$model,
    ));
  }
  
  /**
   * Sends messages generated from the current assignment.
   * @param integer $id the ID of the model to be updated
   */
  public function actionMessages($id)
  {
    $model=$this->loadModel($id);

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(Yii::app()->request->isPostRequest)
    {
      if($count = $model->generateMessages())
      {
        Yii::app()->getUser()->setFlash('flash-success', 'Messages generated: ' . $count . '.');
        $this->redirect(array('view','id'=>$model->id));
      }
      else
      {
        Yii::app()->getUser()->setFlash('flash-error', 'No message generated.');
        $this->redirect(array('view','id'=>$model->id));
      }
    }

    $this->render('messages',array(
      'model'=>$model,
    ));
  }
  
  /**
   * Sends messages generated from the current assignment.
   * @param integer $id the ID of the model to be updated
   */
  public function actionGenerateinvitations($id)
  {
    $model=$this->loadModel($id);

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(Yii::app()->request->isPostRequest)
    {
      if($count = $model->generateInvitations())
      {
        Yii::app()->getUser()->setFlash('flash-success', 'Invitations generated: ' . $count . '.');
        $this->redirect(array('view','id'=>$model->id, 'format'=>'codes'));
      }
      else
      {
        Yii::app()->getUser()->setFlash('flash-error', 'No invitation generated.');
        $this->redirect(array('view','id'=>$model->id, 'format'=>'codes'));
      }
    }

    $this->render('generateinvitations',array(
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
    $dataProvider=new CActiveDataProvider('Assignment');
    $this->render('index',array(
      'dataProvider'=>$dataProvider,
    ));
  }

  /**
   * Lists all public assignments.
   */
  public function actionList()
  {
    $assignments = Assignment::model()->showable()->findAllByAttributes(array('status'=>Assignment::STATUS_PUBLIC));
    $this->render('list',array(
      'assignments'=>$assignments,
    ));
  }


  /**
   * Manages all models.
   */
  public function actionAdmin()
  {
    $model=new Assignment('search');
    $model->unsetAttributes();  // clear any default values
    if(isset($_GET['Assignment']))
      $model->attributes=$_GET['Assignment'];

    $this->render('admin',array(
      'model'=>$model,
    ));
  }
  
  public function actionSelect($id)
  {
    /* not actually used anymore
     * we use the last viewed / created assignment as default anyway
     *
    
    $model = $this->loadModel($id);
    Yii::app()->getUser()->setState('assignment', $model->id);
    Yii::app()->getUser()->setState('assignment-title', $model->title);
    Yii::app()->getUser()->setFlash('flash-success', sprintf('Assignment «%s» selected.', $model));
    $this->redirect(Yii::app()->request->urlReferrer);
    */
  }

  public function actionFiles($id)
  {
    $model=$this->loadModel($id);
    $this->render('files',array(
      'model'=>$model,
      'exercises'=>$model->exercises,
    ));
  }
  
  public function actionStudents($action, $assignment)
  {
    if(!$assignment = Assignment::model()->findByPk($assignment))
    {
      throw new CHttpException(404,'The requested page does not exist.');
    }
    $ids=$_POST['id'];
    
    $flashes = array('success'=>array(), 'error'=>array());

    switch ($action)
    {
      case 'assign':

        $result = $assignment->createExercises($ids);
        
        if(sizeof($result['added']))
        {
          $flashes['success'][] = Yii::t('swu', 'One student has been given the assignment.|{n} students have been given the assignment.', sizeof($result['added']));
        }
        if(sizeof($result['found']))
        {
          $flashes['success'][] = Yii::t('swu', 'One student didn\'t need to be given the assignment.|{n} students didn\'t need to be given the assignment.', sizeof($result['found']));
        }
        
        break;

      
      case 'remove':
      
        $result = $assignment->removeExercises($ids);
        
        if(sizeof($result['removed']))
        {
          $flashes['success'][] = Yii::t('swu', 'One student has been removed.|{n} students have been removed.', sizeof($result['removed']));
        }
        if(sizeof($result['left']))
        {
          $flashes['error'][] = Yii::t('swu', 'One student could not be removed.|{n} students could not be removed.', sizeof($result['left']));
        }
        
        break;
        
      default:
        
          throw new CHttpException(404,'The requested page does not exist.');

    }  // end switch

    $this->setAllFlashes($flashes);
    $this->redirect(array('assignment/view','id'=>$assignment->id));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Assignment the loaded model
   * @throws CHttpException
   */
  public function loadModel($id)
  {
    $model=Assignment::model()->findByPk($id);
    if($model===null)
      throw new CHttpException(404,'The requested page does not exist.');
      
    Yii::app()->getUser()->setState('assignment', $model->id);
    Yii::app()->getUser()->setState('assignment-title', $model->title);
  
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param Assignment $model the model to be validated
   */
  protected function performAjaxValidation($model)
  {
    if(isset($_POST['ajax']) && $_POST['ajax']==='assignment-form')
    {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
  
  public function renderStudent(Exercise $exercise, $row)
  {
    return $this->renderPartial('../student/_student',array('student'=>$exercise->student));
  }

  public function renderFiles(Exercise $exercise, $row)
  {
    return $this->renderPartial('../exercise/_files',array('exercise'=>$exercise, 'filesInfo'=>$exercise->getFilesInformation()));
  }
  
}
