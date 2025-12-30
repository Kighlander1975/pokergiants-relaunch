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
    public ?string $imageUrl;
    public int $size;
    public string $sizeClass = '';

    /**
     * Create a new component instance.
     */
    public function __construct(
        ?string $imageFilename = null,
        ?string $imageUrl = null,
        ?string $firstname = null,
        ?string $lastname = null,
        ?string $nickname = null,
        ?string $displayMode = null,
        int $size = 64
    ) {
        $this->imageFilename = $imageFilename;
        $this->imageUrl = $imageUrl;
        $this->size = $size;
        $this->sizeClass = "avatar-size-{$size}";

        // Generate initials with clear priority:
        // 1. Avatar image exists: No initials needed (handled in view)
        // 2. Display mode determines what to use for initials
        // 3. Fallback: Use '??'
        $this->initials = $this->generateInitials($firstname, $lastname, $nickname, $displayMode);

        // Generate background color based on hash of initials
        $this->backgroundColor = $this->generateColor($this->initials);
    }

    /**
     * Generate initials with clear priority hierarchy.
     */
    private function generateInitials(?string $firstname, ?string $lastname, ?string $nickname, ?string $displayMode): string
    {
        // If display mode is specified, use it to determine what to show
        if ($displayMode === 'initials' && !empty($firstname) && !empty($lastname)) {
            return strtoupper(substr($firstname, 0, 1) . substr($lastname, 0, 1));
        }

        if ($displayMode === 'nickname' && !empty($nickname)) {
            return strtoupper(substr($nickname, 0, 2));
        }

        // Default behavior (backward compatibility)
        // Priority 1: Firstname + Lastname available → First letters of both names
        if (!empty($firstname) && !empty($lastname)) {
            return strtoupper(substr($firstname, 0, 1) . substr($lastname, 0, 1));
        }

        // Priority 2: Nickname available (required field) → First 2 characters
        if (!empty($nickname)) {
            return strtoupper(substr($nickname, 0, 2));
        }

        // Priority 3: Fallback for edge cases
        return '??';
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
