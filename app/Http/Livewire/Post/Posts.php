<?php

namespace App\Http\Livewire\Post;

use App\Models\Categories;
use App\Models\PostCategories;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Post as PostModel;

class Posts extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $search_term;
    public $post_image = null;
    public $categories = [];
    public $title, $image, $post_id, $confirming, $price;
    public $updateMode = false;

    public function mount()
    {
    }

    public function render()
    {
        $search_term = '%'.$this->search_term.'%';
        $categories_data = Categories::query()->get();
        $posts = PostModel::query()
            ->where('title', 'like', $search_term)->paginate(10);
        return view('livewire.post.posts', [
            'categories_data' => $categories_data,
            'posts' => $posts
        ]);
    }

    private function resetInputFields()
    {
        $this->title = null;
        $this->post_image = null;
        $this->image = null;
        $this->post_id = null;
        $this->price = null;
        $this->confirming = null;
        $this->categories = [];
        // Clean errors if were visible before
        $this->resetErrorBag();
        $this->resetValidation();
        $this->emit('reset');
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }

    public function confirmDelete($id)
    {
        $this->confirming = $id;
    }

    public function openModal()
    {
        if ($this->post_id){
            $this->post_id = null;
            $this->resetInputFields();
        }
    }

    public function closeModal()
    {
        $this->updateMode = false;
        $this->emit('closeModal');
        $this->resetInputFields();
    }

    public function create_or_update()
    {
        if ($this->post_id) {
            $this->update();
        } else {
            $this->store();
        }
    }

    public function add_categories($items)
    {
        $this->categories[] = $items;
    }

    public function remove_categories($items)
    {
        foreach ($this->categories as $key => $category) {
            if ($category['id'] == $items['id']) {
                unset($this->categories[$key]);
                break;
            }
        }
    }

    public function edit($id)
    {
        $this->resetInputFields();
        $this->updateMode = true;
        $post = PostModel::query()->with(['categories'])
            ->where('id', $id)->first();

        if (isset($post)) {
            $this->post_id = $id;
            $this->title = $post->title;
            $this->post_image = $post->image;
            $this->price = $post->price;

            $new_categories = [];
            foreach ($post->categories as $category) {
                $new_categories[] = [
                    'id' => $category->category->id,
                    'value' => $category->category->name,
                    'code' => $category->category->code,
                    'currency' => $category->category->currency,
                    'current_wage' => $category->current_wage_info
                ];
            }

            $this->emit('postEdit', [
                'categories' => $new_categories
            ]);
        }

    }

    public function store()
    {
        $this->validate([
            'title' => 'required|min:6|unique:posts,title',
            'price' => 'required',
            'image' => 'required|dimensions:min_width=100,min_height=200|mimes:jpeg,png,jpg,gif,svg',
            'categories' => 'required|array|min:1',
        ]);
        $image_path = $this->image->store('posts', 'public');
        $post = PostModel::query()->create([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'image' => $image_path,
            'price' => $this->price
        ]);
        foreach ($this->categories as $category) {
            PostCategories::query()->create([
                'post_id' => $post->id,
                'category_id' => $category['id'],
            ]);
        }
        session()->flash('message', 'Post Created Successfully.');
        $this->resetInputFields();
        $this->closeModal();
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|min:6|unique:posts,title,' . $this->post_id,
            'price' => 'required',
            'image' => 'nullable|dimensions:min_width=100,min_height=200|mimes:jpeg,png,jpg,gif,svg',
            'categories' => 'required|array|min:1',
        ]);
        $post = PostModel::query()->find($this->post_id);
        if ($this->image) {
            $image_path = $this->image->store('posts', 'public');
        } else {
            $image_path = $post->image;
        }
        $post->update([
            'title' => $this->title,
            'image' => $image_path,
            'price' => $this->price
        ]);
        $current_p_tag_ids = [];
        $current_p_category_ids = [];

        foreach ($this->categories as $category) {
            $post_category = PostCategories::query()->updateOrCreate([
                'post_id' => $post->id,
                'category_id' => $category['id']
            ]);
            $current_p_category_ids[] = $post_category->category_id;
        }
        PostCategories::query()->where('post_id', $post->id)
            ->whereNotIn('category_id', $current_p_category_ids)->delete();
        $this->closeModal();
        session()->flash('message', 'Post Updated Successfully.');
        $this->resetInputFields();
    }

    public function delete($id)
    {
        if ($id) {
            PostModel::query()->where('id', $id)->delete();
            session()->flash('message', 'Post Deleted Successfully.');
        }
    }
}
