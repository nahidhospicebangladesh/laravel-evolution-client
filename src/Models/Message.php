<?php

namespace SamuelTerra22\EvolutionLaravelClient\Models;

class Message
{
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Create a new Message instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->attributes;
    }
}

class QuotedMessage extends Message
{
    /**
     * Create a new QuotedMessage instance.
     *
     * @param array $key
     * @param array|null $message
     */
    public function __construct(array $key, ?array $message = null)
    {
        $attributes = ['key' => $key];

        if ($message !== null) {
            $attributes['message'] = $message;
        }

        parent::__construct($attributes);
    }
}

class TextMessage extends Message
{
    /**
     * Create a new TextMessage instance.
     *
     * @param string $number
     * @param string $text
     * @param int|null $delay
     * @param QuotedMessage|null $quoted
     * @param bool|null $linkPreview
     * @param bool|null $mentionsEveryOne
     * @param array|null $mentioned
     */
    public function __construct(
        string $number,
        string $text,
        ?int $delay = null,
        ?QuotedMessage $quoted = null,
        ?bool $linkPreview = null,
        ?bool $mentionsEveryOne = null,
        ?array $mentioned = null
    ) {
        $attributes = [
            'number' => $number,
            'text' => $text,
        ];

        if ($delay !== null) {
            $attributes['delay'] = $delay;
        }

        if ($quoted !== null) {
            $attributes['quoted'] = $quoted->toArray();
        }

        if ($linkPreview !== null) {
            $attributes['linkPreview'] = $linkPreview;
        }

        if ($mentionsEveryOne !== null) {
            $attributes['mentionsEveryOne'] = $mentionsEveryOne;
        }

        if ($mentioned !== null) {
            $attributes['mentioned'] = $mentioned;
        }

        parent::__construct($attributes);
    }
}

class MediaMessage extends Message
{
    /**
     * Create a new MediaMessage instance.
     *
     * @param string $number
     * @param string $mediatype
     * @param string|null $caption
     * @param string|null $mimetype
     * @param string|null $fileName
     * @param int|null $delay
     * @param QuotedMessage|null $quoted
     * @param bool|null $mentionsEveryOne
     * @param array|null $mentioned
     */
    public function __construct(
        string $number,
        string $mediatype,
        ?string $caption = null,
        ?string $mimetype = null,
        ?string $fileName = null,
        ?int $delay = null,
        ?QuotedMessage $quoted = null,
        ?bool $mentionsEveryOne = null,
        ?array $mentioned = null
    ) {
        $attributes = [
            'number' => $number,
            'mediatype' => $mediatype,
        ];

        if ($caption !== null) {
            $attributes['caption'] = $caption;
        }

        if ($mimetype !== null) {
            $attributes['mimetype'] = $mimetype;
        }

        if ($fileName !== null) {
            $attributes['fileName'] = $fileName;
        }

        if ($delay !== null) {
            $attributes['delay'] = $delay;
        }

        if ($quoted !== null) {
            $attributes['quoted'] = $quoted->toArray();
        }

        if ($mentionsEveryOne !== null) {
            $attributes['mentionsEveryOne'] = $mentionsEveryOne;
        }

        if ($mentioned !== null) {
            $attributes['mentioned'] = $mentioned;
        }

        parent::__construct($attributes);
    }
}

class LocationMessage extends Message
{
    /**
     * Create a new LocationMessage instance.
     *
     * @param string $number
     * @param string $name
     * @param string $address
     * @param float $latitude
     * @param float $longitude
     * @param int|null $delay
     * @param QuotedMessage|null $quoted
     */
    public function __construct(
        string $number,
        string $name,
        string $address,
        float $latitude,
        float $longitude,
        ?int $delay = null,
        ?QuotedMessage $quoted = null
    ) {
        $attributes = [
            'number' => $number,
            'name' => $name,
            'address' => $address,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];

        if ($delay !== null) {
            $attributes['delay'] = $delay;
        }

        if ($quoted !== null) {
            $attributes['quoted'] = $quoted->toArray();
        }

        parent::__construct($attributes);
    }
}

class Contact
{
    /**
     * @var string
     */
    protected $fullName;

    /**
     * @var string
     */
    protected $wuid;

    /**
     * @var string
     */
    protected $phoneNumber;

    /**
     * @var string|null
     */
    protected $organization;

    /**
     * @var string|null
     */
    protected $email;

    /**
     * @var string|null
     */
    protected $url;

    /**
     * Create a new Contact instance.
     *
     * @param string $fullName
     * @param string $wuid
     * @param string $phoneNumber
     * @param string|null $organization
     * @param string|null $email
     * @param string|null $url
     */
    public function __construct(
        string $fullName,
        string $wuid,
        string $phoneNumber,
        ?string $organization = null,
        ?string $email = null,
        ?string $url = null
    ) {
        $this->fullName = $fullName;
        $this->wuid = $wuid;
        $this->phoneNumber = $phoneNumber;
        $this->organization = $organization;
        $this->email = $email;
        $this->url = $url;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $attributes = [
            'fullName' => $this->fullName,
            'wuid' => $this->wuid,
            'phoneNumber' => $this->phoneNumber,
        ];

        if ($this->organization !== null) {
            $attributes['organization'] = $this->organization;
        }

        if ($this->email !== null) {
            $attributes['email'] = $this->email;
        }

        if ($this->url !== null) {
            $attributes['url'] = $this->url;
        }

        return $attributes;
    }
}

class ContactMessage extends Message
{
    /**
     * Create a new ContactMessage instance.
     *
     * @param string $number
     * @param array $contacts
     */
    public function __construct(string $number, array $contacts)
    {
        $contactsArray = [];

        foreach ($contacts as $contact) {
            if ($contact instanceof Contact) {
                $contactsArray[] = $contact->toArray();
            } else {
                $contactsArray[] = $contact;
            }
        }

        parent::__construct([
            'number' => $number,
            'contact' => $contactsArray,
        ]);
    }
}

class ReactionMessage extends Message
{
    /**
     * Create a new ReactionMessage instance.
     *
     * @param array $key
     * @param string $reaction
     */
    public function __construct(array $key, string $reaction)
    {
        parent::__construct([
            'key' => $key,
            'reaction' => $reaction,
        ]);
    }
}

class PollMessage extends Message
{
    /**
     * Create a new PollMessage instance.
     *
     * @param string $number
     * @param string $name
     * @param int $selectableCount
     * @param array $values
     * @param int|null $delay
     * @param QuotedMessage|null $quoted
     */
    public function __construct(
        string $number,
        string $name,
        int $selectableCount,
        array $values,
        ?int $delay = null,
        ?QuotedMessage $quoted = null
    ) {
        $attributes = [
            'number' => $number,
            'name' => $name,
            'selectableCount' => $selectableCount,
            'values' => $values,
        ];

        if ($delay !== null) {
            $attributes['delay'] = $delay;
        }

        if ($quoted !== null) {
            $attributes['quoted'] = $quoted->toArray();
        }

        parent::__construct($attributes);
    }
}

class ListRow
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $rowId;

    /**
     * Create a new ListRow instance.
     *
     * @param string $title
     * @param string $description
     * @param string $rowId
     */
    public function __construct(string $title, string $description, string $rowId)
    {
        $this->title = $title;
        $this->description = $description;
        $this->rowId = $rowId;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'rowId' => $this->rowId,
        ];
    }
}

class ListSection
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var array
     */
    protected $rows = [];

    /**
     * Create a new ListSection instance.
     *
     * @param string $title
     * @param array $rows
     */
    public function __construct(string $title, array $rows)
    {
        $this->title = $title;

        $rowsArray = [];

        foreach ($rows as $row) {
            if ($row instanceof ListRow) {
                $rowsArray[] = $row->toArray();
            } else {
                $rowsArray[] = $row;
            }
        }

        $this->rows = $rowsArray;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'rows' => $this->rows,
        ];
    }
}

class ListMessage extends Message
{
    /**
     * Create a new ListMessage instance.
     *
     * @param string $number
     * @param string $title
     * @param string $description
     * @param string $buttonText
     * @param string $footerText
     * @param array $sections
     * @param int|null $delay
     * @param QuotedMessage|null $quoted
     */
    public function __construct(
        string $number,
        string $title,
        string $description,
        string $buttonText,
        string $footerText,
        array $sections,
        ?int $delay = null,
        ?QuotedMessage $quoted = null
    ) {
        $sectionsArray = [];

        foreach ($sections as $section) {
            if ($section instanceof ListSection) {
                $sectionsArray[] = $section->toArray();
            } else {
                $sectionsArray[] = $section;
            }
        }

        $attributes = [
            'number' => $number,
            'title' => $title,
            'description' => $description,
            'buttonText' => $buttonText,
            'footerText' => $footerText,
            'sections' => $sectionsArray,
        ];

        if ($delay !== null) {
            $attributes['delay'] = $delay;
        }

        if ($quoted !== null) {
            $attributes['quoted'] = $quoted->toArray();
        }

        parent::__construct($attributes);
    }
}

class Button
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $displayText;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Create a new Button instance.
     *
     * @param string $type
     * @param string $displayText
     * @param array $attributes
     */
    public function __construct(string $type, string $displayText, array $attributes = [])
    {
        $this->type = $type;
        $this->displayText = $displayText;
        $this->attributes = $attributes;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $result = [
            'type' => $this->type,
            'displayText' => $this->displayText,
        ];

        return array_merge($result, $this->attributes);
    }
}

class ButtonMessage extends Message
{
    /**
     * Create a new ButtonMessage instance.
     *
     * @param string $number
     * @param string $title
     * @param string $description
     * @param string $footer
     * @param array $buttons
     * @param int|null $delay
     * @param QuotedMessage|null $quoted
     */
    public function __construct(
        string $number,
        string $title,
        string $description,
        string $footer,
        array $buttons,
        ?int $delay = null,
        ?QuotedMessage $quoted = null
    ) {
        $buttonsArray = [];

        foreach ($buttons as $button) {
            if ($button instanceof Button) {
                $buttonsArray[] = $button->toArray();
            } else {
                $buttonsArray[] = $button;
            }
        }

        $attributes = [
            'number' => $number,
            'title' => $title,
            'description' => $description,
            'footer' => $footer,
            'buttons' => $buttonsArray,
        ];

        if ($delay !== null) {
            $attributes['delay'] = $delay;
        }

        if ($quoted !== null) {
            $attributes['quoted'] = $quoted->toArray();
        }

        parent::__construct($attributes);
    }
}

class StatusMessage extends Message
{
    /**
     * Create a new StatusMessage instance.
     *
     * @param string $type
     * @param string $content
     * @param string|null $caption
     * @param string|null $backgroundColor
     * @param int|null $font
     * @param bool $allContacts
     * @param array|null $statusJidList
     */
    public function __construct(
        string $type,
        string $content,
        ?string $caption = null,
        ?string $backgroundColor = null,
        ?int $font = null,
        bool $allContacts = false,
        ?array $statusJidList = null
    ) {
        $attributes = [
            'type' => $type,
            'content' => $content,
            'allContacts' => $allContacts,
        ];

        if ($caption !== null) {
            $attributes['caption'] = $caption;
        }

        if ($backgroundColor !== null) {
            $attributes['backgroundColor'] = $backgroundColor;
        }

        if ($font !== null) {
            $attributes['font'] = $font;
        }

        if ($statusJidList !== null) {
            $attributes['statusJidList'] = $statusJidList;
        }

        parent::__construct($attributes);
    }
}
