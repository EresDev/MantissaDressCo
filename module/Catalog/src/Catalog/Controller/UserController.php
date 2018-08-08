<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/22/14
 * Time: 11:46 PM
 */

namespace Catalog\Controller;

use Catalog\Entity\LoginHistory;
use Catalog\Entity\User;
use Catalog\Form\ForgotPasswordForm;
use Catalog\Form\LoginForm;
use Application\Controller\UserActionController;
use Catalog\Form\RegisterForm;

use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Session\Container;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\View\Model\ViewModel;

class UserController extends UserActionController{

    public function loginAction(){
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('account');
        }

        $loginform = new LoginForm();
        if($this->getRequest()->isPost()){
            $loginform->setData($this->getRequest()->getPost());
            if($loginform->isValid()){
                $user = $this->getEntityManager()->getRepository("Catalog\Entity\User")->findOneBy(array('email' =>$this->getRequest()->getPost('email')));
                if($user->getBanned() == 1){
                    $this->flashMessenger()->addErrorMessage("Your account is suspended. Please contact support if you this this is inappropriate. ");
                    return array(
                        'loginform' => new LoginForm(),

                    );
                }
                $this->getAuthService()->getAdapter()
                    ->setIdentity($this->getRequest()->getPost('email'))
                    ->setCredential($this->getRequest()->getPost('password'));

                $result = $this->getAuthService()->authenticate();
                if ($result->isValid()) {

                    $this->getAuthService()->setStorage($this->getSessionStorage());
                    $this->getAuthService()->getStorage()->write($user->getUserId());
                    $this->handleLoginHistory($user->getUserId());
                    if($user->getEmailVerified() == 0){
                        $user->setEmailVerified(1);
                        $this->getEntityManager()->persist($user);
                        $this->getEntityManager()->flush();
                    }
                    $container = new Container('redirect');
                    if($container && $container->redirect_to){
                        $url = $container->redirect_to;
                        $container->getManager()->getStorage()->clear("redirect");
                        return $this->redirect()->toUrl(rawurldecode($url));

                    }
                    return $this->redirect()->toRoute('account');
                }
                else{
                    foreach($result->getMessages() as $message)
                    {
                        //save message temporary into flashmessenger
                        $this->flashmessenger()->addErrorMessage($message);

                    }
                }

            }
        }
        $redirect = $this->params()->fromQuery('redirect_to');
        if($redirect){
            $container = new Container('redirect');
            $container->redirect_to = rawurlencode($redirect);

        }
        return array(
            'loginform' => $loginform,

        );
    }
    public function logoutAction()
    {
        $this->getAuthService()->clearIdentity();
        $cart = new Container('cart');
        if($cart && $cart->items){
            $cart->getManager()->getStorage()->clear("cart");
        }
        $this->flashmessenger()->addSuccessMessage("You've been logged out.");
        return $this->redirect()->toRoute('login');
    }
    public function registerAction(){
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('account');
        }
        $loginform = new LoginForm();
        $registerform = new RegisterForm();

        if($this->getRequest()->isPost()){
            $post = $this->getRequest()->getPost()->toArray();
            $registerform->setData($post);
            $valid1 = $registerform->isValid();
            $valid2 = $registerform->setInputFilter($registerform->getCustomInputFilter())->isValid() ;
            if($valid1 && $valid2 ){
                $rep = $this->getEntityManager()->getRepository('Catalog\Entity\Setting');
                $from_email = $rep->findOneBy(array('settingKey' => 'from_email'));
                if(!$from_email){
                    throw new \Exception("from_email was not found.");
                }

                    $em = $this->getEntityManager();
                    $already = $em->getRepository('Catalog\Entity\User')->findBy(array('email' => $post['email']));
                    if($already){
                        $registerform->get('email')->setMessages(array('The email address is already in use. Try again.'));
                    }
                    else{
                        $password = rand(1000000,9999999);
                        $post['password'] = md5($password);
                        $user = new User($post);
                        $hydrator = new ClassMethods(true);
                        $hydrator->hydrate($post, $user);

                        $em->persist($user);
                        $em->flush();


                        $site_name = $rep->findOneBy(array('settingKey' => 'site_name'));
                        if(!$site_name){
                            throw new \Exception("site_name was not found.");
                        }

                        $email_footer = $rep->findOneBy(array('settingKey' => 'email_footer'));
                        if(!$email_footer){
                            throw new \Exception("email_footer was not found.");
                        }

                        /** @var User $user */
                        $user = $this->getEntityManager()->getRepository('Catalog\Entity\User')->findOneBy(array('email' => $post['email']));



                        $htmlMarkup = "".
                            "<h1>".$site_name->getSettingValue()."</h1><hr>".
                            "Hello ".$user->getLastname().",<br>".
                            "<p>Thank you for creating an account with us. Your account is ready and you can login now.".
                            "Your password is: $password</p>".
                            "<p>If you have any question, please contact us.</p>".
                            $email_footer->getSettingValue();


                        $html = new MimePart($htmlMarkup);
                        $html->type = "text/html";

                        $body = new MimeMessage();
                        $body->setParts(array($html));

                        $message = new Message();
                        $message->addFrom($from_email->getSettingValue(), $site_name->getSettingValue())
                            ->addTo($post['email'])
                            ->setSubject("Registration");

                        $message->setBody($body);
                        $transport = new Sendmail();
                        $transport->send($message);


                        $view = new ViewModel(
                            array(
                                'loginform' => $loginform
                            )
                        );
                        $view->setTemplate("catalog/user/register-success");
                        return $view;
                    }


            }

        }
        $redirect = $this->params()->fromQuery('redirect_to');
        if($redirect){
            $container = new Container('redirect');
            $container->redirect_to = $redirect;

        }
        return array(
            'loginform' => $loginform,
            'registerform' => $registerform
        );
    }
    private function handleLoginHistory($user_id){
        $em = $this->getEntityManager();
        $history = $em->getRepository('Catalog\Entity\LoginHistory')->findBy(array('user' => $user_id), array('historyId' => 'DESC'));
        $count = count($history);
        $hydrator = new ClassMethods(true);
        for($i = 99; $i < $count; ++$i){
            $em->remove($history[$i]);

        }

        $new_history = array(
            'ip' => $this->getRequest()->getServer('REMOTE_ADDR'),
            'date_time' => new \DateTime(),
            'user' => $em->getRepository('Catalog\Entity\User')->find($user_id),
        );
        $his = new LoginHistory();
        $hydrator->hydrate($new_history, $his);
        $em->persist($his);
        $em->flush();

    }

    public function forgotAction(){
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('account');
        }
        $form = new ForgotPasswordForm();
        if($this->getRequest()->isPost()){
            $post = $this->getRequest()->getPost()->toArray();
            $post['firstname'] = ucwords(strtolower($post['firstname']));
            $post['lastname'] = ucwords(strtolower($post['lastname']));

            $form->setData($post);
            $valid1 = $form->isValid();
            $valid2 = $form->isValid($form->setInputFilter($form->getCustomInputFilter()));
            if($valid1 && $valid2){
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

                /** @var User $user */
                $user = $this->getEntityManager()->getRepository('Catalog\Entity\User')->findOneBy(array('email' => $post['email']));
                if(!$user){
                    $this->flashMessenger()->addErrorMessage('User with given email do not exist. Try again.');
                    $view = new ViewModel(
                        array(
                            'form' => $form,
                        )
                    );

                    return $view;
                }
                $password = rand(1111111, 9999999);
                $user->setPassword(md5($password));
                $this->getEntityManager()->persist($user);


                $htmlMarkup = "".
                    "<h1>".$site_name->getSettingValue()."</h1><hr>".
                    "Hello ".$user->getLastname().",<br>".
                    "<p>We have received a password reset request for your account recently.".
                    "Your new password is: $password</p>".
                    "<p>If you have not requested this action, please contact us.</p>".
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
                $this->flashMessenger()->addSuccessMessage("A new password has been sent to your email address.");
                $this->getEntityManager()->flush();

            }

        }
        $view = new ViewModel(
            array(
                'form' => $form,
            )
        );

        return $view;
    }

    private function verifyEmail($toemail, $fromemail, $getdetails = false){
        $email_arr = explode("@", $toemail);
        $details = '';
        $domain = array_slice($email_arr, -1);
        $domain = $domain[0];
        // Trim [ and ] from beginning and end of domain string, respectively
        $domain = ltrim($domain, "[");
        $domain = rtrim($domain, "]");
        if( "IPv6:" == substr($domain, 0, strlen("IPv6:")) ) {
            $domain = substr($domain, strlen("IPv6") + 1);
        }
        $mxhosts = array();
        if( filter_var($domain, FILTER_VALIDATE_IP) )
            $mx_ip = $domain;
        else
            getmxrr($domain, $mxhosts, $mxweight);
        if(!empty($mxhosts) )
            $mx_ip = $mxhosts[array_search(min($mxweight), $mxhosts)];
        else {
            if( filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ) {
                $record_a = dns_get_record($domain, DNS_A);
            }
            elseif( filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ) {
                $record_a = dns_get_record($domain, DNS_AAAA);
            }
            if( !empty($record_a) )
                $mx_ip = $record_a[0]['ip'];
            else {
                $result = "invalid";
                $details .= "No suitable MX records found.";
                return ( (true == $getdetails) ? array($result, $details) : $result );
            }
        }
        $connect = @fsockopen($mx_ip, 25);
        if($connect){
            if(preg_match("/^220/i", $out = fgets($connect, 1024))){
                fputs ($connect , "HELO $mx_ip\r\n");
                $out = fgets ($connect, 1024);
                $details .= $out."\n";
                fputs ($connect , "MAIL FROM: <$fromemail>\r\n");
                $from = fgets ($connect, 1024);
                $details .= $from."\n";
                fputs ($connect , "RCPT TO: <$toemail>\r\n");
                $to = fgets ($connect, 1024);
                $details .= $to."\n";
                fputs ($connect , "QUIT");
                fclose($connect);
                if(!preg_match("/^250/i", $from) || !preg_match("/^250/i", $to)){
                    $result = "invalid";
                }
                else{
                    $result = "valid";
                }
            }
        }
        else{
            $result = "invalid";
            $details .= "Could not connect to server";
        }
        if($getdetails){
            return array($result, $details);
        }
        else{
            return $result;
        }
    }
} 