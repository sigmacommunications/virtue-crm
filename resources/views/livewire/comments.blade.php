
    {{-- Success is as dangerous as failure. --}}

    <div class="col-md-12">
        <div class="card">

            <div class="p-3">
                <h6>Comments <b>{{$comments->count()}}</b></h6>
            </div>
            <div class="mt-2">
                @foreach ($comments as $k => $comment )
                <div class="d-flex flex-row p-3">
                      {{-- <img src="https://i.imgur.com/zQZSWrt.jpg" width="40" height="40"
                        class="rounded-circle mr-3"> --}}

                    <div class="w-100">

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-row align-items-center">
                                <span class="mr-2">{{ $comment->user->name }}</span>
                               @if($k == 0)
                                <small class="c-badge">Top Comment</small>
                                @endif
                            </div>
                            <small>{{$comment->created_at->diffForHumans() }}</small>
                        </div>

                        <p class="text-justify comment-text mb-0"><?= $comment->comment ?>  </p>
                         <div class="d-flex align-items-start user-feed mt-3">
                          @can("delete-comment")
                            @if($comment->user->id == auth()->id())
                             <a href="#" wire:click="deleteConversation({{ $comment->id }})" onclick="return confirm('Are you sure you want to delete this conversation?')">
                                <span class="text-danger"><i class="fa fa-trash mr-2"></i>Delete</span>
                            </a>
                            @endif
                          @endcan
                         </div>

                    </div>


                </div>

                @endforeach



            </div>
            @if ($showMoreButton)
                <button wire:click="loadMore" class="btn btn-lg btn-primary">
                    Load More
                </button>
             @endif
        </div>
    </div>

