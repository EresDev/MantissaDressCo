<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/27/14
 * Time: 9:26 AM
 */
namespace Admin\Newsletter\Controller;

use Application\Controller\ActionController;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class NewsletterController extends  ActionController{
    public function indexAction(){

        if($this->getRequest()->isPost()){

            $post = $this->getRequest()->getPost()->toArray();
            if( str_replace(' ', '', $post['subject'] )== "" || str_replace(' ', '', $post['message'] )== ""){
                $this->flashMessenger()->addErrorMessage("Subject and message both are required and cannot be empty.");

            }
            else{
                $users = $this->getEntityManager()->getRepository('Catalog\Entity\User')->findBy(array(
                    'newsletter' => true,
                    'banned' => false,
                    'emailVerified' => true,
                ));
                if($users){
                    $rep = $this->getEntityManager()->getRepository('Catalog\Entity\Setting');

                    $from_email = $rep->findOneBy(array('settingKey' => 'from_email'));
                    if(!$from_email){
                        throw new \Exception("from_email was not found.");
                    }

                    $email_footer = $rep->findOneBy(array('settingKey' => 'email_footer'));
                    if(!$email_footer){
                        throw new \Exception("email_footer was not found.");
                    }
                    $site_name = $rep->findOneBy(array('settingKey' => 'site_name'));
                    if(!$site_name){
                        throw new \Exception("site_name was not found.");
                    }

                    $htmlMarkup = $post['message'];
                    $html = new MimePart($htmlMarkup);
                    $html->type = "text/html";
                    $body = new MimeMessage();
                    $body->setParts(array($html));
                    foreach($users as $user){
                        $message = new Message();
                        $message->addFrom($from_email->getSettingValue(), $site_name->getSettingValue())
                            ->addTo($user->getEmail())
                            ->setSubject($post['subject']);

                        $message->setBody($body);
                        $transport = new Sendmail();
                        $transport->send($message);
                    }

                    $this->flashMessenger()->addSuccessMessage("Newsletter sent to ".count($users). " user/users successfully.");

                }
                else{
                    $this->flashMessenger()->addErrorMessage("No active user with verified email is subscribed to newsletter.");

                }
            }
        }
        return array();
    }
} 