<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\LorePage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.private')]
class LoreEdit extends Component
{
    public ?LorePage $lorePage = null;

    public string $slug = '';

    public string $title = '';

    public string $status = 'draft';

    public string $body_md = '';

    public string $excerpt_md = '';

    public array $tags = [];

    public function mount(?LorePage $lorePage = null)
    {
        if ($lorePage && $lorePage->exists) {
            $this->lorePage = $lorePage;
            $this->slug = $lorePage->slug;
            $this->title = $lorePage->title;
            $this->status = $lorePage->status;
            $this->body_md = $lorePage->body_md;
            $this->excerpt_md = $lorePage->excerpt_md ?? '';
            $this->tags = $lorePage->tags_json ?? [];
        }
    }

    public function save()
    {
        $existingId = $this->lorePage && $this->lorePage->exists ? $this->lorePage->id : 'NULL';

        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:lore_pages,slug,'.$existingId,
            'body_md' => 'required',
        ]);

        $userId = Auth::id();

        $data = [
            'slug' => $this->slug ?: Str::slug($this->title),
            'title' => $this->title,
            'status' => $this->status,
            'body_md' => $this->body_md,
            'excerpt_md' => $this->excerpt_md,
            'tags_json' => $this->tags,
            'author_id' => $userId,
        ];

        if ($this->lorePage && $this->lorePage->exists) {
            $this->lorePage->update($data);
            $savedPage = $this->lorePage;
        } else {
            $savedPage = LorePage::create($data);
            $this->lorePage = $savedPage;
        }

        session()->flash('message', 'Lore page saved successfully!');

        return redirect()->route('lore.show', $savedPage);
    }

    public function render()
    {
        $pageTitle = ($this->lorePage ? 'Edit' : 'Create').' Lore Page';

        return view('livewire.pages.lore-edit', [
            'title' => $pageTitle,
        ]);
    }
}
