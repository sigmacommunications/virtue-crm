@extends('layouts.app')


@section('content')
    <style>
        .drag-container {
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }


        .board {
        width: 100%;
        display: flex;
        flex-wrap: nowrap;
        flex-direction: row;
        overflow-x: scroll;
        border: 1px solid #ccc; /* Just for visualization */
        }
        .board-column {
            min-width: 400px; /* Set a minimum width for items */
            padding: 10px;
            border: 1px solid #ccc; /* Just for visualization */
            margin-right: 10px;
        }
        /* .board {
            position: relative;
            width: max-content;
            display: flex;
            flex-wrap: nowrap;
            flex-direction: row;
            overflow-x: scroll;
        }
        .board-column {
            position: absolute;
            left: 0;
            top: 0;
            padding: 0 10px;
            width: 300px;
            z-index: 1;
            float: left;
        } */

        .board-column.muuri-item-releasing {
            z-index: 2;
        }

        .board-column.muuri-item-dragging {
            z-index: 3;
            cursor: move;
        }

        .board-column-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .board-column-header {
            position: relative;
            height: 50px;
            line-height: 50px;
            overflow: hidden;
            padding: 0 20px;
            text-align: center;
            background: #333;
            color: #fff;
            border-radius: 5px 5px 0 0;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        @media (max-width: 600px) {
            .board-column-header {
                text-indent: -1000px;
            }
        }

        .board-column-header {}

        .board-column-content-wrapper {
            position: relative;
            padding: 8px;
            background: #f0f0f0;
            height: 20vh;
            overflow-y: auto;
            border-radius: 0 0 5px 5px;
        }

        .board-column-content {
            position: relative;
            min-height: 100%;
        }

        .board-item {
            position: absolute;
            width: calc(100% - 16px);
            margin: 8px;
        }

        .board-item.muuri-item-releasing {
            z-index: 9998;
        }

        .board-item.muuri-item-dragging {
            z-index: 9999;
            cursor: move;
        }

        .board-item.muuri-item-hidden {
            z-index: 0;
        }

        .board-item-content {
            position: relative;
            padding: 20px;
            background: #fff;
            border-radius: 4px;
            font-size: 17px;
            cursor: pointer;
            -webkit-box-shadow: 0px 1px 3px 0 rgba(0, 0, 0, 0.2);
            box-shadow: 0px 1px 3px 0 rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 600px) {
            .board-item-content {
                text-align: center;
            }

            .board-item-content span {
                display: none;
            }
        }
    </style>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1>Project Management</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item active">Project Management</li>
                                    </ol>
                                </div>
                            </div>
                        </div><!-- /.container-fluid -->
                    </section>
                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                     <div class="drag-container"></div>
                                      @foreach($user_depart as $dk => $pd)
                                            <h2>{{$pd->department->departments}}</h2>
                                            <div class="board">
                                                @foreach ($projectstatus as $k => $ps)
                                                    <div class="board-column">
                                                        <div class="board-column-container" >
                                                            <div class="board-column-header">{{$ps->name}}</div>
                                                            <div class="board-column-content-wrapper">
                                                                <div class="board-column-content" data-status-id="{{$ps->id}}">
                                                                    @php
                                                                        $pcs = $project->ProjectCurrentStatus()->where("status_id",$ps->id)->where("department_id",$pd->department_id)->latest()->first();
                                                                        $newnoti = $project->Notifications->where("user_id",auth()->id())->where("department_id",$pd->department_id)->where("read", 0)->where("is_comment", 1)->count()
                                                                    @endphp
                                                                    @if(!empty($pcs))
                                                                        <a href="{{ route('project-conversation', ['id' => $project->id, 'department_id' => $pd->department_id]) }}" class="text-dark board-item" project-id="{{$project->id}}" data-status="{{$ps->id}}" data-department="{{$pd->department_id}}">
                                                                            <div class="board-item-content d-flex justify-content-between"><span>{{ $project->name }}  </span><span class="badge badge-danger">{{ ($newnoti != 0) ? $newnoti : ""  }}</span></div>
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach




                                    {{-- <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Project List</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="pull-right">
                        @can('project-create')
                            <a class="btn btn-primary" style="margin-bottom:5px" href="{{ route('project.create') }}"> + Add Project</a>
                        @endcan
                        </div>
                        <!-- <table id="example1" class="table table-bordered table-striped"> -->
                        <table id="order-listing" class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info">
                        <thead>
                            <tr>
                            <th>S.No</th>
                            <th>Project Name</th>
                            <th>Client</th>
                            <th>Start Date</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($projects)
                            @php
                            $id =1;
                            @endphp
                            @foreach ($projects as $key => $pro)
                            <tr>
                            <td>{{$id++}}</td>
                            <td>{{ $pro->name }}</td>
                            <td>{{ $pro->client->company_name }}</td>
                            <td>{{ $pro->start_date }}</td>
                            <td>{{ $pro->deadline }}</td>
                            <td>{{ $pro->status }}</td>
                            <td>
                            <div class="btn-group">
                            @can('project-edit')
                                <a class="btn btn-primary btn-a" href="{{ route('project.edit',$pro->id) }}">Edit</a>
                            @endcan
                            @can('project-delete')
                            <form method="post" action="{{route('project.destroy',$pro->id)}}">
                                @csrf
                                @method('delete')
                                <button type="submit" onclick="return confirm('Are You Sure Want To Delete This.??')" type="button" class="btn btn-danger btn-b"><i class="fa fa-trash"></i></button>
                            </form>
                            @endcan
                            @can('project-conversation')
                                <a href="{{route("project-conversation" , $pro->id)}}" class="btn btn-a btn-warning">Conversation</a>
                            @endcan
                            </div>
                            </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                        </table>
                    </div>
                    </div> --}}



                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/web-animations/2.3.2/web-animations.min.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/haltu/muuri@0.9.5/dist/muuri.min.js"></script>
        <script>
            var dragContainer = document.querySelector('.drag-container');
            var itemContainers = [].slice.call(document.querySelectorAll('.board-column-content'));
            debugger;
            var columnGrids = [];
            // var boardGrid;
            // Init the column grids so we can drag those items around.
            itemContainers.forEach(function(container) {
                var grid = new Muuri(container, {
                        items: '.board-item',
                        dragEnabled: true,
                        dragSort: function() {
                            return columnGrids;
                        },
                        dragContainer: dragContainer,
                        dragAutoScroll: {
                            targets: (item) => {
                                return [{
                                        element: window,
                                        priority: 0
                                    },
                                    {
                                        element: item.getGrid().getElement().parentNode,
                                        priority: 1
                                    },
                                ];
                            }
                        },
                    })
                    .on('dragInit', function(item) {
                        item.getElement().style.width = item.getWidth() + 'px';
                        item.getElement().style.height = item.getHeight() + 'px';
                    })
                    .on('dragReleaseEnd', function(item) {
                        const elem = item.getElement();
                        var project = elem.getAttribute("project-id");
                        // var status = elem.getAttribute("data-status");
                        var department = elem.getAttribute("data-department");
                        const parent = item.getGrid().getElement();
                        const status = parent.getAttribute("data-status-id");


                        item.getElement().style.width = '';
                        item.getElement().style.height = '';
                        item.getGrid().refreshItems([item]);

                        $.ajax({
                            type: "POST",
                            url: "{{route('project-kanban-update')}}",
                            data: {
                                project: project,
                                status: status,
                                department: department,
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                // Handle success response
                                console.log(response);
                            },
                            error: function(xhr, status, error) {
                                // Handle error
                                console.error(xhr.responseText);
                            }
                        });
                    })
                    .on('click', function (item, event) {
                        alert("sss");
                    });

                columnGrids.push(grid);
            });

            // Init board grid so we can drag those columns around.
            // boardGrid = new Muuri('.board', {
            //     dragEnabled: true,
            //     dragHandle: '.board-column-header'
            // });
        </script>
    @endsection
