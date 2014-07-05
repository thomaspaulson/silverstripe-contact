<?php
//
class ContactPage extends Page {
  private static $db = array(
	  'Mailto' => 'Varchar(100)',
	  'SubmitText' => 'HTMLText',
	  );
	  
  public function getCMSFields(){
    $fields = parent::getCMSFields();
    $fields->addFieldToTab( "Root.Main", new EmailField('Mailto', 'Recipient')    );
    $fields->addFieldToTab( "Root.Main",   new HTMLEditorField( 'SubmitText','   Text after sending the message' ));
    return $fields;
  }
  
  
}//

class ContactPage_Controller extends Page_Controller {

  private static $allowed_actions = array('sent','Form');
  
  public function init(){
      parent::init();
  }
  public function Form(){
    define('SPAN', '<span class="required">*</span>');
    $firstName = new TextField('FirstName', 'First name' . SPAN);
    $firstName->addExtraClass('rounded');
    $lastName = new TextField('LastName', 'Last name' . SPAN);
    $lastName->addExtraClass('rounded');  
    $email = new EmailField('Email', 'Email address' . SPAN);
    $email->addExtraClass('rounded');
    $phone = new TextField('Phone', 'Phone number');
    $phone->addExtraClass('rounded');
    $comment = new TextareaField('Comment','Message' . SPAN);
    $comment->addExtraClass('rounded');
    
    /*
    // with captacha
    $recaptchaField = new RecaptchaField('MyCaptcha');
    $recaptchaField->jsOptions = array('theme' => 'clean'); // optional

    $fields = new FieldList(    $firstName,    $lastName,    $email,    $phone,    $comment  , $recaptchaField  );
    */
    //without captacha
    $fields = new FieldList(    $firstName,    $lastName,    $email,    $phone,    $comment   );
    $send = new FormAction('sendemail', 'Send');
    $send->addExtraClass('rounded');
    $actions = new FieldList(  $send    );
    
    $validator = new RequiredFields(  'Email',    'Comment',    'FirstName',   'LastName'   );
    
    $form = new Form($this, 'Form', $fields, $actions, $validator );
    $form->setAttribute('novalidate', 'novalidate');
    //$form->setAttribute('class','form');
    //$form->enableSpamProtection();
    return $form;
  }
  
  
  public function sendemail($data, $form){  
  
    if(!empty($this->Mailto)){
      $email = $this->Mailto;
    } else {
      $email = EMAIL;
    }
    $from = $data['Email'];
    $to = $email;
    $config = SiteConfig::current_site_config();
    $config->Title;
    $subject = "Contact Form - ".$config->Title;
    $email = new Email($from, $to, $subject);
    $email->setTemplate('ContactEmail');
    $email->populateTemplate($data);
    $email->send();
    
    return $this->redirect($this->Link('sent'));
  }
  
  public function sent(){
    return $this->render();
  }

  protected function IsSuccess(){
    $url = $this->urlParams;
    return (isset($url['Action']) && ($url['Action'] == 'sent'));
  }
  
}//



