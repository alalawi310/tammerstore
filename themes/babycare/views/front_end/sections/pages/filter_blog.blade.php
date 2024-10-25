
@foreach ($blogs as $key => $blog)
@if($request->cat_id == '0' || $blog->category_id == $request->cat_id)
<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 blog-itm">
                                            <div class="blog-inner">
                                                <div class="blog-img">
                                                    <a href="{{ route('page.article', ['storeSlug'=> $slug,$blog->id]) }}">
                                                        <img src="{{ get_file($blog->cover_image_path, $currentTheme) }}"  class="cover_img{{ $blog->id }}" alt="blog-img">
                                                    </a>
                                                </div>
                                                <div class="blog-content">
                                                    <h4><a href="{{ route('page.article', ['storeSlug'=> $slug,$blog->id]) }}">{{$blog->title}}</a> </h4>
                                                    <p>{{$blog->short_description}}</p>
                                                    <a href="{{ route('page.article', ['storeSlug'=> $slug,$blog->id]) }}" class="btn-secondary">
                                                        Read more
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
        
    @endif
    @endforeach
