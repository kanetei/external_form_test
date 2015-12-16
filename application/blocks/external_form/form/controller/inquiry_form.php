<?php
namespace Application\Block\ExternalForm\Form\Controller;
use Concrete\Core\Controller\AbstractController;
use Loader;
use UserInfo;
use Page;

class InquiryForm extends AbstractController
{
  public function action_test_form_submit()
  {

    // validation/form ヘルパーを呼び出し
    $val = Loader::helper('validation/form');

    // postデータをバリデーションに登録
    $val->setData($this->post());

    // 必須項目ルールの追加
    $val->addRequired('inquiry', 'お問合せ内容を入力してください。');
    $val->addRequired('chktest', 'どちらかにチェックしてください。');

    // メールアドレスの検証
    $val->addRequiredEmail('email', '有効なメールアドレスを入力してください。');

    // テスト実行
    if (!$val->test()) {

      // テストに通らなかった場合はビューにエラーを渡す
      $errorArray = $val->getError()->getList();
      $this->set('errorArray', $errorArray);

    } else {

      // テストに通った場合の処理
      $mh = Loader::helper('mail');

      // 管理者ユーザー情報を取得
      $adminUserInfo = UserInfo::getByID(USER_SUPER_ID);

      if (is_object($adminUserInfo)) {

        // 管理者メールアドレスをFromに設定
        $mh->from($adminUserInfo->getUserEmail());

      }

      // Toアドレスの設定
      $mh->to($this->post('email'));

      // ページのユーザー情報を取得
      $c = Page::getCurrentPage();
      $vo = $c->getVersionObject();
      if (is_object($vo)) {

        $uID = $vo->getVersionAuthorUserID();
        $ui = UserInfo::getByID($uID);

        // bccアドレスの設定
        $mh->bcc($ui->getUserEmail());

      }

      // メール変数を設定
      $mh->addParameter('inquiry', $this->post('inquiry'));
      $mh->addParameter('chktest', $this->post('chktest'));

      // メールテンプレートを設定
      $mh->load('inquiry_form_complete');

      // メール送信
      $mh->sendMail();
      $this->set('response', 'お申し込みありがとうございました。');

    }

    return true;

  }

  public function view()
  {
    // ページのユーザー情報を取得
    $c = Page::getCurrentPage();
    $vo = $c->getVersionObject();
    if (is_object($vo)) {
      $uID = $vo->getVersionAuthorUserID();
      $ui = UserInfo::getByID($uID);
    }
    $this->set('message', $ui->getUserName()."様へのお問い合わせ");
  }

}
