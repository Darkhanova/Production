<?php
require_once "../controllers/BaseCatTwigController.php"; // импортим BaseCatTwigController

class MainController extends BaseCatTwigController {
    public $template = "main.twig";
    public $title = "Главная";

    public function getContext(): array
    {
        $context = parent::getContext();

        if (isset($_GET['type'])) {
            $query = $this->pdo->prepare("SELECT * FROM cats_objects WHERE type = :type");
            $query->bindValue("type", $_GET['type']);
            $query->execute();
        } else {
            $query = $this->pdo->query("SELECT * FROM cats_objects");
        }

        $context['cats'] = $query->fetchAll();

        return $context;
    }
}