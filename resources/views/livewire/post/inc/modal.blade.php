<div wire:ignore.self class="modal fade" id="crudModal_post" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ürün Ekle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" wire:model="post_id">

                    <div class="form-row">
                        <div class="col-12">
                            <label class="block font-medium text-sm text-gray-700" for="image">
                                Ürün Resmi
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
                            @if($post_image)
                                <div class="col-12 text-center">
                                    <img src="{{ asset('storage/'.$post_image) }}" alt="post image"
                                         style="height: 300px;object-fit: contain;">
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="form-row">
                        <div class="col-6">
                            <label for="title">Ürün Adı</label>
                            <input type="text" class="form-control" id="title" wire:model="title" placeholder="Ürün Adı">
                            @error('title') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-6">
                            <label for="price">Ürün Fiyatı</label>
                            <input type="number" class="form-control" id="price" wire:model="price"
                                   placeholder="Ürün Fiyatı">
                            @error('price') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div wire:ignore>
                            <label for="categories">Kategori</label>
                            <input type="text" id="categories" class="categories" placeholder="Ürün Kategorisi">
                        </div>
                        @error('categories') <span class="text-danger">{{ $message }}</span>@enderror
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
        <script>
            $(document).ready(function () {
                const category_input = document.getElementById('categories');

                const tagify_category = new Tagify(category_input, {
                    delimiters: null,
                    enforceWhitelist: true,
                    whitelist: category_input.value.trim().split(/\s*,\s*/),
                    validate: function (tag) {
                        var max = 1;
                        if (!tagify || tagify.value.length < max) {
                            return true;
                        }
                        tag.value = 'Maximum ' + max + ' items';
                    }
                });

                tagify_category.on('remove', function (e) {
                @this.call('remove_categories', e.detail.data);
                });

                tagify_category.on('add', function (e) {
                @this.call('add_categories', e.detail.data);
                });

                tagify_category.on('input', function (e) {
                    tagify_category.settings.whitelist.length = 0;
                    tagify_category.loading(true)

                    $.ajax({
                        url: "{{ route('category.search') }}",
                        data: {
                            'term': e.detail.value
                        }
                    }).then(function (result) {
                        tagify_category.settings.whitelist.push(...result, ...tagify_category.value)
                        tagify_category
                            .loading(false)
                            .dropdown.show.call(tagify_category, e.detail.value);

                    }).catch(err => tagify_category.dropdown.hide.call(tagify_category))
                });


                function onAddTag(e) {
                @this.call('add_tag', e.detail.data.value);
                }

                function onRemoveTag(e) {
                @this.call('remove_tag', e.detail.data.value);
                }


                window.livewire.on('closeModal', () => {
                    $('#crudModal_post').modal('hide');
                    tagify_category.removeAllTags()
                });
                window.livewire.on('reset', () => {
                    tagify_category.removeAllTags()
                });
                window.livewire.on('postEdit', items => {
                    tagify_category.settings.whitelist.push(...items['categories'], ...tagify_category.value)
                    tagify_category.addTags(items['categories'])
                })
            });
        </script>
    @endpush

    @push('styles')
        <style>
            fieldset.post_salary {
                border: 1px groove #ddd !important;
                padding: 0 1.4em 1.4em 1.4em !important;
                margin: 0 0 1.5em 0 !important;
                -webkit-box-shadow: 0 0 0 0 #000;
                box-shadow: 0 0 0 0 #000;
            }

            legend.post_salary {
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
