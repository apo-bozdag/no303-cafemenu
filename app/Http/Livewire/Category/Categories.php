<?php

namespace App\Http\Livewire\Category;

use Livewire\Component;
use App\Models\Categories as categoriesModel;
use Livewire\WithPagination;
use Livewire\WithFileUploads;


class Categories extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $search_term;
    public $category_image = null;
    public $name, $image, $confirming, $category_id;
    public $updateMode = false;
    /**
     * @var array
     */

    public function mount()
    {
    }

    public function render()
    {
        $search_term = '%'.$this->search_term.'%';
        $categories = categoriesModel::query()
            ->where('name', 'like', $search_term)->paginate(10);
        return view('livewire.category.categories', [
            'categories' => $categories
        ]);
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->image = '';
        $this->category_image = null;

        // Clean errors if were visible before
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function confirmDelete($id)
    {
        $this->confirming = $id;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:categories,name',
            'image' => 'required|dimensions:min_width=100,min_height=200|mimes:jpeg,png,jpg,gif,svg',
        ]);
        $image_path = $this->image->store('categories', 'public');
        categoriesModel::query()->create([
            'name' => $this->name,
            'image' => $image_path,
        ]);
        session()->flash('message', 'Category Created Successfully.');
        $this->resetInputFields();
        $this->closeModal();
    }

    public function edit($id)
    {
        $this->resetInputFields();
        $this->updateMode = true;
        $category = categoriesModel::query()->where('id', $id)->first();
        $this->category_id = $id;
        $this->name = $category->name;
        $this->category_image = $category->image;
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }

    public function closeModal()
    {
        $this->updateMode = false;
        $this->emit('closeModal');
        $this->resetInputFields();
    }

    public function openModal()
    {
        if($this->category_id){
            $this->category_id = null;
            $this->resetInputFields();
        }
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|unique:categories,name,' . $this->category_id,
            'image' => 'nullable|dimensions:min_width=100,min_height=200|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $category = categoriesModel::query()->find($this->category_id);
        if ($this->image) {
            $image_path = $this->image->store('categories', 'public');
        } else {
            $image_path = $category->image;
        }
        $category->update([
            'name' => $this->name,
            'image' => $image_path,
        ]);
        $this->closeModal();
        session()->flash('message', 'Category Updated Successfully.');
        $this->resetInputFields();
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        if ($id) {
            categoriesModel::query()->where('id', $id)->delete();
            session()->flash('message', 'Category Deleted Successfully.');
        }
    }

    public function create_or_update()
    {
        if ($this->category_id) {
            $this->update();
        } else {
            $this->store();
        }
    }
}
