<?php

class MessageController extends Controller
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
      'postOnly + email',
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
        'actions'=>array('ack'),
        'users'=>array('*'),
      ),
      /*
      array('allow', // allow authenticated user to perform 'create' and 'update' actions
        'actions'=>array('create','update'),
        'users'=>array('@'),
      ),*/
      array('allow', // allow admin user to perform 'admin' and 'delete' actions
        'actions'=>array('index','view','create','update','admin','delete','confirm','email','send','do','activation', 'toggle'),
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
    $model = $this->loadModel($id);
    $this->render('view',array(
      'model'=>$model,
      'student'=>$model->student,
    ));
  }

  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate()
  {
    $model=new Message;

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(isset($_POST['Message']))
    {
      $model->attributes=$_POST['Message'];
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

    if(isset($_POST['Message']))
    {
      $model->attributes=$_POST['Message'];
      if(!$model->sent_at)
      {
        $model->sent_at = null;
      }
      if($model->save())
        $this->redirect(array('view','id'=>$model->id));
    }

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
    $dataProvider=new CActiveDataProvider('Message');
    $this->render('index',array(
      'dataProvider'=>$dataProvider,
    ));
  }


  public function actionConfirm()
  {
    $ids=$_POST['id'];
    $count = Message::model()->updateByPk($ids, array('confirmed_at'=>date('Y-m-d H:i:s')));

    if($count)
    {
      Yii::app()->getUser()->setFlash('flash-success', 'Messages confirmed: ' . $count . '.');
    }
    else
    {
      Yii::app()->getUser()->setFlash('flash-error', 'No message confirmed.');
    }
    $this->redirect(array('message/admin'));
    
  }
    
  public function actionEmail()
  {
    $count = 0;
    foreach($messages as $message)
    {
      $count += $message->send();
    }
    
    if($count)
    {
      Yii::app()->getUser()->setFlash('flash-success', 'Messages sent: ' . $count . '.');
      $this->redirect(array('admin'));
    }
    else
    {
      Yii::app()->getUser()->setFlash('flash-error', 'No message sent.');
      $this->redirect(array('view'));
    }
    
  }
  
  public function actionAck($k)
  {
    $this->render('ack',array(
      'result'=>Message::acknowledge($k),
    ));
  }

  /**
   * Manages all models.
   */
  public function actionAdmin()
  {
    $model=new Message('search');
    $model->unsetAttributes();  // clear any default values
    if(isset($_GET['Message']))
      $model->attributes=$_GET['Message'];

    $this->render('admin',array(
      'model'=>$model,
    ));
  }
  
  public function actionActivation($active)
  {
    Yii::app()->user->setState('sending', $active);
    return $this->redirect(array('message/send'));
  }

  public function actionToggle($all)
  {
    Yii::app()->user->setState('listall', $all);
    return $this->redirect(array('message/admin'));
  }
  
  public function actionSend()
  {
    $this->render('send',array(
    ));
  }
  
  public function actionDo()
  {
    Message::model()->sendFirstQueued();
    return $this->renderPartial('_queue', array(
      'queued_messages'=>Message::model()->findAllConfirmedToBeSent(),
      ), false);
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Message the loaded model
   * @throws CHttpException
   */
  public function loadModel($id)
  {
    $model=Message::model()->findByPk($id);
    if($model===null)
      throw new CHttpException(404,'The requested page does not exist.');
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param Message $model the model to be validated
   */
  protected function performAjaxValidation($model)
  {
    if(isset($_POST['ajax']) && $_POST['ajax']==='message-form')
    {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
  
  public function renderBody(Message $message, $row)
  {
    return nl2br(substr(CHtml::encode($message->body), 0, 100). '...');
  }

  public function renderHtml(Message $message, $row)
  {
    return nl2br(substr(CHtml::encode($message->html), 0, 100). '...');
  }

  
}
