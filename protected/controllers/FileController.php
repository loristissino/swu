<?php

class FileController extends Controller
{
  /**
   * @var string the default layout for the views. 
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
      'postOnly + mark', // we only allow mark setting via POST request
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
      array('allow',  // allow all users to perform 'upload' action
        'actions'=>array('upload','view','download','raw'),
        'users'=>array('*'),
      ),
      array('allow', // allow admin user to perform the selected actions
        'actions'=>array('index','admin','create','update','delete','servesql', 'mark'),
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
  public function actionView($id, $hash, $code='', $status=0)
  {
    $model = $this->loadModelByIdAndHash($id, $hash);
    $this->render('view',array(
      'model'=>$model,
      'assignment'=>$model->exercise->assignment,
      'student'=>$model->exercise->student,
      'code'=>$code,
      'status'=>$status,
    ));
  }

  public function actionDownload($id, $hash)
  {
    $model = $this->loadModelByIdAndHash($id, $hash);
    $this->sendDispositionHeader($model->original_name);
    $this->serveFile($model->type, $model->getFile(Yii::app()->basePath. DIRECTORY_SEPARATOR . Helpers::getYiiParam('uploadDirectory')));
  }
  
  public function actionRaw($id, $hash)
  {
    $model = $this->loadModelByIdAndHash($id, $hash);
    $this->sendDispositionHeader($model->getFile());  // not actually used for files downloaded by wget
    $this->serveFile($model->type, $model->getFile(Yii::app()->basePath. DIRECTORY_SEPARATOR . Helpers::getYiiParam('uploadDirectory')));
  }
    
  public function actionPlaintext($id, $hash)
  {
    $model = $this->loadModelByIdAndHash($id, $hash);
    $this->sendDispositionHeader($model->getFile());  // not actually used for files downloaded by wget
    $this->serveContent('text/plain', $model->content);
  }

  public function actionServesql($filename)
  {
    if(Yii::app()->user->isGuest)
        throw new CHttpException(401,'You are not authorized to access this page.');
    
    if(!file_exists($file=Yii::app()->basePath . DIRECTORY_SEPARATOR . Helpers::getYiiParam('uploadDirectory') . DIRECTORY_SEPARATOR . $filename))
        throw new CHttpException(404,'The requested file does not exist.');
    
    $this->serveFile('text/plain; charset="utf-8" ', $file);
  }


  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate()
  {
    $model=new File;

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(isset($_POST['File']))
    {
      $model->attributes=$_POST['File'];
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

    if(isset($_POST['File']))
    {
      $model->attributes=$_POST['File'];
      if($model->save())
        $this->redirect(array('view','id'=>$model->id, 'hash'=>$model->md5));
    }

    $this->render('update',array(
      'model'=>$model,
    ));
  }

  /**
   * Allows the upload of a file.
   */
  public function actionUpload($code='')
  {
    $model= new UploadForm();
    
    if(!Yii::app()->user->isGuest)
    {
      $model->byteacher = true;
    }
    
    $model->code = $code;
    $model->setUrlExample();
    
    if(isset($_POST['UploadForm']))
    {
      $model->uploadedfile = CUploadedFile::getInstance($model, 'uploadedfile');
      $model->attributes=$_POST['UploadForm'];
      
      if($model->validate())
      {
        if($file = $model->saveData(Yii::app()->basePath. DIRECTORY_SEPARATOR. Helpers::getYiiParam('uploadDirectory')))
        {
          if(!$model->byteacher)  // we won't send notifications if it is a direct upload by teacher
          {
            if($file->exercise->assignment->notification)
            {
              MailTemplate::model()->mailFromTemplate('new_work_notification', Helpers::getYiiParam('adminEmail'), array(
                'student'=>$model->exercise->student,
                'file'=>$file,
                'url'=>$this->createAbsoluteSslUrl('file/view', array('id'=>$file->id, 'hash'=>$file->md5)),
              ));
            }
            if($model->exercise->student->email)
            {
              MailTemplate::model()->mailFromTemplate('new_work_acknowledgement', array($model->exercise->student->email=>$model->exercise->student), array(
                'student'=>$model->exercise->student,
                'file'=>$file,
                'url'=>$this->createAbsoluteSslUrl('file/view', array('id'=>$file->id, 'hash'=>$file->md5)),
              ));
              Yii::app()->getUser()->setFlash('success', 'Work correctly uploaded / saved. An email has been sent to your address.');
            }
          }
          else
          {
            Yii::app()->getUser()->setFlash('success', 'Work correctly uploaded / saved.');
          }
          
          $this->redirect(array('file/view','id'=>$file->id, 'hash'=>$file->md5, 'status'=>1));
        }
        else
        {
          Yii::app()->getUser()->setFlash('error', 'The work could not be saved.');
        }
      }
    }

    $this->render('upload',array(
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
    throw new CHttpException(501,'Not yet implemented.');
    /*
    $this->loadModel($id)->delete();

    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    if(!isset($_GET['ajax']))
      $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    */
  }

  /**
   * Lists all models.
   */
  public function actionList()
  {
    
    $dataProvider=new CActiveDataProvider('File');
    $this->render('index',array(
      'dataProvider'=>$dataProvider,
    ));
    
  }

  /**
   * Manages all models.
   */
  public function actionAdmin()
  {
    $model=new File('search');
    $model->unsetAttributes();  // clear any default values
    if(isset($_GET['File']))
      $model->attributes=$_GET['File'];

    $this->render('admin',array(
      'model'=>$model,
    ));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return File the loaded model
   * @throws CHttpException
   */
  public function loadModel($id)
  {
    $model=File::model()->findByPk($id);
    if($model===null)
      throw new CHttpException(404,'The requested page does not exist.');
    return $model;
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return File the loaded model
   * @throws CHttpException
   */
  public function loadModelByIdAndHash($id, $hash)
  {
    $model=$this->loadModel($id);
    if($model->md5!=$hash)
      throw new CHttpException(404,'The requested page does not exist.');
    return $model;
  }


  /**
   * Performs the AJAX validation.
   * @param File $model the model to be validated
   */
  protected function performAjaxValidation($model)
  {
    if(isset($_POST['ajax']) && $_POST['ajax']==='file-form')
    {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
  
  
  public function actionMark($id)
  {
    $model=$this->loadModel($id);
    $model->markAsChecked(false);
    $this->redirect(Yii::app()->request->urlReferrer);
  }
  
}
