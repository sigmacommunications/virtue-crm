<div class="col-md-12">
    <div class="card p-3">
        <form wire:submit.prevent="saveContact">
            <input type="hidden"  wire:model="project_id"   value="1"/>
            <input type="hidden"  wire:model="department"   value="1"/>
            <div wire:ignore>

                <textarea  name="comment" wire:model="comment" class="form-control comment" id="description"  rows="6"></textarea>
            </div>
            @error('comment') <span class="error">{{ $message }}</span> @enderror
            <button type="submit"  class="btn btn-sm btn-primary w-25 mt-2">Save</button>
        </form>
    </div>
</div>


<script>
    document.addEventListener('livewire:load', function() {
        $('#description').summernote({
            height: 200,
            callbacks: {
                onChange: function(e) {
                    @this.set('comment', e);
                },

            },
            hint: {
                mentions:   @json($users),
                match: /\B@(\w*)$/,
                search: function (keyword, callback) {
                    callback($.grep(this.mentions, function (item) {
                        return item.indexOf(keyword) == 0;
                    }));
                },
                content: function (item) {
                    return '@' + item + '';

                }
            },
        });


    });
</script>
