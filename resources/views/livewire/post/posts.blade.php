<div>
    <button type="button" class="btn btn-primary" wire:click="openModal()"
            data-toggle="modal" data-target="#crudModal_post">
        Create
    </button>
    @include('livewire.post.inc.modal')
    @if(session()->has('message'))
        <div class="alert {{session('alert') ?? 'alert-info'}}" style="margin-top:30px;">
            {{ session('message') }}
        </div>
    @endif
    <input type="text" wire:model="search_term" class="col-2 form-control mt-3"
           placeholder="Search title.."
    />
    <table class="table table-bordered mt-1">
        <thead>
        <tr>
            <th style="width: 20px">ID</th>
            <th style="width: 100px">Image</th>
            <th>Title</th>
            <th>Price</th>
            <th>Categories</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>
                    <img src="{{ asset('storage/'.$post->image) }}"
                         alt="" style="width: 100px;height:50px;object-fit: contain;">
                </td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->price }} ₺</td>
                <td>
                    @foreach($post->categories as $category)
                        <span class="badge badge-info">
                            {{ $category->category->name }}
                        </span>
                    @endforeach
                </td>
                <td>
                    <button data-toggle="modal" data-target="#crudModal_post" wire:click="edit({{ $post->id }})"
                            class="btn btn-primary btn-sm">Edit
                    </button>
                    @if($confirming===$post->id)
                        <button wire:click="delete({{ $post->id }})" class="btn btn-danger btn-sm">Sure?</button>
                    @else
                        <button wire:click="confirmDelete({{ $post->id }})" class="btn btn-secondary btn-sm">Delete
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $posts->links() }}
</div>
