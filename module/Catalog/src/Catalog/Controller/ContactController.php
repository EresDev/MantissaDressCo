<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/25/14
 * Time: 7:37 AM
 */

namespace Catalog\Controller;


use Application\Controller\UserActionController;
use Catalog\Form\ContactForm;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class ContactController extends UserActionController{
    public function indexAction(){
        $form = new ContactForm();
        if($this->getRequest()->isPost()){
            $post = $this->getRequest()->getPost()->toArray();
            $form->setData($post);
            $valid1 = $form->isValid();
            $valid2 = $form->isValid($form->setInputFilter($form->getCustomInputFilter()));
            if($valid1 && $valid2){

                $rep = $this->getEntityManager()->getRepository('Catalog\Entity\Setting');

                $site_name = $rep->findOneBy(array('settingKey' => 'site_name'));
                if(!$site_name){
                    throw new \Exception("site_name was not found.");
                }

                $site_email = $rep->findOneBy(array('settingKey' => 'site_email'));
                if(!$site_email){
                    throw new \Exception("site_name was not found.");
                }



                /** @var User $user */
                $user = $this->getEntityManager()->getRepository('Catalog\Entity\User')->findOneBy(array('email' => $post['email']));


                $htmlMarkup = "".
                    "<h1>".$site_name->getSettingValue()."</h1><hr>".
                    "Find below details of contact request.<hr>".
                    "Name: ".$post['yourname']."<br>".
                    "Email: ".$post['email']."<br>".
                    "Subject: ".$post['subject']."<br>".
                    "Message: ".$post['message']."<br>";



                $html = new MimePart($htmlMarkup);
                $html->type = "text/html";

                $body = new MimeMessage();
                $body->setParts(array($html));

                $message = new Message();
                $message->addFrom($post['email'], $post['yourname'])
                    ->addTo($site_email->getSettingValue())
                    ->setSubject("New Inquiry - ".$post['subject']);

                $message->setBody($body);
                $transport = new Sendmail();
                $transport->send($message);
                $this->flashMessenger()->addSuccessMessage("Your inquiry is received. We will get back to you ASAP. Thank you for contacting us.");
                return array(
                    'form' => new ContactForm(),
                );
            }
        }
        else if($this->getAuthService()->hasIdentity()){
            $user = $this->getEntityManager()->getRepository('Catalog\Entity\User')->find($this->getAuthService()->getIdentity());
            $data = array(
                'yourname' => $user->getFirstname()." ".$user->getLastname(),
                'email' => $user->getEmail(),
            );
            $form->setData($data);
        }

        return array(
          'form' => $form,
        );

    }
} 