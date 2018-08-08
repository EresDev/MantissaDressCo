<?php
/**
 * Created by PhpStorm.
 * User: Ares
 * Date: 9/25/14
 * Time: 10:23 PM
 */

namespace Application\Controller;



use Zend\Mvc\Controller\AbstractActionController;

class AdminActionController extends ActionController{
    private $images_dir = "ResponsiveFilemanager/source/";

    protected function getViewImage($img){
        if(!$img){
            return null;
        }

        return $this->url()->fromRoute('home', array(), array('force_canonical' => true)).$this->images_dir.$img;
    }
    protected function getDbImage($img){
        if(!$img){
            return null;
        }
        $array  = explode($this->images_dir, $img);
        if(!isset($array[1]) || !$array[1]){
            $img = str_replace($this->url()->fromRoute('home', array(), array('force_canonical' => true)), "", $img);
            if($img){
                return $img;
            }
            else{
                throw new \Exception("getDbImage failed for ". $img);
            }

        }
        return $array[1];
    }
    protected function getViewDescription($description){
        //$description = htmlspecialchars_decode($description);

        if(! preg_replace('/\s+/', '', $description)){
            return "";
        }
        $dom=new \DOMDocument();
        $dom->loadHTML($description);
        $imgs = $dom->getElementsByTagName('img');
        foreach($imgs as $img){

            $img_src = $img->getAttribute('src'); // will give you the src String

            if(strpos($img_src, "http://") === false && strpos($img_src, "https://") === false){
                $img->setAttribute('src', $this->getViewImage($img_src));
            }
        }

        $anchors = $dom->getElementsByTagName('a');
        foreach($anchors as $anchor){

            $anchor_src = $anchor->getAttribute('href'); // will give you the src String

            if(strpos($anchor_src, "http://") === false && strpos($anchor_src, "https://") === false){
                $anchor->setAttribute('href', $this->getViewImage($anchor_src));
            }
        }
        $text = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $dom->saveHTML());
        return $text;
    }

    protected function getDbDescription($description){
		if(!$description){
			return '';	
		}
        $dom=new \DOMDocument();
        $dom->loadHTML($description);
        $imgs = $dom->getElementsByTagName('img');
        foreach($imgs as $img){

            $img_src = $img->getAttribute('src'); // will give you the src String

            if(strpos($img_src, $this->images_dir) !== false){
                $img->setAttribute('src', $this->getDbImage($img_src));
            }
        }

        $anchors = $dom->getElementsByTagName('a');
        foreach($anchors as $anchor){

            $anchor_src = $anchor->getAttribute('href'); // will give you the src String

            if(strpos($anchor_src, $this->images_dir) !== false){
                $anchor->setAttribute('href', $this->getDbImage($anchor_src));
            }
        }

        $text = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $dom->saveHTML());
        return $text;
    }
} 