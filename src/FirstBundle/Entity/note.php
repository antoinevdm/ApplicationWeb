<?php

namespace FirstBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * note
 *
 * @ORM\Table(name="note")
 * @ORM\Entity(repositoryClass="FirstBundle\Repository\noteRepository")
 */
class note
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=3000)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
     private $date;

     /**
      *@ORM\ManyToOne(targetEntity="categorie", inversedBy="note")
      *@ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
      */
     private $categorie;

     public function __construct() {
         $this->date = new \DateTime();
     }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return note
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }



    /**
     * Set content
     *
     * @param string $content
     *
     * @return note
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return note
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set categorie
     *
     * @param \FirstBundle\Entity\categorie $categorie
     *
     * @return note
     */
    public function setCategorie(\FirstBundle\Entity\categorie $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \FirstBundle\Entity\categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    public function addXmlNeed($content)
    {
        $toValid = new \DOMDocument;
        $contentxml = new \DOMImplementation;
        $toValid->appendChild($contentxml->createDocumentType('content'));

        $content_elem = $toValid->createElement('content');
        $content_xml = $toValid->createDocumentFragment();
        $content_xml->appendXML($this->content);
        $content_elem->appendChild($content_xml);

        $toValid->appendChild($content_elem);


        return $toValid;
    }

    /**
     * @Assert\IsTrue(message = "Xml non valide")
     */
    public function isValid()
    {
        $toValid = $this->addXmlNeed($this->content);

        try {
                $toValid->schemaValidate("SchemaContent.xsd");
        }
        catch( \ErrorException $e) {
            return false;
        }
        return true;
    }
}
