<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class RichText extends BaseComponent
{
    protected $selector;

    public function __construct(string $selector)
    {
        $this->selector = $selector;
    }

    /**
     * Get the root selector for the component.
     */
    public function selector()
    {
        return $this->selector;
    }

    /**
     * Fill the rich text field.
     */
    public function fill(Browser $browser, string $html)
    {
        $escapedHtml = addslashes($html);

        $browser->script([
            "$('{$this->selector}').next('.note-editor').find('.note-editable').html('$escapedHtml');",
            "$('{$this->selector}').val('$escapedHtml');"
        ]);
    }
}
