<div wire:ignore.self class="modal fade" id="crudModal_category" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-row">
                        <div class="col-12">
                            <label class="block font-medium text-sm text-gray-700" for="image">
                                Kategori Resmi
                            </label>

                            <input type="file" wire:model="image" id="image"
                                   class="form-control" placeholder="Post Image">
                            @error('image') <span class="text-danger">{{ $message }}</span>@enderror

                        </div>

                        @if ($image and !$errors->has('image'))
                            <div class="col-12 text-center">
                                <img src="{{ $image->temporaryUrl() }}" style="height: 300px;object-fit: contain;"
                                     alt="post image">
                            </div>
                        @else
                            @if($category_image)
                                <div class="col-12 text-center">
                                    <img src="{{ asset('storage/'.$category_image) }}" alt="post image"
                                         style="height: 300px;object-fit: contain;">
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="row">
                        <div class="col">
                            <input type="hidden" wire:model="category_id">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" wire:model="name" id="name" placeholder="Name">
                            @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary" data-dismiss="modal">
                    Close & Reset
                </button>
                <button type="button" wire:click.prevent="create_or_update()" class="btn btn-primary">Save changes
                </button>
            </div>
        </div>
    </div>
    @push('ex_scripts')
        <script type="text/javascript">
            window.livewire.on('closeModal', () => {
                $('#crudModal_category').modal('hide');
            });
        </script>
    @endpush
    @push('styles')
        <style>
            fieldset.category_wage {
                border: 1px groove #ddd !important;
                padding: 0 1.4em 1.4em 1.4em !important;
                margin: 0 0 1.5em 0 !important;
                -webkit-box-shadow: 0 0 0 0 #000;
                box-shadow: 0 0 0 0 #000;
            }

            legend.category_wage {
                font-size: 1.2em !important;
                font-weight: bold !important;
                text-align: left !important;
                width: auto;
                padding: 0 10px;
                border-bottom: none;
            }
        </style>
    @endpush
</div>
