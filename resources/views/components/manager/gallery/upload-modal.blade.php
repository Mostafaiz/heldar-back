<div class="back-cover expand" x-show="$wire.visible" x-cloak
    x-on:keydown.escape.window="$dispatch('hide-upload-modal')" x-transition.opacity>
    <form class="upload-modal-container" wire:submit="store" x-on:click.outside="$dispatch('hide-upload-modal')">
        @if($form->image && !$errors->has('form.image'))
            <div class="image-preview-container">
                <img src="{{ $form->image->temporaryUrl() }}" class="image-preview">
                <i class="fa-solid fa-circle-xmark" wire:click="removeImage"></i>
            </div>
        @else
            <div class="image-selector-container">
                <label class="image-selector" for="image-selector" x-show="!$wire.uploading">
                    <i class="fa-solid fa-circle-plus"></i>
                    <span class="info-title">انتخاب تصویر</span>
                    <span class="info-description">حداکثر حجم تصویر 2 مگابایت می‌باشد.</span>
                </label>

                <div class="progress-bar-container" x-show="$wire.uploading">
                    <div class="inner-progress-container" x-bind:style="'--i:' + $wire.progress">
                        <div class="progress-bar">
                            <span x-text="$wire.progress"></span>%
                        </div>
                    </div>
                    <x-blade.manager.tonal-button class="cancel-button" x-on:click="$wire.cancelUpload('form.image')"
                        value="انصراف" />
                </div>
            </div>
            @error('form.image')
                <span class="error-message image">{{ $message }}</span>
            @enderror

        @endif

        <input type="file" id="image-selector" accept="image/*" onchange="uploadImage(event)" />

        <x-blade.manager.input-text title="برچسب تصویر (alt)" name="form.alt" />

        <div class="buttons-container">
            <x-blade.manager.tonal-button value="لغو" x-on:click="$dispatch('hide-upload-modal')" />
            <x-blade.manager.filled-button type="submit" value="ذخیره" target="store" />
        </div>
    </form>
</div>

@script
<script>
    window.uploadImage = async function (event) {
        const file = event.target.files[0];
        if (file) {
            const maxSize = {{ config('gallery.max_image_size') }} * 1024;

            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/gif'];

            if (!validTypes.includes(file.type)) {
                @this.addImageTypeError();
                return;
            }

            if (file.size >= maxSize) {
                @this.addImageMaxError();
                return;
            }

            if (file.name.length > 150) {
                @this.addImageNameError();
                return;
            }

            @this.uploading = true;
            await @this.resetImageErrors();
            document.getElementById('image-selector').value = '';
            @this.upload('form.image', file,
                () => {
                    // done
                    @this.uploading = false;
                    @this.progress = 100;
                },
                () => {
                    // fail
                    @this.uploading = false;
                    @this.progress = 0;
                },
                (event) => {
                    // progress
                    @this.progress = event.detail.progress;
                },
                () => {
                    // cancel
                    @this.uploading = false;
                    @this.progress = 0;
                }
            );
        }
    };
</script>
@endscript