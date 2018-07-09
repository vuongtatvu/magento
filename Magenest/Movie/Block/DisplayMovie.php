<?php
namespace Magenest\Movie\Block;

use    Magento\Framework\View\Element\Template;

class DisplayMovie extends Template
{
    private $_movieCollection;
    private $_actorCollection;
    private $_directorCollection;
    private $_movieactorCollection;

    public function __construct(
        Template\Context $context,
        \Magenest\Movie\Model\ResourceModel\MagenestMovie\CollectionFactory $movieCollection,
        \Magenest\Movie\Model\ResourceModel\MagenestActor\CollectionFactory $actorCollection,
        \Magenest\Movie\Model\ResourceModel\MagenestDirector\CollectionFactory $directorCollection,
        \Magenest\Movie\Model\ResourceModel\MagenestMovieActor\CollectionFactory $movieactorCollection,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->_movieCollection = $movieCollection;
        $this->_actorCollection = $actorCollection;
        $this->_directorCollection = $directorCollection;
        $this->_movieactorCollection = $movieactorCollection;
    }

    public function getMovies()
    {
        $collection = $this->_movieCollection->create();
        return $collection;
    }

    public function getActors()
    {
        $collection = $this->_actorCollection->create();
        return $collection;
    }

    public function getDirectors()
    {
        $collection = $this->_directorCollection->create();
        return $collection;
    }

    public function getNameDirector($director_id){
        $collection = $this->_directorCollection->create()->getItemById($director_id);
        return $collection->getName();
    }

    public function getMovieActors()
    {
        $collection = $this->_movieactorCollection->create();
        return $collection;
    }

    public function getActorMovies($movie_id)
    {
        $collection = $this->_movieactorCollection->create()->getItemsByColumnValue('movie_id', $movie_id);
        return $collection;
    }

    public function getNameActor($actor_id){
        $collection = $this->_actorCollection->create()->getItemById($actor_id);
        return $collection->getName();
    }

}