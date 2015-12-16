<?php
$form = Loader::helper('form');
defined('C5_EXECUTE') or die("Access Denied.");
// エラーメッセージの表示
if (isset($errorArray) && is_array($errorArray) && count($errorArray) > 0) {
?>
<div style="border:1px solid red">
  <?php
  foreach ($errorArray as $e){
  ?>
  <p style="color:red"><?php echo $e?></p>
  <?php
  }
  ?>
</div>
<?php
}
// お礼メッセージの表示
if (isset($response)) { echo $response; }
?>
<h2><?php echo $message?></h2>
<form method="post" action="<?php echo $this->action('test_form_submit')?>">
  <dl>
    <dt>お問い合わせ内容</dt>
    <dd><?php echo $form->textarea('inquiry')?></dd>
    <dt>メールアドレス</dt>
    <dd><?php echo $form->text('email')?></dd>
    <dt>チェックボックス</dt>
    <dd><?php	echo $form->checkbox('chktest[]', 'チェック1')?> チェック1</dd>
    <dd><?php	echo $form->checkbox('chktest[]', 'チェック2')?> チェック2</dd>
  </dl>
  <?php echo $form->submit('submit','送信'); ?>
</form>
