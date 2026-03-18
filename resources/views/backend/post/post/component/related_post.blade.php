<div class="ibox w">
    <div class="ibox-title">
        <h5>Bài viết liên quan</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <select multiple name="related_posts[]" class="form-control setupSelect2" 
                        data-placeholder="Chọn bài viết liên quan..."
                    >
                        @if(isset($postsSelection) && count($postsSelection))
                            @foreach($postsSelection as $val)
                                @php
                                    $isSelected = false;
                                    if(isset($relatedPosts) && count($relatedPosts)){
                                        foreach($relatedPosts as $related){
                                            if($related->id == $val->id){
                                                $isSelected = true;
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                                <option 
                                    value="{{ $val->id }}" 
                                    {{ ($isSelected) ? 'selected' : '' }}
                                    {{ (isset($post->id) && $post->id == $val->id) ? 'disabled' : '' }}
                                >
                                    {{ $val->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
