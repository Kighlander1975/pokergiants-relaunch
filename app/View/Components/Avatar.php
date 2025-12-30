<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Avatar extends Component
{
    public string $initials;
    public string $backgroundColor;
    public ?string $imageFilename;
    public int $size;

    /**
     * Create a new component instance.
     */
    public function __construct(
        ?string $imageFilename = null,
        ?string $firstname = null,
        ?string $lastname = null,
        ?string $nickname = null,
        int $size = 64
    ) {
        $this->imageFilename = $imageFilename;
        $this->size = $size;

        // Generate initials
        if ($firstname && $lastname) {
            $this->initials = strtoupper(substr($firstname, 0, 1) . substr($lastname, 0, 1));
        } elseif ($nickname) {
            $this->initials = strtoupper(substr($nickname, 0, 2));
        } else {
            $this->initials = '??';
        }

        // Generate background color based on hash of initials + user identifier
        $this->backgroundColor = $this->generateColor($this->initials);
    }

    /**
     * Generate a consistent background color based on initials.
     */
    private function generateColor(string $seed): string
    {
        $hash = crc32($seed);
        $hue = $hash % 360; // 0-359 degrees

        // Use HSL for better color distribution
        // Saturation: 60-80%, Lightness: 45-65% for good contrast
        $saturation = 70;
        $lightness = 55;

        return "hsl({$hue}, {$saturation}%, {$lightness}%)";
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.avatar');
    }
}
