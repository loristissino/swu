<!DOCTYPE html>
<html  lang="<?php echo Yii::app()->language ?>">
<head>
    <meta charset="utf-8">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link rel="icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/printer.png" type="image/png" />
    <style>
    <?php echo Helpers::getYiiParam('labelsStyle') ?>
    </style>

</head>
<body>
<?php echo $content; ?>
</body>
</html>
