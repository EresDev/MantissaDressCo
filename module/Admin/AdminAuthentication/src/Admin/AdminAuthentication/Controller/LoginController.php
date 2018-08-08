<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 8/15/14
 * Time: 9:57 AM
 */

namespace Admin\AdminAuthentication\Controller;

use Admin\AdminAuthentication\Form\LoginForm;
use Admin\AdminAuthentication\Model\Login;

use Application\Controller\ActionController;
use Catalog\Entity\Admin;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class LoginController extends ActionController{
    public function loginAction(){
        $form = new LoginForm();
        $request = $this->getRequest();
        if($request->isPost()){
            $login = new Login();
            $form->setInputFilter($login->getInputFilter());
            $form->setData($request->getPost()->toArray());

            if($form->isValid()){

                $login->exchangeArray($form->getData());
                $success = $login->login($this->getServiceLocator());
                if($success){

                   //$this->forward()->dispatch('AdminAccount\Controller\Account', array('action' => 'index'));
                    $this->redirect()->toRoute('admin-account');
                }
                else{
                    return array('invalid_credentials' => 'Username/Password combination do not match. Try again.', 'loginform' => $form);
                }
            }
        }

        return array('loginform' => $form);
    }

    public function logoutAction(){
        $container = new Container('admin');
        $container->getManager()->getStorage()->clear("admin");;
        return array();

    }
    public function forgotAction(){

        if($this->getRequest()->isPost() && $this->getRequest()->isXmlHttpRequest()){

            $post = $this->getRequest()->getPost()->toArray();
            $em = $this->getEntityManager();
            /** @var Admin $admin */
            $admin = $em->getRepository('Catalog\Entity\Admin')->findOneBy(
                array(
                    'username' => $post['username'],
                    'email' => $post['email'],
                )
            );
            if($admin){

                $password = md5(rand(1111111,9999999));
                $admin->setPassword(md5($password));
                $em->persist($admin);

                $rep = $this->getEntityManager()->getRepository('Catalog\Entity\Setting');

                $site_name = $rep->findOneBy(array('settingKey' => 'site_name'));
                if(!$site_name){
                    throw new \Exception("site_name was not found.");
                }


                $from_email = $rep->findOneBy(array('settingKey' => 'from_email'));
                if(!$from_email){
                    throw new \Exception("from_email was not found.");
                }

                $email_footer = $rep->findOneBy(array('settingKey' => 'email_footer'));
                if(!$email_footer){
                    throw new \Exception("email_footer was not found.");
                }



                $htmlMarkup = "".
                    "<h1>".$site_name->getSettingValue()."</h1><hr>".
                    "Hello ".$admin->getUsername().",<br>".
                    "<p>We have received a password reset request for your account recently.".
                    "Your new password is: $password</p>".
                    "<p></p>".
                    $email_footer->getSettingValue();


                $html = new MimePart($htmlMarkup);
                $html->type = "text/html";

                $body = new MimeMessage();
                $body->setParts(array($html));

                $message = new Message();
                $message->addFrom($from_email->getSettingValue(), $site_name->getSettingValue())
                    ->addTo($post['email'])
                    ->setSubject("Password Reset Request");

                $message->setBody($body);
                $transport = new Sendmail();
                $transport->send($message);

                $em->flush();
                return new JsonModel(
                    array(
                        'status' => 'success',
                        'message' => 'A new password has been sent to your email address.',
                    )
                );
            }
            else{

                return new JsonModel(
                    array(
                        'status' => 'error',
                        'message' => 'The provided combination do not match any account.',
                    )
                );
            }
        }
        else{
            $this->getResponse()->setStatusCode(404);
            return;
        }
    }
} 