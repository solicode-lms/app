{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}  

<form action="{{ $item->id ? route('comments.update', $item->id) : route('comments.store') }}" method="POST">
    @csrf

    @if ($item->id)
        @method('PUT')
    @endif

    <div class="card-body">
        
        <div class="form-group">
            <label for="content">
                {{ ucfirst(__('PkgBlog::comment.content')) }}
                
                    <span class="text-danger">*</span>
                
            </label>
            <input
                name="content"
                type="input"
                class="form-control"
                id="content"
                placeholder="{{ __('Enter PkgBlog::comment.content') }}"
                value="{{ $item ? $item->content : old('content') }}">
            @error('content')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        

        
        <div class="form-group">
            <label for="user_id">
                {{ ucfirst(__('PkgBlog::user.singular')) }}
                <span class="text-danger">*</span>
            </label>
            <select id="user_id" name="user_id" class="form-control">
                <option value="">Sélectionnez une option</option>
            </select>
            @error('user_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="article_id">
                {{ ucfirst(__('PkgBlog::article.singular')) }}
                <span class="text-danger">*</span>
            </label>
            <select id="article_id" name="article_id" class="form-control">
                <option value="">Sélectionnez une option</option>
            </select>
            @error('article_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
    </div>

    <div class="card-footer">
        <a href="{{ route('comments.index') }}" class="btn btn-default">{{ __('Core::msg.cancel') }}</a>
        <button type="submit" class="btn btn-info ml-2">{{ $item->id ? __('Core::msg.edit') : __('Core::msg.add') }}</button>
    </div>
</form>

<script>
    window.dynamicSelectManyToOne = [
        
        {
            fieldId: 'user_id',
            fetchUrl: "{{ route('users.all') }}",
            selectedValue: {{ $item->user_id ? $item->user_id : 'undefined' }},
            fieldValue: 'name'
        },
        
        {
            fieldId: 'article_id',
            fetchUrl: "{{ route('articles.all') }}",
            selectedValue: {{ $item->article_id ? $item->article_id : 'undefined' }},
            fieldValue: 'title'
        }
        
    ];
</script>
