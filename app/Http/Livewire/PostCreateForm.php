<?php

namespace App\Http\Livewire;

use Livewire\WithFileUploads;
use App\Models\Categories;
use Livewire\Component;

class PostCreateForm extends Component
{
    use WithFileUploads;

    public $title;
    public $image;
    public $countries;
    public $description;
    public $subdivision_id;

    protected $rules = [
        'title' => 'required|min:6',
        'image' => 'required|dimensions:min_width=100,min_height=200|mimes:jpeg,png,jpg,gif,svg',
        'categories' => 'required|array|max:1|min:1',
        'price' => 'required|min:1',
    ];

    public function render()
    {
        $countries_data = Categories::query()->get();
        return view('livewire.post-create-form', compact('countries_data'));
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function savePost()
    {
        $validatedData = $this->validate();
//        $this->image->store('photos');
        return redirect()->route('posts');
    }
}
