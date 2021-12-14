<div>
    <button type="button" class="btn btn-primary" wire:click="openModal()"
            data-toggle="modal" data-target="#crudModal_category">
        Create
    </button>
    @include('livewire.category.inc.modal')
    @if(session()->has('message'))
        <div class="alert {{session('alert') ?? 'alert-info'}}" style="margin-top:30px;">
            {{ session('message') }}
        </div>
    @endif
    <input type="text" wire:model="search_term" class="col-2 form-control mt-3"
           placeholder="Search name.."
    />
    <table class="table table-bordered mt-1">
        <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>
                    <img src="{{ asset('storage/'.$category->image) }}"
                         alt="" style="width: 100px;height:50px;object-fit: contain;">
                </td>
                <td>{{ $category->name }}</td>
                <td>
                    <button data-toggle="modal" data-target="#crudModal_category" wire:click="edit({{ $category->id }})"
                            class="btn btn-primary btn-sm">Edit
                    </button>
                    @if($confirming===$category->id)
                        <button wire:click="delete({{ $category->id }})" class="btn btn-danger btn-sm">Sure?</button>
                    @else
                        <button wire:click="confirmDelete({{ $category->id }})" class="btn btn-secondary btn-sm">Delete
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $categories->links() }}

</div>
