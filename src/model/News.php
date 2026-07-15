<?php 
Class News
{
    private $id;
    private $title;
    private $summary;
    private $content;
    private $tag;
    private $type;
    private $image;
    private $attachments;
    private $event_date;
    private $created_at;

    public function __construct($id, $title, $summary, $content, $tag, $type, $image, $attachments, $event_date, $created_at)
    {
        $this->id = $id;
        $this->title = $title;
        $this->summary = $summary;
        $this->content = $content;
        $this->tag = $tag;
        $this->type = $type;
        $this->image = $image;
        $this->attachments = json_decode($attachments, true);
        $this->event_date = is_numeric($event_date) ? (int)$event_date : null; // Ensure event_date is an integer or null
        $this->created_at = (int)$created_at; // Ensure created_at is an integer
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return htmlspecialchars($this->title);
    }

    public function getSummary()
    {
        return htmlspecialchars($this->summary);
    }

    public function getContent()
    {
        return htmlspecialchars($this->content);
    }

    public function getTag()
    {
        return htmlspecialchars($this->tag);
    }

}