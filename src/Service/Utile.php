<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class Utile{

	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	public function generateUniqueSlug($nom, $entity){
        $slugger = new AsciiSlugger();
        $slug = $slugger->slug($nom);

        $verification = $this->em->getRepository('App\\Entity\\'.$entity)->findOneBySlug($slug);
        if($verification != null){
            // Si on a trouvé un slug qui correspond
            $slug .= '-'.uniqid();
        }

        return $slug;
    }

}