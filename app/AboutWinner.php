<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AboutWinner extends Model
{
    protected $guarded = [];

    protected $casts = [
        'links' => 'array',
    ];

    public const PLACEMENTS = [
        'winner' => 'Winner',
        'first_runner_up' => 'First Runner Up',
        'second_runner_up' => 'Second Runner Up',
    ];

    public function placementLabel()
    {
        if (!empty($this->label)) {
            return $this->label;
        }
        return self::PLACEMENTS[$this->placement] ?? ucwords(str_replace('_', ' ', (string) $this->placement));
    }

    /** Normalised list of links: [ ['url' => ..., 'label' => ...], ... ]. */
    public function linkList()
    {
        $links = $this->links;
        if (!is_array($links)) {
            return [];
        }
        $out = [];
        foreach ($links as $link) {
            if (!is_array($link)) {
                continue;
            }
            $url = trim((string) ($link['url'] ?? ''));
            if ($url === '') {
                continue;
            }
            $out[] = [
                'url' => $url,
                'label' => trim((string) ($link['label'] ?? '')),
            ];
        }
        return $out;
    }

    /** The first link URL (used to make the winner image/name clickable), or null. */
    public function firstLinkUrl()
    {
        $links = $this->linkList();
        return $links[0]['url'] ?? null;
    }
}
